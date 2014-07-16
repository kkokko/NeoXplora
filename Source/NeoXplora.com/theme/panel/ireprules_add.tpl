<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
	  <div class="buttons">
		<a href="?action=ireprules">IRep Rules</a>
	    <a href="javascript:void(0)" id="pageHeadTitle" class="active">Add IRep Rule</a>
		<a href="javascript:void(0)">Save All</a>
	  </div>
      
	  <div id="ruleNameForm" class="formSection">
		<h4>Rule Name</h4>
		<div class="controls">
			<input type="text" id="ruleNameInput" value="<?php print $this->ruleName; ?>">
			<input type="hidden" id="ruleId" value="<?php print $this->ruleId; ?>">
			<button id="postRuleNameButton" class="button">Create</button>
		</div>
	  </div>
	  
	  <div id="ruleConditionsForm" class="formSection">
		<h4>Rule Conditions</h4>
		<div class="controls" style="display:none;">
			<input type="text" id="conditionStringInput">
			<button id="addConditionButton" class="button"> Add Condition </button>
			<button id="saveConditionsButton" class="button"> Save </button>
		</div>
		<div id="conditionsList">
			
		</div>
	  </div>
	  <div id="ruleValuesForm" class="formSection">
		<h4>Rule Values</h4>
		<div class="controls" style="display:none;">
			<input type="text" id="valueStringInput">
			<button id="addValueButton" class="button"> Add Value </button>
			<button id="saveValuesButton" class="button"> Save </button>
		</div>
		<div id="valuesList">
			
		</div>
	  </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>