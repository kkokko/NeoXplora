<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <div class="buttons">
        <a href="panel.php?type=pages" class="active">Manage Page</a>
        <a href="panel.php?type=pages&action=add">Add Page</a>
      </div>
      
      <br />
      Category: 
      <select id="categoryId">
        <option value="-1">
          All
        </option>
        <option value="0" <?php if($this->currentCategory == 0) echo "selected='selected'"; ?>>
          Uncategorized
        </option>
        <?php foreach($this->categoryList AS $key => $value) { ?>
          <option value="<?php echo $key; ?>" <?php if($this->currentCategory == $key) echo "selected='selected'"; ?>>
            <?php echo $value; ?>
          </option>
        <?php } ?>
      </select>
      Status: 
      <select id="status">
        <option value="-1">Any</option>
        <option value="0" <?php if($this->currentStatus == 0) echo "selected='selected'"; ?>>Split not done</option>
        <option value="1" <?php if($this->currentStatus == 1) echo "selected='selected'"; ?>>Interpreting not done</option>
        <option value="2" <?php if($this->currentStatus == 2) echo "selected='selected'"; ?>>Linking not done</option>
        <option value="3" <?php if($this->currentStatus == 3) echo "selected='selected'"; ?>>Training completed</option>
      </select>
      <br/>
      <br/>
      <h4>Pages:</h4>
      <br/>
      <?php if(count($this->pageList)) { ?>
        <ul class="page-list">
        <?php foreach($this->pageList as $APage) { ?>
          <li data-id="<?php echo $APage['Id']; ?>">
            <?php echo $APage['Title']; ?>
            <div style="float:right">
              <?php
                switch($this->currentStatus) {
                  case 0:
                ?>
                  <a href="review.php?type=splitter&pageId=<?php echo $APage['Id']; ?>" target="_blank" class="viewPage">Fix Split</a> |
                <?php
                    break;
                  case 1:
                ?>
                  <a href="review.php?type=interpreter&pageId=<?php echo $APage['Id']; ?>" target="_blank" class="viewPage">Fix Rep</a> |
                <?php
                    break;
                  case 2:
                ?>
                  <a href="train.php?type=linker&pageId=<?php echo $APage['Id']; ?>" target="_blank" class="viewPage">Fix CRep</a> |
                <?php
                    break;
                }
              ?>
              <a href="panel.php?type=pages&action=edit&pageid=<?php echo $APage['Id']; ?>&page=<?php echo $this->currentPage; ?>" class="editPage">Edit</a> | 
              <a href="panel.php?type=pages&action=delete&pageid=<?php echo $APage['Id']; ?>&page=<?php echo $this->currentPage; ?>" onclick="return confirm('Are you sure you want to delete this page and all of its data ?')" class="deletePage">Delete</a>
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