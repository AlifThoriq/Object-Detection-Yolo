# --- Gunakan Python resmi ---
FROM python:3.11-slim

# --- Set working directory ---
WORKDIR /app

# --- Install system dependencies untuk OpenCV & PyTorch ---
RUN apt-get update && apt-get install -y \
    libgl1 \
    libglib2.0-0 \
    libopencv-dev \
    && rm -rf /var/lib/apt/lists/*

# --- Salin file requirements ---
COPY requirements.txt .

# --- Install dependencies ---
RUN pip install --upgrade pip --no-cache-dir \
    && pip install --no-cache-dir -r requirements.txt

# --- Install PyTorch (CPU version agar ringan) ---
RUN pip install torch torchvision torchaudio --index-url https://download.pytorch.org/whl/cpu

# --- Salin semua file aplikasi ---
COPY . .

# --- Set environment agar log muncul langsung di console ---
ENV PYTHONUNBUFFERED=1
ENV FLASK_ENV=production

# --- Expose port internal Flask ---
EXPOSE 5000

# --- Gunicorn start ---
# Catatan:
# - app:app -> sesuai dengan nama file & objek Flask di app.py
# - port container: 5000 (karena docker-compose sudah mapping 5002:5000)
# - threads: 4 -> paralel request ringan
# - timeout: 180 -> agar load model besar tidak timeout
CMD ["gunicorn", "--bind", "0.0.0.0:5000", "app:app", "--workers=1", "--threads=4", "--timeout", "180"]
