  <input type="hidden" class="pageId" value="<?php $this->pageId ?>" />
  <table class="trainer">
    <tr class="table-header">
      <th>Sentence</th>
      <th>Rep</th>
    </tr>
    <?php foreach($this->sentences AS $sentence) { ?>
      <?php $this->sentence = $sentence; ?>
      <?php echo $this->fetch("row", "train/linker"); ?>
    <?php } ?>
  </table>
