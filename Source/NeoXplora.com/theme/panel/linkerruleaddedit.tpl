<?php echo $this->fetch("header"); ?>
<Script language="javascript">

  $(document).ready(function()
  {
    $("#frmLinkerRule").validate(
    {
     rules: {     
           linkerRuleValue: { digits:true, required: true } 
        },
        tooltip_options: {
           linkerRuleName: { placement: 'right' }, 
           linkerRuleValue: { placement: 'right' }
        }
    }
    );
  });
  
</script>

<div id="content">
<div class="tab-content">
  <div id="bootstrap-style-demo" class="tab-pane fade active in">
    <form id="frmLinkerRule" name="frmLinkerRule" method="post" action="panel.php?type=linkerrule&action=saveLinkerRule">
    <input type="hidden" name="linkerRuleId" id="linkerRuleId" value="<?php echo $this->rulesList['id']; ?>">
    <div style="margin-top:20px;"></div>
    <table width="100%" cellspacing="5" cellpadding="5" border="0">
    <tr>
      <td width="10%" valign="top">
        <span class="linkerRuleLabel"><strong>Name :</strong></span>
      </td>
      <td>
        <input type="text" name="linkerRuleName" id="linkerRuleName" value="<?php echo $this->rulesList['name']; ?>" class="form-control" style="width:30%;" required>
      </td>
     </tr>
     
     <tr>
      <td valign="top">
        <span class="linkerRuleLabel"><strong>Type :</strong></span>
      </td>
      <td>
        <input type="radio" name="linkerRuleType" id="linkerRuleType" value="rtNegate" class="required" <?php echo $this->rulesList['type1']; ?> >&nbsp;rtNegate&nbsp;
        <input type="radio" name="linkerRuleType" id="linkerRuleType" value="rtScoring" class="required" <?php echo $this->rulesList['type2']; ?> >&nbsp;rtScoring&nbsp;
      </td>
     </tr>
     
     <tr>
      <td valign="top">
        <span class="linkerRuleLabel"><strong>Value :</strong></span>
      </td>
      <td>
        <input type="text" name="linkerRuleValue" id="linkerRuleValue" value="<?php echo $this->rulesList['value']; ?>" class="form-control" style="width:10%;" required>
      </td>
     </tr>
     
     <tr>
      <td valign="top">
        <span class="linkerRuleLabel"><strong>Conditions :</strong></span>
      </td>
      <td>
      <textarea name="linkerRuleConditions" id="linkerRuleConditions" class="form-control" style="width:30%;"><?php echo $this->rulesList['conditions']; ?></textarea>
      </td>
     </tr>
     
     <tr>
        <td colspan="2">&nbsp; </td>
     </tr>
     <tr>
        <td colspan="2" >
          
          <input type="submit" id="submit" value="Save"  class="btn btn-lg btn-primary btn-block" style="width:100px;" />
          <input type="button" id="button" value="Cancel"  class="btn btn-lg btn-primary btn-block" style="width:100px;" onClick="document.location.href='panel.php?type=linkerrule'" />
        </td>
    </tr>
    </table>
    </p>
    </form>
   </div>
   </div>
</div>
<?php echo $this->fetch("footer"); ?>