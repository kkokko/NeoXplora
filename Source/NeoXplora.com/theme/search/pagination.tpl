<div class="pagination">
	<p>Page : 
<?php
	foreach($this->pageLinks as $pageLink){
?>
		<a href="?q=<?php print $this->q; ?>&page=<?php print $pageLink['data-page']; ?>" data-page="<?php print $pageLink['data-page']; ?>" class="<?php if($pageLink['isCurrent']) print "currentPage"; ?>"><?php print $pageLink['label']; ?></a>
<?php
	}
?>
	</p>
</div>
