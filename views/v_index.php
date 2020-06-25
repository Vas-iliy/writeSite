
<?foreach ($state as $st):?>
	<div class="card mt-2" style="width: 30rem;">
		<h5 class="card-header"><?=$st['state_title']?></h5>
		<div class="card-body">
			<h5 class="card-title"><?=$st['cat_title']?></h5>
			<p class="card-text"><?=$st['login']?></p>
			<div class="card-footer text-right">
				<a  href="article.php?stateId=<?=$st['id_state']?>" class="btn btn-primary">Узнать больше</a>
			</div>

		</div>
	</div>
<?endforeach;?>