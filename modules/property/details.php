<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8" name="description" 
	content="<?php echo _config('assets')['meta']['property']?>">
	<title></title>
</head>
<body>
	<?php
		$propService = new PropertyService();
		$property = $propService->get($_GET['id']);
	?>

	<h1>Property Name : <?php echo $property['prop_name']?></h1>
	<a href="property_index.php">Show lists</a>
	
	<table>
		<tr>
			<td>Reference:</td>
			<td><?php echo $property['prop_reference']?></td>
		</tr>

		<tr>
			<td>Keyword:</td>
			<td><?php echo $property['prop_keyword']?></td>
		</tr>

		<tr>
			<td>Type:</td>
			<td><?php echo $property['prop_type']?></td>
		</tr>
	</table>
</body>
</html>