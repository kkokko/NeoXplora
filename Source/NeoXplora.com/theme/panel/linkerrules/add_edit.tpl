<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <div class="buttons">
      <a href="?type=linkerrule">Linker Rules</a>
        <a href="javascript:void(0)" id="pageHeadTitle" class="active">Add Rule</a>
      </div>
        
      <div id="ruleNameForm" class="formSection">
        <h4>Rule Name</h4>
        <div class="controls">
          <input type="text" id="ruleNameInput" value="<?php echo $this->ruleName; ?>">
          <select id="ruleTypeInput">
            <option value="rtNegate"<?php if($this->ruleType == "rtNegate") echo ' selected="selected"'; ?>>Negate</option>
            <option value="rtScoring"<?php if($this->ruleType == "rtScoring") echo ' selected="selected"'; ?>>Scoring</option>
          </select>
          <input type="text" id="ruleScore" <?php if($this->ruleType == "rtNegate") echo 'style="display: none;"'; ?>placeholder="Score" value="<?php echo $this->ruleScore; ?>">
          <input type="hidden" id="ruleId" value="<?php echo $this->ruleId; ?>">
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
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>