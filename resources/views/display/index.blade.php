<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Information Board</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: #000000;
        }
        
        #display-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Area gambar portrait 9:16 - dikecilkan */
        #image-wrapper {
            width: calc(92vh * 9 / 16);
            height: 92vh;
            max-width: 95vw;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }
        
        @media (orientation: portrait) {
            #image-wrapper {
                width: 95vw;
                height: calc(95vw * 16 / 9);
                max-height: 92vh;
            }
        }
        
        .slide-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .slide-image.active {
            opacity: 1;
        }

        /* Clock overlay - positioned at top left */
        #clock-overlay {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 15;
            pointer-events: none;
        }

        #clock-box {
            background: transparent;
            padding: 0;
        }

        #time-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            font-variant-numeric: tabular-nums;
            letter-spacing: 2px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.9), 
                         0 0 20px rgba(0, 0, 0, 0.7);
        }

        #date-display {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 2px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9), 
                         0 0 15px rgba(0, 0, 0, 0.7);
        }

        /* Running text - positioned inside image at bottom */
        #running-text-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.3) 0%, transparent 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        #running-text {
            display: flex;
            white-space: nowrap;
            animation: scroll-left 35s linear infinite;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.95),
                         0 0 20px rgba(0, 0, 0, 0.9),
                         2px 2px 4px rgba(0, 0, 0, 1);
        }

        #running-text span {
            padding: 0 50px;
            display: inline-flex;
            align-items: center;
        }

        #running-text span::before {
            content: '●';
            color: #4ecdc4;
            margin-right: 25px;
            font-size: 0.7rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        /* Logout button */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(220, 38, 38, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 40;
            text-decoration: none;
            display: inline-block;
            backdrop-filter: blur(8px);
        }

        .logout-btn:hover {
            background: rgba(185, 28, 28, 1);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.5);
        }

        /* Responsive untuk layar kecil */
        @media (max-width: 768px) {
            #time-display {
                font-size: 1.4rem;
                letter-spacing: 1.5px;
            }
            
            #date-display {
                font-size: 0.65rem;
            }
            
            #clock-overlay {
                top: 20px;
                left: 20px;
            }
            
            #running-text {
                font-size: 0.9rem;
            }
            
            #running-text-overlay {
                height: 80px;
            }
        }

        /* Loading placeholder */
        .loading-placeholder {
            width: 100%;
            height: 100%;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.4);
            font-size: 1.5rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div id="display-container">
        
        <!-- Logout Button (untuk authenticated users) -->
        @auth 
            <a class="logout-btn" 
               href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        @endauth
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Image Slideshow Wrapper -->
        <div id="image-wrapper">
            <!-- Images dari Laravel -->
            @foreach ($images as $key => $path)
                <img src="{{ Storage::url($path) }}" 
                     alt="Poster {{ $key + 1 }}" 
                     class="slide-image {{ $key === 0 ? 'active' : '' }}" 
                     data-index="{{ $key }}">
            @endforeach
            
            <!-- Placeholder jika tidak ada gambar -->
            @if (count($images) === 0)
                <div class="slide-image active loading-placeholder">
                    Tidak Ada Poster Aktif
                </div>
            @endif

            <!-- Clock Overlay (di dalam gambar) -->
            <div id="clock-overlay">
                <div id="clock-box">
                    <div id="time-display">00:00:00</div>
                    <div id="date-display">Loading...</div>
                </div>
            </div>

            <!-- Running Text Overlay (di dalam gambar) -->
            <div id="running-text-overlay">
                <div id="running-text">
                    <span>Selamat Datang di Universitas Janabadra  </span>
                    <span>Mengembangkan Potensi Membangun Masa Depan</span>
                    <span>Pendaftaran Mahasiswa Baru Dibuka</span>
                    <span>Mari Bergabung Bersama Kami</span>
                    <span>Berprestasi Berkarya Berdedikasi</span>
                    <span>Selamat Datang di Universitas</span>
                    <span>Mengembangkan Potensi Membangun Masa Depan</span>
                    <span>Pendaftaran Mahasiswa Baru Dibuka</span>
                    <span>Mari Bergabung Bersama Kami</span>
                    <span>Berprestasi Berkarya Berdedikasi</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Konfigurasi
        const SLIDESHOW_INTERVAL = 7000; // 7 detik
        
        // Fungsi untuk update jam dan tanggal
        function updateClock() {
            const now = new Date();
            
            // Format waktu HH:MM:SS
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('time-display').textContent = `${hours}:${minutes}:${seconds}`;
            
            // Format tanggal lengkap
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            document.getElementById('date-display').textContent = 
                `${dayName}, ${date} ${monthName} ${year}`;
        }

        // Slideshow logic
        const images = document.querySelectorAll('.slide-image');
        const totalImages = images.length;

        if (totalImages > 1) {
            let currentImageIndex = 0;

            function nextSlide() {
                images[currentImageIndex].classList.remove('active');
                currentImageIndex = (currentImageIndex + 1) % totalImages;
                images[currentImageIndex].classList.add('active');
            }

            setInterval(nextSlide, SLIDESHOW_INTERVAL);
        }

        // Initialize
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>