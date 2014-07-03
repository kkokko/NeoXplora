<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <h3>IRep Rules <a href="?action=ireprules_add"><button>Add Rule</button></a></h3>
	  <div class="rulesContainer">
		<?php
		if(count($this->rulesList)>0){
		?>
			<ul id="rulesSortable">
			<?php
			foreach($this->rulesList as $IRepRule){
				?>
				<li data-id="<?php print $IRepRule['id']; ?>" data-priority="<?php print $IRepRule['priority']; ?>">
					<?php print $IRepRule['name']; ?>
					<div style="float:right">
					<a href="#" ><img src="images/edit_icon.png" width="20"></a>
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