<!doctype html>
<html lang="en" dir="ltr">

<?= $this->include('template/element/head') ?>

<body class="geex-dashboard">

<?= $this->include('template/element/header') ?>

<main class="geex-main-content">

   <?= $this->include('template/element/sidebar') ?>

<!--   --><?php //= $this->include('template/element/customizer') ?>


    <div class="geex-content">
       <?= $this->include('template/element/contentHeader') ?>
       <?= $this->renderSection('content') ?> <!-- Main content from the page -->

    </div>
</main>

<!-- JAVASCRIPTS START -->
<script>
    const base_url = '<?= base_url() ?>';
</script>

<script src="/assets/vendor/js/jquery/jquery-3.5.1.min.js"></script>
<script src="/assets/vendor/js/jquery/jquery-ui.js"></script>
<script src="/assets/vendor/js/bootstrap/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?= $this->renderSection('custom_scripts') ?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.6.6/dragula.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/main.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userData = localStorage.getItem('userData');
        if (userData) {
            const data = JSON.parse(userData);
            document.getElementById('nameUser').innerHTML = data.data.nome;
            //console.log(data);
        } else {
            fetch('/api/v1/usuarios/me')
                .then(response => response.json())
                .then(data => {
                    localStorage.setItem('userData', JSON.stringify(data));
                    document.getElementById('nameUser').innerHTML = data.data.nome;
                    //console.log(data);
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                });
        }
    });
</script>

<?= $this->renderSection('scripts') ?>
<!-- JAVASCRIPTS END -->
</body>

</html>