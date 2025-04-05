<?= $this->include('partials/main') ?>
<?php
$page   = $page ?? 'Dashboard';
$titulo = $titulo ?? 'Multidesk';
?>
<head>
   <?php echo view('partials/title-meta', array('title' => $titulo)); ?>
   <?= $this->renderSection('css') ?>
   <?= $this->include('partials/head-css') ?>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#5840ff">
</head>
<body>

<!-- Begin page -->
<div id="layout-wrapper">
   <?= $this->include('clientes/menu') ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
               <?php echo view('partials/page-title', array('pagetitle' => $page, 'title' => $titulo)); ?>
               <?= $this->renderSection('content') ?>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
       <?= $this->include('partials/footer') ?>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<?= $this->include('partials/customizer') ?>
<?= $this->include('partials/vendor-scripts') ?>
<?= $this->renderSection('scripts') ?>
<!-- App js -->
<script src="/assets/js/app.js"></script>
<script src="/app.js"></script>

<script>
    (async () => {
        try {
            // Verifica se os dados do usuário já estão no localStorage
            const userData = localStorage.getItem('userData');

            let data;
            if (userData) {
                // Se os dados já estiverem salvos, parseia o JSON salvo
                data = JSON.parse(userData);
            } else {
                // Caso contrário, faz a requisição para buscar os dados
                const response = await fetch('/api/v1/me');
                if (!response.ok) {
                    throw new Error('Failed to fetch user data');
                }
                data = await response.json();

                // Armazena os dados no localStorage
                localStorage.setItem('userData', JSON.stringify(data));
            }

            // Atualiza os elementos no DOM com os dados do usuário
            const profileFoto = document.getElementById('profile-foto');
            const profileName = document.getElementById('profile-name');
            const profileEmail = document.getElementById('profile-email');

            if (profileFoto) {
                profileFoto.setAttribute('src', data.foto);
                profileFoto.style.display = 'block';
            }
            if (profileName) profileName.textContent = data.nome;
            if (profileEmail) profileEmail.textContent = data.email;

        } catch (error) {
            console.error(error.message);
        }
    })();
</script>
</body>

</html>