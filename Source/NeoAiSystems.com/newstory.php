<?php
  /**
   * Features
   *
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$user->logged_in)
      redirect_to("login.php");
  
  $id = isset($_REQUEST['id'])?$_REQUEST['id'] : '';
  require_once "config_storydb.php"; 
  mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
  mysql_set_charset("utf8");
  mysql_select_db($configuration['db']);
    
  if(!empty($id)){   
    $result = mysql_fetch_array(mysql_query("SELECT `user` FROM `page` WHERE `pageID` = '" . $id . "'"));
    if(!count($result) || ($user->userlevel == 1 && $result['user'] != $user->username)) {
      redirect_to("story.php");
    }
  }
?>
<?php include(THEMEDIR."/header.php");?>
<link href="<?php echo SITEURL;?>/assets/highliter.css" rel="stylesheet" type="text/css"/>
<!-- Full Layout -->
<div class="container">
  <div class="row grid_24">
    <div id="page">
      <div class="box"> 
        <!-- Content Start -->
        <?php  ?>        
		<?php 
		if (empty($id)){
		?>
        <h2>Add New Story</h2>

        <?php } else 
		echo '<h2>Edit A Story</h2>';
		?>
        
        <div class="hr2"></div>

		<?php 
		if (empty($id)){
		?>
        <div class="msgOk done" ><strong>Story Sucessfuly Add! </strong><br /></div>
        <?php } else 
		echo '<div class="msgOk done" ><strong>Story Sucessfuly Edit! </strong><br /></div>';
		?>
        <!--div class="msgInfo"><strong>Styling individual plugins/modules</strong><br />
        If you want your plugins/modules to match your current theme, create a new theme folder inside each plugin/module directory and name it the same as your main theme. Inside place your style.css.</div>-->
        
        <div class="msgAlert error" ></div>

<!-- Web Form  -->


  <div id="page" class="row grid_24">
   
    <p class="info">Here you can make <?php echo empty($id) ? 'add a new ' : 'edit '; ?> story in the system.</p>
<?php 
  $title='';
  $body='';
  $categoryID='';
 
  if (!empty($id)){
    $qtmp1 = 'Select * from page WHERE pageID='.$_REQUEST['id'];
    $chk1 = mysql_query($qtmp1);
  
    if($rows= mysql_fetch_array($chk1)) {
      //echo "text,".round($rows['text25'],2).','.round($rows['text50'],2).','.round($rows['text100'],2)."\n";
      //echo "pos,".round($rows['text25'],2).','.round($rows['text50'],2).','.round($rows['text100'],2)."\n";
      //echo "hybrid,".round($rows['hybrid25'],2).','.round($rows['hybrid50'],2).','.round($rows['hybrid100'],2)."\n";
      $title=$rows['title'];
      $body=$rows['body'];
      $categoryID=$rows['categoryID'];
    }
  }   
?>
    <div class="box top10">
      <form action="#" method="post" id="story_form" name="story_form"><input type="hidden"  name="id"  value="<?php echo $id; ?>" />   
        <div id="msgholder"></div>
        <table class="display">
          <tr>
            <th>Story Title:</th>
            <td><div class="placeholder">
                <input name="storytitle" type="text"  class="inputbox" value="<?php echo $title;?>" size="45" />
              </div>
              </td>
          </tr>
          <tr>
            <th>Story Detail:</th>
            <td><div class="placeholder">
                <textarea name="storydetail" rows="5" class="inputbox"><?php echo $body;?></textarea>
              </div></td>
          </tr>
          <tr>
            <th>Category: </th>
            <td><div class="placeholder">
                <select id="storycat"  name="storycat">
                <?php 
                //load categories
                $queryCategories = 'select * from `category` order by `category` ';
                $sqlResult = mysql_query($queryCategories);
                while ($row = mysql_fetch_array($sqlResult)){
                  ?> 
                  <option value="<?php echo $row['categoryID']?>" <?php echo  ($categoryID==$row['categoryID'] )? 'selected="selected"':''; ?>> <?php echo $row['category']; ?> </option> <?php
                    $query_scats = mysql_query("SELECT * FROM `category` WHERE `parentId` = '" . $row['categoryID'] . "'");
                    while($row_scat = mysql_fetch_array($query_scats)) { ?>
                      <option value='<?php echo $row_scat['categoryID'] ?>' <?php
                      if($categoryID == $row['categoryID']) { echo " selected='selected'"; } ?>
                      >-- <?php echo $row_scat['category'] ?></option> <?php
                    }
                }  
                ?>                                               
                </select>
              </div></td>
          </tr>
          <tr>
            <td><?php if (empty($id)){ ?><input name="dosubmit" type="submit" value="Add Story"  class="button"/> <?php } else {?> <input name="dosubmit" type="submit" value="Update Story"  class="button"/><?php } ?></td>
        
          </tr>
        </table>
      </form>
    </div>
   </div>

<!-- End Web Form  -->
<style>
.element label {
	float:left;
	width:75px;
	font-weight:700
}
.text {
	float:left;
	width:270px;
	padding-left:20px;
}
.textarea {
	height:120px;
	width:270px;
	padding-left:20px;
}
.hightlight {
	border:2px solid #9F1319;
	background:url(assets/iconCaution.gif) no-repeat 2px;
	z-index:999;
	background-image:url(assets/iconCaution.gif) no-repeat 2px;
}
 #submit {
	float:right;
	margin-right:10px;
}
.loading {
	float:right;
	background:url(assets/ajax-loader.gif) no-repeat 1px;
	height:28px;
	width:28px;
	display:none;
}
.done {
display:none;
}
.error{
display:none;
}
</style>
<script type="text/javascript">
// <![CDATA[
var ajaxLoading = false;
$(document).ready(function (K) {

K('input[name=dosubmit]').click(function (event) {
//   event.preventDefault();
//   event.stopPropagation()   //this prevented it from submitting twice i a row			
//K(this).unbind();   
		//Get the data from all the fields
		var id = K('input[name=id]');		
		var title = K('input[name=storytitle]');
		var detail = K('textarea[name=storydetail]');
		var Cat1 =  K('select[name=storycat]');
		 var action  = $(this).attr("value");
	 		
		K(".msgAlert").html('');
		//Simple validation to make sure user entered something
		//If error found, add hightlight class to the text field
		if (title.val()=='') {
//			title.addClass('hightlight');
			K(".msgAlert").html('Title is missing <br>');
			K('.error').fadeIn('slow');			
			return false;
		} else title.removeClass('hightlight');
		
		if (detail.val()=='') {
			K(".msgAlert").html('Details  is missing <br>');		
			K('.error').fadeIn('slow');						
//			detail.addClass('hightlight');
			return false;
		} else detail.removeClass('hightlight');
		
		if (Cat1.val()=='NULL') {
//			Cat1.addClass('hightlight');
			K(".msgAlert").append('plz Select the category <br>');			
			K('.error').fadeIn('slow');						
			return false;
		} else Cat1.removeClass('hightlight');				
				
		var act='add';
		//organize the data properly
		var data = 'id='+id.val()+'&title=' + title.val() +'&detail=' + detail.val() + '&Cat1=' + Cat1.val() +'&action='+action;
//alert(data);
		//disabled all the text fields
		K('.text').attr('disabled','true');
		
		//show the loading sign
		K('.loading').show();
 if(!ajaxLoading) {
       ajaxLoading = true;		
		//start the ajax
		K.ajax({
			//this is the php file that processes the data and send mail
			url: "operations.php",	
			type: "POST",
			data: {id: id.val(), title: title.val(), detail: detail.val(),Cat1: Cat1.val(),action:action },	
			cache: false,
			success: function (html) {	
				//if process.php returned  array('storyId' => $storyID, 'success' => '1', 'message'=>'');
				var response = $.parseJSON(html);				
				if (response['success']== '1') {					
					K('.msgOk').fadeIn('slow');
      		K('input[name=storytitle]').val('');
      		K('textarea[name=storydetail]').val('');
      	  window.location.replace( "story.php?id="+response['storyId']+'#tabs-7');  
      		      							
				} else if(response['success'] == 0) {
  				K(".msgAlert").html(response['message'] + '<br>');
          K('.error').fadeIn('slow');
          ajaxLoading = false;
          return false;
        }
			}		
		});
	}	
		//cancel the submit button default behaviours
		return false;
	});	
	});		

// ]]>
</script> 
        
        <!-- Content Ends /--> 
      </div>
    </div>
  </div>
</div>
<?php include(THEMEDIR."/footer.php");?>
