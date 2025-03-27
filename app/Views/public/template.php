<?php $titulo = $titulo ?? "" ; ?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $titulo ?> | Portal Multidesk.io</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#5840ff">

    <link rel="stylesheet" href="/assets/libs/bootstrap/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap');
        body {
            margin: 0;
            padding: 0;
            background-color: #151515FF;
            color: rgba(232, 232, 232, 0.56);
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            height: 100vh;
        }

        footer{
            background-color: #151515FF;
        }

        h1 {
            font-size: 36px;
            font-weight: 900;
            margin: 0;
        }

        h3 {
            font-size: 2.5em;
            font-weight: 900;
        }

        h5 {
            font-size: 1.2em;
            font-weight: 900;
        }

        .description_card {
            font-size: 0.9em;
            font-weight: 400;
        }

        #colum_1 {
            background-color: #151515;
            color: #e1e1e1;
        }

        #colum_2 {
            background-color: #000;
            color: #fff;
        }

        @media (max-width: 991px) {
            #colum_2 {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<?= $this->renderSection('content') ?>
<script src="/assets/libs/bootstrap/js/bootstrap.min.js" defer></script>
</body>
</html>