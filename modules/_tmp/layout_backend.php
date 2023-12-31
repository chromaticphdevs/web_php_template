<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	<link rel="shortcut icon" type="image/png" href="<?php echo _path_tmp('main/img/logo30x30.png')?>">

	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/bootstrap.min.css')?>">

	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/all.min.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/animations.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/dropzone/dropzone.min.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/mycss.css?ver=$curr')?>">
	<?php produce('headers')?>

	<style>
		.box{
			border: 1px solid #eee; border-radius:5px; padding:15px;
		}
	</style>
</head>
<body class="backcolor">
	<div class="offsettop"></div>
	<?php echo loader_tag();?>
	<?php echo topnav_tag();?>
	<main class="maxw1080 m-auto">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-9">
					<div class="allpost p-0">
						<?php produce('content')?>
					</div>
				</div>
				<?php echo sideadsfooter_tag();?>
			</div>
		</div>
	</main>
		
	<script src="<?php echo _path_public('libraries/jquery3.min.js')?>"></script>
	<script src="<?php echo _path_tmp('main/js/popper.min.js')?>"></script>
	<script src="<?php echo _path_tmp('main/js/all.min.js')?>" defer></script>
	<script src="<?php echo _path_tmp('main/js/bootstrap.bundle.min.js')?>"></script>
	<script src="<?php echo _path_public('js/prop.js')?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" 
	integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" 
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		$(document).ready(function(){
			if($('.form-verify-action')){
				$('.form-verify-action').click(function(evt) {

					let message = $(this).attr('data-message');

					if(message == null) {
						message = 'Are you sure you want to continue this proccess , irreversible process ';
					}
					if(!confirm(message)){
						evt.preventDefault();
					}
				});
			}
		});
	</script>
	<?php produce('scripts')?>
</body>
</html>

