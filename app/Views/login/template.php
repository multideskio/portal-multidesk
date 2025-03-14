<!doctype html>
<html lang="pt-BR" dir="ltr">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Entrar - Geex Dashboard</title>

   <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

   <!-- inject:css-->
   <link rel="stylesheet" href="/assets/vendor/css/bootstrap/bootstrap.css">
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
   <link rel="stylesheet" href="/assets/css/style.css">
   <!-- endinject -->
   <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon.svg">
   <!-- Fonts -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">

   <script>
       // Renderizar localStorage JS:
       if (localStorage.theme) document.documentElement.setAttribute("data-theme", localStorage.theme);
       if (localStorage.layout) document.documentElement.setAttribute("data-nav", localStorage.navbar);
       if (localStorage.layout) document.documentElement.setAttribute("dir", localStorage.layout);
   </script>

   <?= $this->renderSection('css') ?>

</head>

<body class="geex-dashboard authentication-page">
<main class="geex-content">
   <div class="geex-content__authentication">
      <div class="geex-content__authentication__content">
         <div class="geex-content__authentication__content__wrapper">
            <div class="geex-content__authentication__content__logo">
               <a href="">
                  <img class="logo-lite" src="/assets/img/logo-dark.svg" alt="logo">
                  <img class="logo-dark" src="/assets/img/logo-lite.svg" alt="logo">
               </a>
            </div>
            <?= $this->renderSection('content') ?>
         </div>
      </div>
      <!-- SIDE IMAGE START  -->
      <div class="geex-content__authentication__img">
         <img src="/assets/img/authentication.svg" alt="">
      </div>
      <!-- SIDE IMAGE END  -->
   </div>
</main>

<!-- inject:js-->
<script src="/assets/vendor/js/jquery/jquery-3.5.1.min.js"></script>
<script src="/assets/vendor/js/jquery/jquery-ui.js"></script>
<script src="/assets/vendor/js/bootstrap/bootstrap.min.js"></script>
<script src="/assets/js/main.js"></script>

<?= $this->renderSection('js') ?>


</body>
</html>
