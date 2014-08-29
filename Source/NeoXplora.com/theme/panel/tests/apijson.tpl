<?php echo $this->fetch("header"); ?>
<script type="text/javascript">
  $(document).ready(function() {
    $(".req1").click(function() {
      $("textarea[name='req']").val('{"ClassName":"TResponseGetLinkerDataForPageId","Properties":{"Entities":{"Values":[{"Id":"0","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Eric","Version":""}}]}},{"ClassName":"TAttributeRecord","Properties":{"Key":"Pronoun","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"I","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"my","Version":""}}]}}],"Type":"etPerson"}}},{"Id":"1","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Members","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"2","Name":"","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"3","Name":"","Version":""}}]}},{"ClassName":"TAttributeRecord","Properties":{"Key":"ref","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"sisters","Version":""}},{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"their","Version":""}}]}}],"Type":"etGroup"}}},{"Id":"2","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Rachel","Version":""}}]}}],"Type":"etPerson"}}},{"Id":"3","Object":{"ClassName":"TEntityRecord","Properties":{"Attributes":[{"ClassName":"TAttributeRecord","Properties":{"Key":"Name","Values":[{"ClassName":"TEntityWithName","Properties":{"Id":"0","Name":"Carrie","Version":""}}]}}],"Type":"etPerson"}}}],"ClassName":"TSkyIdList"},"Sentences":{"Values":[{"Id":"2124","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"Hi"},{"Key":","},{"Key":" "},{"Key":"my","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"name"},{"Key":" "},{"Key":"is"},{"Key":" "},{"Key":"Eric","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}}]}},{"Id":"2125","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"am"},{"Key":" "},{"Key":"12"}]}},{"Id":"2126","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"want"},{"Key":" "},{"Key":"to"},{"Key":" "},{"Key":"talk"},{"Key":" "},{"Key":"about"},{"Key":" "},{"Key":"my","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"family"}]}},{"Id":"2127","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"I","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"0"}}},{"Key":" "},{"Key":"have"},{"Key":" "},{"Key":"two"},{"Key":" "},{"Key":"sisters","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"1"}}}]}},{"Id":"2128","Object":{"ClassName":"TSkyStringList","Values":[{"Key":"Their","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"1"}}},{"Key":" "},{"Key":"names"},{"Key":" "},{"Key":"are"},{"Key":" "},{"Key":"Rachel","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"2"}}},{"Key":" "},{"Key":"and"},{"Key":" "},{"Key":"Carrie","Object":{"ClassName":"TEntityWithId","Properties":{"Id":"3"}}}]}}],"ClassName":"TSkyIdList"}}}');
    });
  }); 
</script>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <a href="panel.php?type=tests">Run other tests</a> <br/><br/>
      
      <div style="width: 600px; float:left;">
        Request: <br/>
        <form action='panel.php?type=tests&action=apijson' method='post' />
          <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 300px; margin-top: 10px;' name='req'><?php echo $this->requestjson; ?></textarea><br/><br/>
          <input type='submit' value='Run' name='submit' />
        </form>
        <?php if($this->responsejson) { ?>
        <br/><br/>Response:<br/>
        <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 300px;' name='resp'><?php echo $this->responsejson; ?></textarea><br/><br/>
        <?php } ?>
      </div>
      <div style="width: 280px; margin-left: 20px; float:left;">
        Load Request: <br/>
        <a href="javascript:void(0)" class="req1">Get POS</a>
        
      </div>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>