<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <?php echo $this->fetch("menu", "panel"); ?>
      <br/>
      <br/>
  		<div class="buttons">
  			<a href="?type=ireprules" class="active">IRep Rules</a>
  			<a href="?type=ireprules&action=add">Add Rule</a>
  		</div>
  	  <div class="rulesContainer">
  		<?php
  		if(count($this->rulesList)>0){
  		?>
  			<ul id="rulesSortable">
  			<?php
  			foreach($this->rulesList as $IRepRule){
  				?>
  				<li data-id="<?php print $IRepRule['Id']; ?>" data-priority="<?php print $IRepRule['Order']; ?>">
  					<?php print $IRepRule['Name']; ?>
  					<div style="float:right">
              <a href="?type=ireprules&action=edit&ruleId=<?php print $IRepRule['Id']; ?>" ><img src="images/edit_icon.png" width="20"></a>
              <a href="javascript:void(0)" id="deleteRule"><img src="images/delete_icon.png" width="20"></a>
  					</div>
  				</li>
  				<?php
  			}
  			?>
  			</ul>
  		<?php
  		}else {
  	  
  		?>
  		<p>No rules found.</p>
  		<?php
  		}
  		?>
  	  </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>