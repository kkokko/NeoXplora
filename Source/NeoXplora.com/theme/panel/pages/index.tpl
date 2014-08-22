<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <div class="buttons">
        <a href="panel.php?type=pages" class="active">Manage Page</a>
        <a href="panel.php?type=pages&action=add">Add Page</a>
      </div>
      
      <?php if(count($this->rulesList) > 0) { ?>
        <br/>
        <h4>Pages:</h4>
        <br/>
        <ul class="page-list">
        <?php foreach($this->pageList as $APage) { ?>
          <li data-id="<?php echo $APage['Id']; ?>">
            <?php echo $APage['Title']; ?>
            <div style="float:right">
              <a href="panel.php?type=pages&action=edit&pageid=<?php echo $APage['Id']; ?>&page=<?php echo $this->currentPage; ?>" class="editPage">Edit</a> | 
              <a href="panel.php?type=pages&action=delete&pageid=<?php echo $APage['Id']; ?>&page=<?php echo $this->currentPage; ?>" class="deletePage">Delete</a>
            </div>
          </li>
        <?php } ?>
        </ul>
      <?php } else { ?>
        <p>No pages found.</p>
      <?php } ?>
      <div class="boxPagination">
      <?php echo $this->pagination; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>