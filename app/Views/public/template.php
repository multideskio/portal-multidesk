<?php $titulo = $titulo ?? "" ; ?><!doctype html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?= $titulo ?> | Portal Multidesk.io</title>
   <link rel="manifest" href="/manifest.json">
   <meta name="theme-color" content="#5840ff">
   <?= $this->renderSection('meta') ?>
   <meta name="description" content="">
   <link rel="stylesheet" href="/assets/libs/bootstrap/css/bootstrap.min.css">
   <style>
       @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap');
       body {
           font-family: 'Nunito', sans-serif;
       }
       body {
           font-family: 'Poppins', sans-serif;
       }
       .btn-primary {
           background-color: #009b5b;
           color: #151515;
           border: none;
       }
       .hero {
           background-image: url('https://placehold.co/1920x300');
           background-size: cover;
           background-position: center;
           color: #fff;
           height: 300px;
           position: relative;
           box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
           display: flex;
           align-items: center;
           justify-content: center;
       }
       .hero-inner {
           max-width: 1020px;
           margin: 0 auto;
           text-align: center;
           padding: 0 15px;
           z-index: 2;
       }
       .hero-inner h1 {
           font-size: 36px;
           margin: 0;
           color: #fff;
           text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
           font-weight: 700;
       }
       .hero::before {
           content: "";
           position: absolute;
           inset: 0;
           background-color: rgba(30, 30, 30, 0.9);
           z-index: 1;
           box-shadow: inset 0 4px 15px rgba(0, 0, 0, 0.16);
       }
       @media (max-width: 768px) {
           .hero {
               padding: 50px 0;
           }
           .hero-inner h1 {
               font-size: 24px;
           }
           #info-evento {
               height: 30vh;
           }
       }
       footer {
           margin-top: 50px;
           font-size: 14px;
       }
       @media (max-width: 991px) {
           #info-evento {
               height: 30vh;
           }
       }
   </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
   <div class="container">
      <a class="navbar-brand" href="#">
         <img src="/assets/img/logo-dark.svg" alt="Portal Multidesk.io" width="160">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir navegação">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav ms-auto">
            <li class="nav-item">
               <a class="nav-link active" aria-current="page" href="/">Home</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="/contato">Contato</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="/login">Login</a>
            </li>
         </ul>
      </div>
   </div>
</nav>
<?= $this->renderSection('content') ?>
<footer class="bg-light text-center text-lg-start mt-5">
   <div class="container p-4">
      <div class="row">
         <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
            <h5 class="text-uppercase">Sobre nós</h5>
            <p>
               Portal Multidesk.io é uma plataforma focada em entregar conteúdo de qualidade.
            </p>
         </div>
         <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
            <h5 class="text-uppercase">Links úteis</h5>
            <ul class="list-unstyled mb-0">
               <li>
                  <a href="/" class="text-dark">Home</a>
               </li>
               <li>
                  <a href="/contato" class="text-dark">Contato</a>
               </li>
               <li>
                  <a href="/login" class="text-dark">Login</a>
               </li>
            </ul>
         </div>
         <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
            <h5 class="text-uppercase">Redes sociais</h5>
            <ul class="list-unstyled mb-0">
               <li>
                  <a href="#" class="text-dark">Facebook</a>
               </li>
               <li>
                  <a href="#" class="text-dark">Twitter</a>
               </li>
               <li>
                  <a href="#" class="text-dark">Instagram</a>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="text-center p-3 text-white" style="background-color: rgb(30,30,30);">
      © 2024 Portal Multidesk.io. Todos os direitos reservados.
   </div>
</footer>
<script src="/assets/libs/bootstrap/js/bootstrap.min.js" defer></script>
<script>
    const button = document.querySelector('button[type="submit"]');
    const spinner = document.querySelector('#loading-icon');
    button.addEventListener('click', () => {
        spinner.classList.remove('d-none'); // Exibe o spinner ao clicar no botão
    });
</script>
</body>
</html>