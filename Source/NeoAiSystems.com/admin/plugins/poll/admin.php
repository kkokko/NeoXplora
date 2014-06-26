<?php
  /**
   * jQuery Poll
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("poll")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $poll = new poll();
  $pollrow = $poll->getPolls();
?>
<?php switch($core->paction): case "edit": ?>
<?php $row = $core->getRowById("plug_poll_questions", $poll->pollid);?>
<?php $pollopt = $poll->getPollOptions();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_PL_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_PL_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_PL_SUBTITLE1 . $row['question'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_PL_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=poll" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_PL_QUESTION;?>: <?php echo required();?></th>
            <td><input name="question<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['question'.$core->dblang];?>" size="55" title="<?php echo PLG_PL_QUESTION_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_PL_OPTIONS;?>:</th>
            <td valign="top"><div id="sort-options">
                <?php foreach ($pollopt as $k => $v): ?>
                <?php $k++;?>
                <div style="margin-bottom:4px;" id="input_<?php echo $v['id']; ?>" class="newQuestion">
                  <input name="value<?php echo $core->dblang;?>[<?php echo $v['id']; ?>]" type="text"  id="value<?php echo $k; ?>" class="inputbox" value="<?php echo $v['value'.$core->dblang] ?>" size="55" />
                  <img src="images/handle.png" alt="" class="smallHandle" style="margin-right:8px;margin-top:4px"/> </div>
                <?php endforeach;?>
                <?php unset($v);?>
                <?php unset($k);?>
              </div>
              <?php /*?>         <input type="button" id="btnAdd" class="button-sml" value="<?php echo PLG_PL_ADD_Q;?>" />
		<input type="button" id="btnDel" class="button-alt-sml" value="<?php echo PLG_PL_DEL_Q;?>" /><?php */?></td>
          </tr>
          <tr>
            <th><?php echo PLG_PL_ACTIVE;?>:</th>
            <td><span class="input-out">
              <label for="status-1"><?php echo _YES;?></label>
              <input name="status" type="radio" id="status-1" value="1" <?php getChecked($row['status'], 1); ?> />
              <label for="status-2"><?php echo _NO;?></label>
              <input name="status" type="radio" id="status-2" value="0" <?php getChecked($row['status'], 0); ?> />
              </span></td>
          </tr>
        </tbody>
      </table>
      <input name="pollid" type="hidden" value="<?php echo $poll->pollid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("updatePoll","plugins/poll/controller.php");?> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
	/*
    $('#btnAdd').click(function () {
        var value = $('.newQuestion').length;
        var newValue = new Number(value + 1);

        var newElem = $('#input_' + value).clone().attr('id', 'input_' + newValue);

        newElem.children(':first').attr('id', 'value' + newValue).attr('name', 'value<?php //echo $core->dblang;?>[' + newValue + ']');
        $('#input_' + value).after(newElem);
        (value) ? $('#btnDel').show() : $('#btnDel').hide();
    });

    $('#btnDel').click(function () {
        var value = $('.newQuestion').length;

        $('#input_' + value).remove();
        (value - 1 == 1) ? $('#btnDel').hide() : $('#btnDel').show();
    });
	*/
    $("div#sort-options").sortable({
        handle: '.smallHandle',
        opacity: 0.6,
        helper: 'helper',
        update: function() {
            var result = $('div#sort-options').sortable('serialize');
			result += '&sortpoll=1';
			$.ajax({
				type: "post",
				url: "plugins/poll/controller.php",
				data: result,
				cache: false,
				success: function (res) {
					$('#msgDisplay').html(res);
				}
			});
		  
			
            //$.post("plugins/poll/controller.php?sortpoll=1&" + order, function(theResponse) {
               // $("#msgDisplay").html(theResponse);
            //});
        }

    });
});
// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_PL_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_PL_INFO2 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_PL_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_PL_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=poll" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <td width="200"><?php echo PLG_PL_QUESTION;?>: <?php echo required();?></td>
            <td><input name="question<?php echo $core->dblang;?>" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <td><?php echo PLG_PL_OPTIONS;?>:</td>
            <td valign="top"><div style="margin-bottom:4px;" id="input1" class="newQuestion">
                <input name="value<?php echo $core->dblang;?>[1]" type="text"  id="value1" class="inputbox" size="55" />
              </div>
              <input type="button" id="btnAdd" class="button" value="<?php echo PLG_PL_ADD_Q;?>" />
              <input type="button" id="btnDel" class="button-orange" value="<?php echo PLG_PL_DEL_Q;?>" /></td>
          </tr>
          <tr>
            <td><?php echo PLG_PL_ACTIVE;?>:</td>
            <td><span class="input-out">
              <label for="status-1"><?php echo _YES;?></label>
              <input name="status" type="radio" id="status-1" value="1" checked="checked" />
              <label for="status-2"><?php echo _NO;?></label>
              <input name="status" type="radio" id="status-2" value="0" />
              </span></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("addPoll","plugins/poll/controller.php");?> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('#btnAdd').click(function () {
        var value = $('.newQuestion').length;
        var newValue = new Number(value + 1);
        var newElem = $('#input' + value).clone().attr('id', 'input' + newValue);

        newElem.children(':first').attr('id', 'value' + newValue).attr('name', 'value<?php echo $core->dblang;?>[' + newValue + ']');
        $('#input' + value).after(newElem);
		(value) ? $('#btnDel').show() : $('#btnDel').hide();
    });

    $('#btnDel').click(function () {
        var value = $('.newQuestion').length;
        $('#input' + value).remove();
        (value - 1 == 1) ? $('#btnDel').hide() : $('#btnDel').show();
        
    });
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_PL_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_PL_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=config&amp;plug=poll&amp;plug_action=add" class="button-sml"><?php echo PLG_PL_ADD1;?></a></span><?php echo PLG_PL_SUBTITLE3 . $content->getPluginName(get("plug"));?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_PL_QUESTION;?></th>
          <th class="left"><?php echo PLG_PL_DATE;?></th>
          <th><?php echo PLG_PL_VIEW;?></th>
          <th><?php echo PLG_PL_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($pollrow == 0):?>
        <tr style="background-color:transparent">
          <td colspan="6"><?php echo $core->msgAlert(PLG_PL_NOPOLL,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($pollrow as $prow):?>
        <tr>
          <th><?php echo $prow['id'];?>.</th>
          <td><?php echo $prow['question'.$core->dblang];?></td>
          <td><?php echo dodate($core->short_date, $prow['created']);?></td>
          <td class="center"><a href="javascript:void(0);" class="view-poll" data-info="<?php echo $prow['question'.$core->dblang];?>" id="poll_<?php echo $prow['id'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo PLG_PL_VIEW;?>"/></a></td>
          <td class="center"><a href="index.php?do=plugins&amp;action=config&amp;plug=poll&amp;plug_action=edit&amp;pollid=<?php echo $prow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_PL_EDIT.': '.$prow['question'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $prow['question'.$core->dblang];?>" id="item_<?php echo $prow['id'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo _DELETE.': '.$prow['question'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($prow);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_PL_POLL, "deletePoll","plugins/poll/controller.php");?> 
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
	// View Poll
    $('a.view-poll').click(function() {
        var id = $(this).attr('id').replace('poll_', '')
        var title = $(this).attr('data-info');
		  $.ajax({
			  type: 'post',
			  url: "plugins/poll/controller.php",
			  data: 'viewPoll=' + id + '&name=' + title,
			  success: function (res) {
				$.confirm({
					'title': title,
					'message': res,
					'buttons': {
						'<?php echo _CLOSE;?>': {
							'class': 'no'
						}
					}
				});
			  }
		  });	
        return false;
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>