<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <h3>IRep Rules - Add</h3>
	  <form method="POST">
		<label>Rule Name</label>
		<input type="text"><br>
		<label>Conditions</label><br>
		<input type="text" id="conditionInput"><button id="addConditionButton">Add condition</button>
		<ul id="ConditionList">
			<li>cond 1</li>
			<li>cond 2</li>
			<li>cond 3</li>
			<li>cond 4</li>
			<li>cond 5</li>
		</ul>
		
		
	  </form>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>