<!doctype html>
<html lang="pt-BR" dir="ltr" data-theme="dark">
<?= $this->include('admin/head') ?>
<body class="geex-dashboard">
<?= $this->include('admin/header') ?>
<main class="geex-main-content">
   <?= $this->include('admin/sidebar') ?>
   <?= $this->include('admin/customizer') ?>
   <div class="geex-content">
       <?= $this->include('admin/contentHeader') ?>
       <?= $this->renderSection('content') ?>
   </div>
</main>

<!-- JAVASCRIPTS START -->
<script src="/assets/vendor/js/jquery/jquery-3.5.1.min.js"></script>
<script src="/assets/vendor/js/jquery/jquery-ui.js"></script>
<script src="/assets/vendor/js/bootstrap/bootstrap.min.js"></script>
<?php //= $this->fetch('custom_scripts') ?>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.6.6/dragula.min.js" referrerpolicy="origin"></script>
<script src="/assets/js/main.js"></script>
<!-- JAVASCRIPTS END -->
</body>
</html>