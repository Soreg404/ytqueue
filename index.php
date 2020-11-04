<?php $BASE = true; require_once('include.php');

$type = 'home'; require_once 'head.php'; ?>

<body>
	
	<div id="select-content">
	
		<div id="select-container" class="hcenter-notransform">
			
			<div class="tile no-select" id="field-player">
				<div class="hvcenter">
					<div class="tile-desc">
						<span>UTWÓRZ</span>
					</div>
				</div>
			</div>
			
			<div class="tile no-select" id="field-client">
				<div class="hvcenter">
					<div class="tile-desc">
						<span>DOŁĄCZ</span>
					</div>
				</div>
			</div>
			
		</div>
		
		<div id="prompt-bg" style="display: none;"></div>
		
		<div class="prompt" id="create-prompt">
			
			<form action="action.php" method="post">
			
				<input type="submit" name="a" value="createPlayer" />
				
			</form>
			
		</div>
		
		<div class="prompt" id="connect-prompt">
			
			<form method="post" action="action.php">
			
				<input id="f-connect" class="upper-case" type="text" maxlength="10" name="tag" value="<?=err('reconnect', 1)?>" />
				<input type="submit" name="a" value="connectToPlayer" />
				
			</form>
			
		</div>
		
	</div>
	
</body>

</html>

<?php if(isset($_SESSION['error']['home'])) unset($_SESSION['error']['home']); ?>