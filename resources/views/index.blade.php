<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="POPCOM dev team">
    <title>POPCOM</title>
    <link rel="icon" href="{{ asset('imgs/favicon.ico') }}" type="image/gif">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>
<body>

<main class="d-flex align-items-center justify-content-between main">
    <div class="container content-wrapper">
        <img class="img-fluid popcom-logo" src="{{ asset('imgs/logo-with-text.png') }}">
        <div class="columns-wrapper">
            <div class="order-2 left-col">
                <img class="img-fluid popcom-logo" src="{{ asset('imgs/logo-with-text.png') }}">
                <div class="buttons-wrapper">
                    <a href="#" class="btn playstore-btn" style="background-image:url('{{ asset("imgs/play-store.png") }}'); cursor: default;"></a>
                    <a href="/api" class="btn api-btn">Api Documentation</a>
                </div>
            </div>
            <div class="order-1 right-col">
                <img class="img-fluid mobile-illustration" src="{{ asset('imgs/mobile-illustration.png') }}">
            </div>
        </div>
    </div>
    <svg class="svg-bg" viewBox="0 0 500 150" preserveAspectRatio="none">
        <path d="M-2.54,58.70 C184.25,11.34 348.47,109.03 500.84,12.33 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #043D10;"></path>
    </svg>
</main>
<footer class="fixed-bottom">
    <div class="d-md-flex align-items-center justify-content-between">
        <div>
            <span class="copyright-text">&copy; Population Comission Region VII.</span>
            <span class="rights-text">All Rights Reserved.</span>
        </div>
        <div>For inquiries, email us at: dev@popcom.app</div>
    </div>
</footer>

<script src="{{ asset('js/jquery-3.5.1.slim.min.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
</body>
</html>
