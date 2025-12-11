<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Information Board - No Content</title>
    @vite('resources/css/app.css')
    <style>
        html, body {
            height: 100%;
            background-color: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }
        #real-time-clock {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4rem;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            font-weight: bold;
            font-family: monospace;
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <h1 class="text-3xl font-bold">PAPAN INFORMASI DIGITAL</h1>
    <p class="mt-4 text-xl">Konten belum tersedia. Silakan upload poster melalui Admin Panel.</p>

    <div id="real-time-clock"></div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('real-time-clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>