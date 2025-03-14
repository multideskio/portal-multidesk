<!doctype html>
<html lang="en" dir="ltr">

<?= $this->element('head') ?> 

<body class="geex-dashboard">

    <?= $this->element('header') ?> 

    <main class="geex-main-content">
        
        <?= $this->element('sidebar') ?> 

		<?= $this->element('customizer') ?>
        

        <div class="geex-content">
            <?= $this->element('contentHeader') ?> 
            <?= $this->fetch('content') ?> <!-- Main content from the page -->

        </div>
    </main>

    <!-- JAVASCRIPTS START -->
    <script src="<?= $this->Url->assetUrl('assets/vendor/js/jquery/jquery-3.5.1.min.js') ?>"></script>
	<script src="<?= $this->Url->assetUrl('assets/vendor/js/jquery/jquery-ui.js') ?>"></script>
	<script src="<?= $this->Url->assetUrl('assets/vendor/js/bootstrap/bootstrap.min.js') ?>"></script>

    <?= $this->fetch('custom_scripts') ?>
    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.6.6/dragula.min.js" referrerpolicy="origin"></script>
    <script src="<?= $this->Url->assetUrl('assets/js/main.js') ?>"></script>

    <!-- JAVASCRIPTS END -->
</body>

</html>