<!DOCTYPE html>
<html
	lang="es"
	class="light-style layout-wide customizer-hide"
	dir="ltr"
	data-theme="theme-default"
	data-assets-path="../../assets/"
	data-template="vertical-menu-template"
>
	<head>
        <?php require_once(RUTA_APP.'/views/home/templates/head.html'); ?>
        <link rel="stylesheet" href="<?php echo RUTA_URL; ?>css/pages/page-auth.css">
	</head>

	<body>
		<!-- Content -->
        <div class="">
            <?php require_once(RUTA_APP.'/views/home/templates/header.html');?>
			<!-- Banner -->
			<div
				id="carouselExampleControls"
				class="carousel slide"
				data-bs-ride="carousel"
			>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="https://www.cesurformacion.com/uploads/media/21-9-large/02/2302-que-es-la-imagen-personal.png?v=1-0" class="d-block w-100" alt="..." />
					</div>
				</div>
			</div>
			<!-- end banner -->

			<!-- mision y vision -->
			<div class="py-5" style="background-color: var(--bs-orange)">
				<div class="container">
					<div class="row text-white">
						<div class="col-sm">
							<div class="p-3">
								<h5 class="text-center text-white">MISION</h5>
								<p class="text-center">
									Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus cupiditate pariatur similique quibusdam! Deserunt, magnam.
								</p>
							</div>
						</div>
						<div class="col-sm">
							<div class="p-3">
								<h5 class="text-center text-white">VISION</h5>
								<p class="text-center">
									Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus cupiditate pariatur similique quibusdam! Deserunt, magnam.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>

        </div>
		<!-- / Content -->
	</body>
</html>

<!-- beautify ignore:end -->
