<?php
	
	define("_VALID_PHP", true);
	require_once("init.php");
	require_once 'db_config.php';  
    
	if (!$user->logged_in)
		redirect_to("login.php");
	  
	$storyId = isset($_GET['id']) ?$_REQUEST['id'] : '';
	if(!empty($id)){          
    $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $id . "'"));
    if(!count($result) || ($user->userlevel == 1 && $result['user'] != $user->username)) {
      redirect_to("story.php");
    }
  }
?>
<?php
  include (THEMEDIR . "/header.php");
?>

<script type="text/javascript">
  history.navigationMode = 'compatible';
    
  jQuery(document).ready(function($) {
    $("#ajaxLoading").val('false');  
    console.log('document ready');
    loadStorySentences();
    $("#tabs").tabs();
    
    $('#tabs-7 .splitSentence').click(function() {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
      resizable : false,
      height : 140,
      title : "Are you sure you want to split the sentences ?",
      modal : true,
      buttons : {
        "Yes" : function() {            
          $sentences = [];       
          $('.short_sentnc').each(function(index){            
            var sentenceId = $(this).parent('tr').attr('id');  
            sentenceId = parseInt(sentenceId.replace('tr', ''));                      
            var parentValue = $(this).parent('tr').find('.proto_sentnc').find('[id^="edit"]').text();                   
            var currentValue = $(this).find('[id^="edit"]').text();            
            $sentences.push({
                "sid": sentenceId, 
                "sentence": currentValue,
                "parentSentece": parentValue 
            });
          });
          $.ajax({
            type : "POST",
            url : "operations.php",
            data : {
              storyId: storyid,
              action: 'splitSentence',
              sentences: $sentences
            },
            success : function(result) {
              loadStorySentences();
            }
          }); 
          $(this).dialog("close");
        },
        Cancel : function() { 
          $(this).dialog("close");
        }
      } } );
    });    
        
        
        
  });
    
    function loadStorySentences() {
      var ajaxLoading = $("#ajaxLoading").val();      
      if (ajaxLoading == 'false') {
        $.ajax({
          type : "POST",
          url : "operations.php",
          data : {
              sentenceId: $('#story-id').val(),
              action: 'loadSentences' 
          },
          async : false,
          success : function(data) {
            event.preventDefault();
            // response: array('msg' => $msg, 'table' => $htmltext, 'total' => $countr, 'storyId' => $storyID)                       
            var response = $.parseJSON(data);              
            $("#sentences-row").html(response.table);             
            $('#total-data').val(response.total);
            $('#story-id').val(response.storyId);     
            $("#container").html(response.msg);         
          }
        });
      }
    }
    
    $(document).on('click', '.td-crep', (function(e0) {
      e0.preventDefault();
        var thisObj = $(this);
        var olddata = thisObj.html();       
        olddata = escapeHtml(olddata);
        var newDom = '<input type="text" value="' + olddata + '"  class="new-value" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';        
        thisObj.parent('td').html(newDom);
    }));
       
    $(document).on('focusout', '.new-value', (function(e1) {
      e1.preventDefault();
      var thisObj = $(this);
      var storyid = $("#story-id").val();
      var newData = thisObj.val();
      var santanceId = thisObj.parent('td').parent('tr').attr('id');
      santanceId = parseInt(santanceId.replace('tr', '')); 
      //<span class="td-crep" sid="783" id="edit783">He has left his big top on the box</span>
      var newDom1 = '<span class="td-crep" id="edit'+santanceId+'" >' + newData + '</span>';
      var parent = thisObj.parent('td');
      parent.html(newDom1);  
    }));
        
    function escapeHtml(text) {
      return text
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
    }

</script>

<style type="text/css">
  input.sentencetxt, input.representationtxt {

    border: 1px solid #EEEEEE;
    display: inline-table;
    padding: 2px;
    width: 95%;
  }
  
  #rep-data td {
    font-size: 13px;
    padding:5px;
  }

</style>

<div id="sql"></div>
<div class="header-wrapper">
  <div class="header" style="position:relative; margin-top:60px;">
    <div class="logoo">
      STORY <span class="td-edit1"></span>      
    </div>
    <div class="header_right">
    </div>
    <div class="store" >            
      <div id="container" >
        <div class="data"></div>
        <div class="pagination"></div>
      </div>
    </div>
  </div>
  <div class="chat" id="tabs">         
    <ul>
      <?php
      if (($user -> logged_in)) {
        echo '<li><a href="#tabs-7">STORY</a></li>';
      }
      ?>
   </ul>
    
     <div id ="tabs-7" class="nas">
     <input type="hidden" value="<?php echo $_GET['id']?>" id="story-id" />  
     <input type="hidden" id="total-data" />  
     <input type="hidden" value="false" id="ajaxLoading"/> 
     <div id="loading"></div>          
      <div class="chat-wi">
        <div class="chat-space nano nscroller">
          <div id="sentences-row"></div>
        </div>
      </div>
    
      <div style="color:#3399FF;cursor: pointer; right:150px; top:58px; position:absolute; " class="splitSentence">
        SPLIT
      </div>
      
    </div>
  </div>
  <div id="confirm">
  </div>
</div> 
<?php include(THEMEDIR."/footer.php");?>