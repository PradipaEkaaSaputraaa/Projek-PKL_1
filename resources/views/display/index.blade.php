<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Information Board</title>
    {{-- Memuat Tailwind CSS melalui Vite --}}
    @vite('resources/css/app.css')
    
    <style>
        /* CSS Utama untuk Fullscreen dan Background Hitam */
        html, body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background-color: #000;
        }
        
        #display-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Area Wrapper Gambar (Menyesuaikan dengan jam di bawah) */
        #image-wrapper {
            height: calc(100vh - 4rem); 
            width: auto;
            max-width: 100vw;
            position: relative;
            overflow: hidden;
        }
        
        /* Layout Portrait (9:16) pada Layar Landscape */
        @media (orientation: landscape) {
             #image-wrapper {
                width: calc((100vh - 4rem) * 9 / 16); 
                height: calc(100vh - 4rem);
                max-width: 100vw;
            }
        }

        /* Layout Portrait (9:16) pada Layar Portrait */
        @media (orientation: portrait) {
             #image-wrapper {
                width: 100vw;
                height: calc(100vw * 16 / 9); 
                max-height: 100vh;
            }
        }
        
        /* Gaya Gambar Slideshow */
        .slide-image {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out; 
        }
        
        .slide-image.active {
            opacity: 1;
        }

        /* Gaya Jam Real-Time */
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
            z-index: 10;
        }
        
        /* Gaya Tombol Logout */
        .logout-btn-display {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 20; /* Lebih tinggi dari jam */
        }
    </style>
</head>
<body>
    <div id="display-container">
        
        {{-- Tombol Logout (Hanya tampil jika user terotentikasi) --}}
        @auth 
            <div class="logout-btn-display">
                <a class="text-white bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded-lg shadow-xl text-sm" 
                   href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
            </div>
        @endauth
        
        {{-- Form tersembunyi untuk proses POST Logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        {{-- Wrapper Gambar Slideshow --}}
        <div id="image-wrapper">
            @foreach ($images as $key => $path)
                <img src="{{ Storage::url($path) }}" 
                     alt="Poster {{ $key + 1 }}" 
                     class="slide-image {{ $key === 0 ? 'active' : '' }}" 
                     data-index="{{ $key }}">
            @endforeach
            
            {{-- Tampilan Default jika tidak ada gambar --}}
            @if (count($images) === 0)
                <div class="slide-image active flex items-center justify-center bg-gray-900 text-white text-3xl">
                    Tidak Ada Poster Aktif
                </div>
            @endif
        </div>

        {{-- Jam Real-Time --}}
        <div id="real-time-clock"></div>
    </div>

    <script>
        // --- 1. Jam Real-Time (HH:MM:SS) ---
        function updateClock() {
            const now = new Date();
            // Menggunakan local time Indonesia (WIB/WITA/WIT), jika server/klien sudah diset dengan benar
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('real-time-clock').textContent = timeString;
        }

        setInterval(updateClock, 1000);
        updateClock(); 
        
        // --- 2. Slideshow Otomatis ---
        const images = document.querySelectorAll('.slide-image');
        const totalImages = images.length;

        // Hanya jalankan slideshow jika ada lebih dari satu gambar
        if (totalImages > 1) {
            let currentImageIndex = 0;
            const slideshowInterval = 7000; // 7 detik

            function nextSlide() {
                // Hapus kelas 'active' dari gambar saat ini
                images[currentImageIndex].classList.remove('active');

                // Pindah ke indeks berikutnya, kembali ke 0 jika sudah terakhir
                currentImageIndex = (currentImageIndex + 1) % totalImages;

                // Tambahkan kelas 'active' ke gambar baru
                images[currentImageIndex].classList.add('active');
            }

            // Mulai interval slideshow
            setInterval(nextSlide, slideshowInterval);
        }
    </script>
</body>
</html>