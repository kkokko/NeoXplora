<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
		<div class="buttons">
			<a href="?action=ireprules" class="active">IRep Rules</a>
			<a href="?action=ireprules_add">Add Rule</a>
		</div>
      <h3>IRep Rules </h3>
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
					<a href="?action=ireprules_edit&ruleId=<?php print $IRepRule['Id']; ?>" ><img src="images/edit_icon.png" width="20"></a>
					<a href="#" ><img src="images/delete_icon.png" width="20"></a>
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