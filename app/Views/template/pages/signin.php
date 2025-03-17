<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SignIn - Geex Dashboard</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon.svg">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/vendor/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <script>
        // Load theme and layout preferences from localStorage
        if (localStorage.theme) document.documentElement.setAttribute("data-theme", localStorage.theme);
        if (localStorage.navbar) document.documentElement.setAttribute("data-nav", localStorage.navbar);
        if (localStorage.layout) document.documentElement.setAttribute("dir", localStorage.layout);
    </script>
</head>

<body class="geex-dashboard authentication-page">
<main class="geex-content">
    <div class="geex-content__authentication">
        <div class="geex-content__authentication__content">
            <div class="geex-content__authentication__content__wrapper">
                <div class="geex-content__authentication__content__logo">
                    <a href="#">
                        <img class="logo-lite" src="/assets/img/logo-dark.svg" alt="logo">
                        <img class="logo-dark" src="/assets/img/logo-lite.svg" alt="logo">
                    </a>
                </div>

                <!-- LOGIN FORM START -->
                <form id="signInForm" class="geex-content__authentication__form">
                    <h2 class="geex-content__authentication__title">Entre na sua conta 👋</h2>
                    <div class="geex-content__authentication__form-group">
                        <label for="emailSignIn">Seu e-mail</label>
                        <input type="email" id="emailSignIn" name="emailSignIn" placeholder="Enter Your Email" required>
                        <i class="uil-envelope"></i>
                    </div>
                    <div class="geex-content__authentication__form-group">
                        <div class="geex-content__authentication__label-wrapper">
                            <label for="loginPassword">Sua senha</label>
                            <a href="#">Esqueceu sua senha?</a>
                        </div>
                        <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required>
                        <i class="uil-eye toggle-password-type"></i>
                    </div>
                    <div class="geex-content__authentication__form-group custom-checkbox">
                        <input type="checkbox" class="geex-content__authentication__checkbox-input" id="rememberMe">
                        <label class="geex-content__authentication__checkbox-label" for="rememberMe">Lembre de mim</label>
                    </div>
                    <button type="submit" class="geex-content__authentication__form-submit">Entrar</button>
                    <span class="geex-content__authentication__form-separator">Ou</span>

                    <!-- SOCIAL SECTION START -->
                    <div class="geex-content__authentication__form-social">
                        <a href="#" class="geex-content__authentication__form-social__single">
                            <img src="/assets/img/icon/google.svg" alt="Google"> Google
                        </a>
                    </div>
                    <!-- SOCIAL SECTION END -->

<!--                    <div class="geex-content__authentication__form-footer">-->
<!--                        Don't have an account? <a href="#">Sign Up</a>-->
<!--                    </div>-->
                </form>
                <!-- LOGIN FORM END -->
            </div>
        </div>

        <!-- SIDE IMAGE START -->
        <div class="geex-content__authentication__img">
            <img src="/assets/img/authentication.svg" alt="Authentication">
        </div>
        <!-- SIDE IMAGE END -->
    </div>
</main>

<!-- Scripts -->
<script src="/assets/vendor/js/jquery/jquery-3.5.1.min.js"></script>
<script src="/assets/vendor/js/jquery/jquery-ui.js"></script>
<script src="/assets/vendor/js/bootstrap/bootstrap.min.js"></script>
<!--<script src="/assets/js/main.js"></script>-->
</body>
</html>