<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Information Board</title>
    @vite('resources/css/app.css')
    
    <style>
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
    </style>
</head>
<body>
    <div id="display-container">
        
        <div id="image-wrapper">
            @foreach ($images as $key => $path)
                <img src="{{ Storage::url($path) }}" 
                     alt="Poster {{ $key + 1 }}" 
                     class="slide-image {{ $key === 0 ? 'active' : '' }}" 
                     data-index="{{ $key }}">
            @endforeach
        </div>

        <div id="real-time-clock"></div>
    </div>

    <script>
        // --- 1. Jam Real-Time (HH:MM:SS) ---
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
        
        // --- 2. Slideshow Otomatis ---
        const images = document.querySelectorAll('.slide-image');
        const totalImages = images.length;

        if (totalImages > 1) {
            let currentImageIndex = 0;
            const slideshowInterval = 7000; // 7 detik

            function nextSlide() {
                images[currentImageIndex].classList.remove('active');

                currentImageIndex = (currentImageIndex + 1) % totalImages;

                images[currentImageIndex].classList.add('active');
            }

            setInterval(nextSlide, slideshowInterval);
        }
    </script>
</body>
</html>