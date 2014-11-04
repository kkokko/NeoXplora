<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <?php echo $this->fetch("menu", "panel"); ?>
      <br/>
      <br/>
      <div class="buttons">
        <a href="panel.php?type=linkerrule" class="active">Linker Rules</a>
        <a href="panel.php?type=linkerrule&action=add">Add Rule</a>
      </div>
      <div class="rulesContainer">
      <?php if(count($this->rulesList) > 0) { ?>
        <ul id="rulesSortable">
        <?php foreach($this->rulesList as $LinkerRule) { ?>
          <li data-id="<?php echo $LinkerRule['id']; ?>" data-priority="<?php echo $LinkerRule['order']; ?>">
            <?php echo $LinkerRule['name']; ?> 
            <?php
              switch($LinkerRule['type']) {
                case "rtNegate":
                  echo "(Negate)";
                  break;
                case "rtScoring":
                  echo "(Scoring: " . $LinkerRule['score'] . ")";
                  break;
              }
            ?>
            <div style="float:right">
              <a href="?type=linkerrule&action=edit&ruleId=<?php echo $LinkerRule['id']; ?>" ><img src="images/edit_icon.png" width="20"></a>
              <a href="javascript:void(0)" id="deleteRule" ><img src="images/delete_icon.png" width="20"></a>
            </div>
          </li>
        <?php } ?>
        </ul>
      <?php } else { ?>
        <p>No rules found.</p>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>