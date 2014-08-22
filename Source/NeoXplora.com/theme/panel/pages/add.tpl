<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <div class="buttons">
        <a href="panel.php?type=pages">Manage Page</a>
        <a href="panel.php?type=pages&action=add" class="active">Add Page</a>
      </div>
        
      <form action="panel.php?type=pages&action=add" method="post">
        <table class="pagesForm">
          <tr>
            <td>Title:</td> 
            <td><input type="text" name="pageTitle" /></td>
          </tr>
          <tr>
            <td>Body:</td>
            <td><textarea name="pageBody"></textarea></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Add Page" style="width: 100px; float: right;" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>