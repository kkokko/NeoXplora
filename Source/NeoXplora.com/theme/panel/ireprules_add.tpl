<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <h3>IRep Rules - Add</h3>
	  <div id="ruleNameForm" class="formSection">
		<h4>Rule Name</h4>
		<input type="text" id="ruleNameInput" value="<?php print $this->ruleName; ?>">
		<input type="hidden" id="ruleId" value="<?php print $this->ruleId; ?>">
		<button id="postRuleNameButton">Create</button>
	  </div>
	  
	  <div id="ruleConditionsForm" class="formSection">
		<h4>Rule Conditions</h4>
		<div class="controls" style="display:none;">
			<input type="text" id="conditionStringInput">
			<button id="addConditionButton"> Add Condition </button>
			<button id="saveConditionsButton"> Save </button>
		</div>
		<div id="conditionsList">
			<p>None</p>
		</div>
	  </div>
	  <div id="ruleValuesForm" class="formSection">
		<h4>Rule Values</h4>
		<div class="controls" style="display:none;">
			<input type="text" id="valueStringInput">
			<button id="addValueButton"> Add Value </button>
			<button id="saveValuesButton"> Save </button>
		</div>
		<div id="valuesList">
			<p>None</p>
		</div>
	  </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>