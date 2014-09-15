<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <div>
        <a href="panel.php?action=stats"><img src="images/stats.jpg" alt="Stats" /></a>
        <a href="panel.php?type=ireprules"><img src="images/ireprules.jpg" alt="IRep Rules" /></a>
        <a href="panel.php?type=linkerrule"><img src="images/linkerrule.jpg" alt="Linker Rules" /></a>
        <a href="panel.php?type=pages"><img src="images/pages.jpg" alt="Manage Pages" /></a>
        <a href="panel.php?type=tests"><img src="images/tests.jpg" alt="Run Tests" /></a>
      </div>
      <br/><br/>
      <table>
        <tr>
          <td style="width:50%; padding-right: 33px; border-right: 1px solid #333;">
            <h2>Pages</h2>
            <hr><br/>
            <strong>Total: </strong> <?php echo $this->pageCounts['total']; ?><br/>
            <strong>Pending Training: </strong> <?php echo $this->pageCounts['pendingTraining']; ?><br/>
            <strong>Split</strong><br/>
            <ul style="margin-left: 15px;">
              <li>Trained: <?php echo $this->pageCounts['splitTrained']; ?></li>
              <li>Reviewed: <?php echo $this->pageCounts['splitReviewed']; ?><br/></li>
            </ul>
            <strong>Rep</strong>
            <ul><ul style="margin-left: 15px;">
              <li>Trained: <?php echo $this->pageCounts['repTrained']; ?></li>
              <li>Reviewed: <?php echo $this->pageCounts['repReviewed']; ?></li>
            </ul>
            <strong>CRep</strong> 
            <ul style="margin-left: 15px;">
              <li>Reviewed: <?php echo $this->pageCounts['crepReviewed']; ?></li>
            </ul>
          </td>
          <td style="padding-left: 30px;">
            <h2>Sentences</h2>
            <hr><br/>
            <strong>Total: </strong> <?php echo $this->sentenceCounts['total']; ?><br/>
            <strong>Pending Training: </strong> <?php echo $this->sentenceCounts['pendingTraining']; ?><br/>
            <strong>Split</strong><br/>
            <ul style="margin-left: 15px;">
              <li>Trained: <?php echo $this->sentenceCounts['splitTrained']; ?></li>
              <li>Reviewed: <?php echo $this->sentenceCounts['splitReviewed']; ?><br/></li>
            </ul>
            <strong>Rep</strong>
            <ul><ul style="margin-left: 15px;">
              <li>Trained: <?php echo $this->sentenceCounts['repTrained']; ?></li>
              <li>Reviewed: <?php echo $this->sentenceCounts['repReviewed']; ?></li>
            </ul>
            <strong>CRep</strong> 
            <ul style="margin-left: 15px;">
              <li>Reviewed: <?php echo $this->sentenceCounts['crepReviewed']; ?></li>
            </ul>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>
