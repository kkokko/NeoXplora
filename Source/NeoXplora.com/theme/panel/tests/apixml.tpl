<?php echo $this->fetch("header"); ?>
<script type="text/javascript">
  $(document).ready(function() {
    $(".req1").click(function() {
      $("textarea[name='req']").val("<RequestGetPosForSentences><Sentences><EntityWithName><Id>0</Id><Name>At first he was running</Name></EntityWithName><EntityWithName><Id>1</Id><Name>The running hurt him</Name></EntityWithName><EntityWithName><Id>2</Id><Name>His legs hurt</Name></EntityWithName></Sentences></RequestGetPosForSentences>");
    });
  });
</script>
<div id="content">
  <div class="container relative">
    <div class="panel">
      
      <div id="tabs">
        <ul>
          <li><a href="panel.php?type=tests&action=apixml_formatted">Formatted</a></li>
          <li><a href="panel.php?type=tests&action=apixml_xml">XML</a></li>
        </ul>
      </div>
      
      <?php /*
      <a href="panel.php?type=tests">Run other tests</a> <br/><br/>
      
      <div style="width: 600px; float:left;">
        Request: <br/>
        <form action='panel.php?type=tests&action=apixml' method='post' />
          <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 300px; margin-top: 10px;' name='req'><?php echo $this->requestxml; ?></textarea><br/><br/>
          <input type='submit' value='Run' name='submit' />
        </form>
        <?php if($this->responsexml) { ?>
        <br/><br/>Response:<br/>
        <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 300px;' name='resp'><?php echo $this->responsexml; ?></textarea><br/><br/>
        <?php } ?>
      </div>
      <div style="width: 280px; margin-left: 20px; float:left;">
        Load Request: <br/>
        <a href="javascript:void(0)" class="req1">Get POS</a>
      </div>*/ ?>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>