<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8" name="description" 
	content="<?php echo _config('assets')['meta']['home']?>">
	<title></title>
</head>
<body>
	<h1>Sample Form Page</h1>
	<?php $codes = ['A','B','C','D']?>
	<form>
		<table>
			<tr>
				<td>Property Name</td>
				<td><input type="text" name="property_name"></td>
			</tr>

			<tr>
				<td>Property Code</td>
				<td>
					<select name="property_code">
						<?php foreach($codes as $key => $row) :?>
							<option value="<?php echo $row?>"><?php echo $row?></option>
						<?php endforeach?>
					</select>
				</td>
			</tr>

			<tr>
				<td>Description</td>
				<td>
					<textarea name="description"></textarea>
				</td>
			</tr>
			<td>
				<td><input type="submit" name=""></td>
			</td>
		</table>
	</form>
</body>
</html>