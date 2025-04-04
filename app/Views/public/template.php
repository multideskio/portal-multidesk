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

        :root {
            --bg-dark: #1f1f1f;
            --bg-darker: #121212;
            --text-light: #eaeaea;
        }

        html, body {
            height: 100%;
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Montserrat', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        footer {
            background-color: var(--bg-darker);
            padding: 15px 0;
            text-align: center;
            font-size: 14px;
            color: #aaa;
            flex-shrink: 0;
        }
        .ticket-card {
            background-color: #2a2a2a;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            height: 100%;
        }
        .ticket-card:hover {
            background-color: #333;
            transform: scale(1.01);
        }
        .badge-restante {
            background-color: #dc3545;
            padding: 0.4em 0.6em;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .qtd-input {
            background-color: #000;
            color: #fff;
            border: 1px solid #555;
            border-radius: 8px;
            padding: 6px 12px;
            text-align: center;
            max-width: 80px;
        }

        .btn-light {
            background-color: #fff;
            color: #000;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px 30px;
        }

        .btn-light:hover {
            background-color: #ddd;
        }

        .btn-continuar {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 14px 40px;
            border-radius: 12px;
        }
        .text-soft {
            color: rgba(255, 255, 255, 0.7) !important;
        }
    </style>
</head>
<body>
<main>
   <?= $this->renderSection('content') ?>
</main>
<footer>
    &copy; <?= date('Y') ?> Portal Multidesk.io — Todos os direitos reservados.
</footer>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session()->getFlashdata('msg')): ?>
    <script>
        Swal.fire({
            title: 'Tudo certo!',
            text: "<?= esc(session()->getFlashdata('msg')) ?>",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('erro')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: 'Opa!',
                text: "<?= esc(session()->getFlashdata('erro')) ?>",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Fechar'
            });
        })
    </script>
<?php endif; ?>
<?= $this->renderSection('scripts') ?>
</body>
</html>