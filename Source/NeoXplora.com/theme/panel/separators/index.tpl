<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <?php echo $this->fetch("menu", "panel"); ?>
      <br/>
      
      <form action="panel.php?type=separators&action=add" method="post">
        <table class="pagesForm">
          <tr>
            <td style="vertical-align: middle;">Separator:</td> 
            <td style="vertical-align: middle;"><input type="text" name="separator" autofocus /></td>
            <td style="vertical-align: middle;"><input type="submit" name="submit" value="Add" style="width: 100px;" /></td>
          </tr>
        </table>
      </form>
      <br/>
      <h4>Separators:</h4>
      <br/>
      <?php if(count($this->separatorList)) { ?>
        <ul class="page-list">
        <?php foreach($this->separatorList as $ASeparator) { ?>
          <li data-id="<?php echo $ASeparator['Id']; ?>" style="border-bottom: 1px solid #ccc">
            <?php echo $ASeparator['Value']; ?>
            <div style="float:right">
              <?php /*<a href="panel.php?type=separators&action=edit&id=<?php echo $ASeparator['Id']; ?>&page=<?php echo $this->currentPage; ?>" class="editS">Edit</a> | */ ?> 
              <a href="panel.php?type=separators&action=delete&id=<?php echo $ASeparator['Id']; ?>&page=<?php echo $this->currentPage; ?>" onclick="return confirm('Are you sure you want to delete this separator ?')" class="deletePage">Delete</a>
            </div>
          </li>
        <?php } ?>
        </ul>
      <?php } else { ?>
        <p>No separators found.</p>
      <?php } ?>
      <div class="boxPagination">
      <?php echo $this->pagination; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>