<?php echo $this->fetch("header"); ?>
<style>
.green-left {
    /*background: url("images/message_green.gif") top left no-repeat;*/
    color: #6da827;
    font-family: Tahoma;
    font-weight: bold;
    line-height: 22px;
    padding: 5px 0 5px 20px;
    margin: 0 0 10px 0;
    /*border-radius: 10px;
    border: 1px solid #B7EA79;
    border-collapse: collapse;*/
}
</style>
<div id="content">
	  <div style="padding-top:10px;" >
	  			
			<?php
      if(count($this->rulesList)>0){ 
      ?>
      <table width="100%" cellspacing="0" style="font-size:14px;" >
      <tr>
        <td width="10%"></td>
        <td align="center">
        
        <a class="revertReviewSplitButton button" href="panel.php?type=linkerrule&action=addEditLinkerRule&perform=add" style="margin-bottom:10px;">Add New</a>
        
        <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != '') { ?>
          <div class="green-left"><?php echo $_SESSION['msg']; ?></div>
        <?php 
            unset($_SESSION['msg']);
            } 
        ?>
        
    			<table id="example" class="display" cellspacing="0" width="100%" >
    			<thead>
    			 <tr>
    			   <th>Name</th>
    			   <th>Type</th>
    			   <th>Value</th>
    			   <th width="10%">Action</th>
    			   
    			  </tr>
    			 </thead>
    			<tbody>
    			<?php 
    			if(is_array($this->rulesList))
    			{
            foreach($this->rulesList as $LinkerRule){ 
          ?>
    			<tr> 
    			 <td><?php echo $LinkerRule[$this->linkerRulesDB['name']] ?></td>
    			 <td><?php echo $LinkerRule[$this->linkerRulesDB['type']] ?></td>
    			 <td><?php echo $LinkerRule[$this->linkerRulesDB['value']] ?></td>
    			 <td align="center">
    			     <a href="panel.php?type=linkerrule&action=addEditLinkerRule&linkerRuleId=<?php echo $LinkerRule[$this->linkerRulesDB['id']]; ?>" ><img src="images/edit_icon.png" width="16"></a>
               <a href="javascript:void(0);" onClick="if(confirm('Are you sure you want to delete linker rule <?php echo $LinkerRule[$this->linkerRulesDB['name']]; ?>'))  delete_linkerrule(<?php echo $LinkerRule[$this->linkerRulesDB['id']]; ?>);"><img src="images/delete_icon.png" width="16"></a>
           </td>
    			 
    			</tr>
    			<?php
            }
            }
            ?>
    			</tbody>
    			</table>
  			</td>
  			<td width="10%"></td>
  			</tr>
  			</table>
		<?php
		}else {
	  
		?>
		<p>No rules found.</p>
		<?php
		}
		?>
	  </div>
</div>
<Script language="javascript">
$(document).ready(function() { $('#example').dataTable(); } );
</script>
<?php echo $this->fetch("footer"); ?>