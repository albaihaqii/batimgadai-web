<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('favicon-512.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon-32.png') }}?v=2">
    <title>BATIM GADAI - Sistem Informasi Gadai Elektronik</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Outfit', sans-serif; }

        /* Grid pattern seperti TailAdmin login */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(31,92,58,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(31,92,58,0.07) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .grid-pattern-light {
            background-image:
                linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Fade up animation */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-up-delay-1 { transition-delay: 0.1s; }
        .fade-up-delay-2 { transition-delay: 0.2s; }
        .fade-up-delay-3 { transition-delay: 0.3s; }
        .fade-up-delay-4 { transition-delay: 0.4s; }
        .fade-up-delay-5 { transition-delay: 0.5s; }
        .fade-up-delay-6 { transition-delay: 0.6s; }
    </style>
    @stack('styles')
</head>
<body class="bg-white antialiased text-gray-900">
    @yield('content')
    <script>
        // Fade up on scroll
        const fadeEls = document.querySelectorAll('.fade-up');
        const fadeObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.15 });
        fadeEls.forEach(el => fadeObs.observe(el));
    </script>
    @stack('scripts')
</body>
</html>