from flask import Flask, Response
from ultralytics import YOLO
import numpy as np
import cv2
import time
import requests
from datetime import datetime

# =====================================================
# Inisialisasi Flask & YOLO
# =====================================================
app = Flask(__name__)
model = YOLO("my_model.pt")

# =====================================================
# Sumber kamera dan API PHP
# =====================================================
DROIDCAM_HTTP = ["http://192.168.110.254:4747/video"]  # sesuaikan IP droidcam kamu
PHP_API_URL = "http://php_apache/objekyolo/save_detection.php"  # arahkan ke PHP API kamu

# =====================================================
# Fungsi koneksi kamera
# =====================================================
def try_open_camera():
    """Coba buka webcam lokal atau DroidCam"""
    for idx in range(3):
        cap = cv2.VideoCapture(idx)
        if cap.isOpened():
            print(f"Terhubung ke USB webcam index {idx}")
            return cap

    for url in DROIDCAM_HTTP:
        cap = cv2.VideoCapture(url)
        if cap.isOpened():
            print(f"Terhubung ke DroidCam HTTP: {url}")
            return cap

    print("Tidak ada kamera yang bisa dibuka.")
    return None

# =====================================================
# Fungsi kirim hasil deteksi ke PHP
# =====================================================
def send_to_php(detections):
    """Kirim hasil deteksi ke PHP API"""
    try:
        payload = {"detections": detections}  # ubah di sini, bungkus array ke dalam key detections
        headers = {"Content-Type": "application/json"}
        response = requests.post(PHP_API_URL, json=payload, headers=headers, timeout=5)
        print(f"Kirim ke PHP: {payload} | Respon: {response.text}")
    except Exception as e:
        print("^ Gagal kirim ke PHP:", e)

# =====================================================
# Streaming video + deteksi YOLO (VERSI OPTIMASI)
# =====================================================
def gen_frames():
    cap = None
    last_sent = 0  # waktu terakhir kirim ke PHP
    
    # --- TAMBAHAN UNTUK OPTIMASI ---
    frame_count = 0
    cached_results = None
    # -------------------------------

    while True:
        if cap is None or not cap.isOpened():
            print("Mencoba koneksi kamera...")
            cap = try_open_camera()
            time.sleep(1)

        if not cap:
            frame = 255 * np.ones((480, 640, 3), dtype=np.uint8)
        else:
            success, frame = cap.read()
            if not success:
                print("Gagal membaca frame, reconnect...")
                cap.release()
                cap = None
                continue
        
        frame_count += 1
        
        # --- BLOK OPTIMASI ---
        # Kita hanya jalankan deteksi 1x setiap 3 frame
        if frame_count % 3 == 0:
            # Jalankan deteksi
            results = model(frame, conf=0.6, iou=0.5)
            cached_results = results  # Simpan hasil deteksi
            detections = []

            for r in results:
                for box in r.boxes:
                    class_id = int(box.cls[0])
                    conf = float(box.conf[0])
                    class_name = model.names[class_id]

                    if conf >= 0.6:
                        detections.append({
                            "class_name": class_name,
                            "confidence": conf,
                            "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                        })

            # Kirim tiap 5 detik (PINDAHKAN ke dalam blok 'if' ini)
            if detections and (time.time() - last_sent > 5):
                send_to_php(detections)
                last_sent = time.time()
        
        # --- AKHIR BLOK OPTIMASI ---

        # Gambar hasil deteksi (Gunakan hasil cache)
        if cached_results:
            # Gambar hasil deteksi TERAKHIR di frame BARU
            frame = cached_results[0].plot() 

        # Encode ke JPEG untuk streaming
        ret, buffer = cv2.imencode('.jpg', frame)
        if not ret:
            continue

        frame_bytes = buffer.tobytes()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')

# =====================================================
# Flask routes
# =====================================================
@app.route("/")
def home():
    return {"message": "YOLO Flask API aktif dan siap kirim ke PHP/MySQL"}

@app.route("/video_feed")
def video_feed():
    return Response(gen_frames(), mimetype="multipart/x-mixed-replace; boundary=frame")

# =====================================================
# Jalankan server Flask
# =====================================================
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)