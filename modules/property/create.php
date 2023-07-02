<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

	<?php
		$service = new PropertyService();

		$_SESSION['message'] = '';
		if(isset($_POST['submit'])) {
			$post = $_POST;
			$service->store($post);
		}
	?>
	<a href="property_index.php">Show lists</a>
	<form method="post" action="">
		<?php if(!empty($_SESSION['message'])) :?>
			<?php echo $_SESSION['message']?>
		<?php endif?>
		<div>
			<label>Name</label>
			<input type="text" name="prop_name" placeholder="Insert Name Here">
		</div>

		<div>
			<label>Keyword</label>
			<input type="text" name="prop_keyword" placeholder="Insert Keyword">
		</div>

		<div>
			<label>Property Type</label>
			<input type="text" name="prop_type" placeholder="Insert Property Type">
		</div>

		<div>
			<label>Error</label>
			<input type="text" name="test_col_error" placeholder="Insert Property Type">
		</div>

		<div>
			<input type="submit" name="submit" value="Submit">
		</div>
	</form>
</body>
</html>