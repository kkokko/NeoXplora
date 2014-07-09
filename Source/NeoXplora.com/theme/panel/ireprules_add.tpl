<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <h3>IRep Rules - Add</h3>
	  <div id="ruleNameForm" class="formSection">
		<h4>Rule Name</h4>
		<input type="text" id="ruleNameInput">
		<input type="hidden" id="ruleId" value="-1">
		<button id="postRuleNameButton">Create</button>
	  </div>
	  
	  <div id="ruleConditionsForm" class="formSection">
		<h4>Rule Conditions</h4>
		<div class="controls">
			<input type="text" id="ruleStringInput">
			<button id="addConditionButton"> Add Condition </button>
		</div>
		<ul id="rulesList">
		
		</ul>
	  </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>