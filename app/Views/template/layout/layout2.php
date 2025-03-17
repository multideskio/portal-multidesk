

<!doctype html>
<html lang="en" dir="ltr">

    <?= $this->element('head') ?> 


<body class="geex-dashboard">

    <?= $this->element('header') ?> 


	<main class="geex-main-content">

		<?= $this->element('sidebar') ?> 

		<?= $this->element('customizer') ?> 

		<div class="geex-content">
			<?= $this->fetch('content') ?> <!-- Main content from the page -->
		</div>
	</main>

	<!-- JAVASCRIPTS START -->
	<script src="<?= $this->Url->assetUrl('assets/vendor/js/jquery/jquery-3.5.1.min.js') ?>"></script>
	<script src="<?= $this->Url->assetUrl('assets/vendor/js/jquery/jquery-ui.js') ?>"></script>
	<script src="<?= $this->Url->assetUrl('assets/vendor/js/bootstrap/bootstrap.min.js') ?>"></script>
	<script src="<?= $this->Url->assetUrl('assets/js/main.js') ?>"></script>
	<!-- JAVASCRIPTS END -->
</body>

</html>