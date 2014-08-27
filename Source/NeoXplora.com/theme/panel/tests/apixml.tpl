<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <a href="panel.php?type=tests">Run other tests</a> <br/><br/>
      Request:<br/>
      <form action='panel.php?type=tests&action=apixml' method='post' />
      <textarea style='width: 800px; height: 300px;' name='req'><?php echo $this->requestxml; ?></textarea><br/><br/>
      <input type='submit' value='Submit' name='submit' />
      </form>
      <?php if($this->responsexml) { ?>
      <br/><br/>Response:<br/>
      <textarea style='width: 800px; height: 300px;' name='resp'><?php echo $this->responsexml; ?></textarea><br/><br/>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>