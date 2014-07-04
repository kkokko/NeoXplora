<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <h3>IRep Rules - Add</h3>
	  <form method="POST">
		<label>Rule Name</label>
		<input type="text"><br>
		<label>Conditions</label><br>
		
		<div id="conditionContainer">
			None
		</div>
		
	  </form>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>