<?php echo $this->fetch("header"); ?>
<div id ="search_box" class="search_box">
	<div id='logo_box_wrp'>
		<div class="logo"><img src="images/NeoXploraLOGO.png" alt="" border="0" /></div>
	</div>
	<div class="search_bar row">
		<input id="searchInput" type="text" value="Coming July 2014" ></input>
		<input id="submitSearch" type="submit" value="" />
	</div>
</div>
<div id="searchResults">
	<?php if($this->preRequest) print $this->fetch('results', 'search'); ?>
</div>
<?php echo $this->fetch("footer"); ?>
