<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <b>Tests:</b><br/><br/>
      <a href="panel.php?type=tests&action=apixml">API XML</a><br/>
      <a href="panel.php?type=tests&action=apijson">API JSON</a><br/>
      <a href="panel.php?type=tests&action=postagger">Pos Tagger</a><br/>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>