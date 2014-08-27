<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <a href="panel.php?action=stats"><img src="images/stats.jpg" alt="Stats" /></a>
      <a href="panel.php?type=ireprules"><img src="images/ireprules.jpg" alt="IRep Rules" /></a>
      <a href="panel.php?type=linkerrule"><img src="images/linkerrule.jpg" alt="Linker Rules" /></a>
      <a href="panel.php?type=pages"><img src="images/pages.jpg" alt="Manage Pages" /></a>
      <a href="panel.php?type=tests"><img src="images/tests.jpg" alt="Run Tests" /></a>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>