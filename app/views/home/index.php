<h1>Welcome Home Bro</h1>
<?php 
foreach($all as $user): ?>
<div>
	<?php echo $user['username']; ?>
</div>
<?php endforeach; ?>
<?php print_r($first); ?>

