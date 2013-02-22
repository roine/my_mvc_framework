check

<table>
	<thead>
		<th>name</th>
		<th>email</th>
	</thead>
	<tbody>
		<?php  foreach($users as $user): ?>
		<tr data-id="<?php echo $user['id'];?>">
			<td><a href="view/users/<?php echo $user['username'];?>"><?php echo $user['username'];?></a></td>
			<td><?php echo $user['email'];?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>