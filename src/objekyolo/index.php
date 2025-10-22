<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Deteksi Objek YOLO</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; margin: 20px; background: #f4f4f4; color: #333; }
        h2 { color: #007bff; }
        .container { max-width: 1000px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        #status { margin-top: 10px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Realtime Deteksi Objek YOLO</h2>
        <p>Data akan diperbarui otomatis setiap 3 detik.</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kelas</th>
                    <th>Akurasi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr><td colspan="4" style="text-align: center;">Memuat data...</td></tr>
            </tbody>
        </table>
        <div id="status"></div>
    </div>

    <script>
        async function loadData() {
            const statusEl = document.getElementById("status");
            try {
                const res = await fetch('get_data.php');
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const data = await res.json();
                let rows = "";
                
                if (data.length === 0) {
                    rows = '<tr><td colspan="4" style="text-align: center;">Belum ada data deteksi.</td></tr>';
                } else {
                    data.forEach(d => {
                        let confidence = (parseFloat(d.confidence) * 100).toFixed(2);
                        rows += `<tr>
                            <td>${d.id}</td>
                            <td>${d.class_name}</td>
                            <td>${confidence}%</td>
                            <td>${d.timestamp}</td>
                        </tr>`;
                    });
                }
                document.getElementById("table-body").innerHTML = rows;
                statusEl.textContent = "Data diperbarui pada: " + new Date().toLocaleTimeString();
            } catch (err) {
                console.error("Gagal muat data:", err);
                statusEl.textContent = "Gagal memuat data. Cek console.";
                document.getElementById("table-body").innerHTML = '<tr><td colspan="4" style="text-align: center; color: red;">Gagal memuat data.</td></tr>';
            }
        }

        window.onload = () => {
            loadData(); // Muat pertama kali
            setInterval(loadData, 3000); // Update tiap 3 detik
        };
    </script>
</body>
</html>