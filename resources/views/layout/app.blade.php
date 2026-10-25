<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .product-img {
            height: 220px;
            object-fit: cover;
        }

        .detail-img {
            width: 100%;
            max-height: 420px;
            object-fit: cover;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sticky-summary {
            position: sticky;
            top: 1.5rem;
        }
    </style>
    @stack('css')
</head>

<body class="d-flex flex-column min-vh-100">
    @include('template.navbar')

    <main class="flex-grow-1">
        @include('template.alert')
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} MBShop. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    @stack('js')
</body>

</html>
