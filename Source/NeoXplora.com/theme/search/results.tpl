<div class="searchResultContainer">
	<p <?php print (isset($this->errorMessage) && $this->errorMessage)?' style="color:red;':'';?> > <?php print $this->message; ?> </p>
	<?php
		if(is_array($this->searchResults) && count($this->searchResults)>0){
			foreach($this->searchResults as $searchResult){
				$this->searchResultItem = $searchResult;
				print $this->fetch('result_item', 'search');
			}
		}
		
		if(is_array($this->pageLinks) && count($this->pageLinks)>1){
			print $this->fetch('pagination', 'search');
		}
	?>
	
</div>
