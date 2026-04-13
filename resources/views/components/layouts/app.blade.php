<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPADU' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#effaf3',
                            100: '#d8f2e0',
                            200: '#b3e4c2',
                            300: '#82d09d',
                            400: '#4eb572',
                            500: '#289353',
                            600: '#1e7442',
                            700: '#195d37',
                            800: '#17492d',
                            900: '#143c26',
                        },
                        ink: '#183228',
                        mist: '#f4f8f4',
                    },
                    boxShadow: {
                        soft: '0 20px 45px -28px rgba(22, 60, 38, 0.28)',
                        card: '0 18px 32px -24px rgba(25, 93, 55, 0.25)',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('portal.css') }}">
</head>
<body class="min-h-screen flex flex-col antialiased">
    @include('components.cyberpunk-bg')
    <div class="relative isolate flex-1 flex flex-col">
        {{ $slot }}
    </div>
    <script src="{{ asset('portal.js') }}"></script>
</body>
</html>
