<?php $BASE = true; require_once('include.php');

$type = 'home'; require_once 'head.php'; ?>

<body>
	
	<div id="select-container">
		
		<div id="field-player">
			<div>
				<span>PLAYER</span>
				<div id="create-player">
					<form action="action.php" method="post">
						<input type="submit" name="a" value="createPlayer" />
					</form>
				</div>
			</div>
		</div>
		
		<div id="field-client">
			<div>
				<span>CLIENT</span>
				<form method="post" action="action.php">
				
					<input id="f-connect" type="text" maxlength="10" name="tag" value="<?=err('reconnect')?>" />
					<input type="submit" name="a" value="connectToPlayer" />
					
				</form>
			</div>
		</div>
		
	</div>

</body>

</html>

<?php if(isset($_SESSION['error']['home'])) unset($_SESSION['error']['home']); ?>