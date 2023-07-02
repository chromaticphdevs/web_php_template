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
	?>
	<a href="property_create.php">Create</a>
	<table>
		<thead>
			<th>Reference</th>
			<th>Name</th>
			<th>Keyword</th>
			<th>Type</th>
			<th>Action</th>
		</thead>

		<tbody>
			<?php foreach($service->getAll() as $key => $row) :?>
				<tr>
					<td><?php echo $row['prop_reference']?></td>
					<td><?php echo $row['prop_name']?></td>
					<td><?php echo $row['prop_keyword']?></td>
					<td><?php echo $row['prop_type']?></td>
					<td>
						<a href="property_details.php?id=<?php echo $row['id']?>&<?php echo SLUG_KEYWORD?>=<?php echo $row['prop_keyword']?>">
							Show Property
						</a>
					</td>
				</tr>
			<?php endforeach?>
		</tbody>
	</table>
</body>
</html>