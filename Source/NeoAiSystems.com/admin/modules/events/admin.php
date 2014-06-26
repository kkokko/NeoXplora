<?php
  /**
   * Event Manager
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("events")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $event = new eventManager();
  $eventrow = $event->getEvents();
?>
<?php switch($core->maction): case "edit": ?>
<?php $row = $core->getRowById("mod_events", $event->eventid);?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo PLG_EM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_EM_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_EM_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_EM_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=events" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_EM_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_VENUE;?>:</th>
            <td><input name="venue<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['venue'.$core->dblang];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_CONTACT;?>:</th>
            <td><input name="contact_person" type="text" class="inputbox" value="<?php echo $row['contact_person'];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_CONTACT_U;?>:</th>
            <td><select class="custombox" name="user_id" style="width:300px">
                <?php foreach($event->getUserList() as $urow):?>
                <option value="<?php echo $urow['id'];?>"<?php if($row['user_id'] == $urow['id']) echo ' selected="selected"';?>><?php echo $urow['name'];?></option>
                <?php endforeach;?>
                <?php unset($urow);?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_EMAIL;?>:</th>
            <td><input name="contact_email" type="text" class="inputbox" value="<?php echo $row['contact_email'];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_PHONE;?>:</th>
            <td><input name="contact_phone" type="text" class="inputbox" value="<?php echo $row['contact_phone'];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_DATE_S;?>: <?php echo required();?></th>
            <td><input name="date_start" type="text" class="inputbox" id="date_start" value="<?php echo $row['date_start'].' '.$row['time_start'];?>" size="25"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_TIME_S;?>: <?php echo required();?></th>
            <td><input name="date_end" type="text" class="inputbox" id="date_end" value="<?php echo $row['date_end'].' '.$row['time_end'];?>" size="25"  /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_COLOUR;?>:</th>
            <td><input id="colorpicker" name="color" type="text" value="#<?php echo $row['color'];?>" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($row['body'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
        </tbody>
      </table>
      <input name="eventid" type="hidden" value="<?php echo $event->eventid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processEvent","modules/events/controller.php");?> 
<script type="text/javascript">
$(document).ready(function() {
  $('#colorpicker').colorPicker();	
  $('#date_start').dateplustimepicker({
      <?php echo $event->getCalData();?>
  });
  $('#date_end').dateplustimepicker({
	<?php echo $event->getCalData();?>
  });
});
</script>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo PLG_EM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_EM_INFO2 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_EM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_EM_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=events" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_EM_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_VENUE;?>:</th>
            <td><input name="venue<?php echo $core->dblang;?>" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_CONTACT;?>:</th>
            <td><input name="contact_person" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_CONTACT_U;?>:</th>
            <td><select class="custombox" name="user_id" style="width:300px">
                <?php foreach($event->getUserList() as $urow):?>
                <option value="<?php echo $urow['id'];?>"><?php echo $urow['name'];?></option>
                <?php endforeach;?>
                <?php unset($urow);?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_EMAIL;?>:</th>
            <td><input name="contact_email" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_PHONE;?>:</th>
            <td><input name="contact_phone" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_DATE_S;?>: <?php echo required();?></th>
            <td><input name="date_start" type="text" class="inputbox" id="date_start" size="25" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_TIME_S;?>: <?php echo required();?></th>
            <td><input name="date_end" type="text" class="inputbox" id="date_end"  size="25"  /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_COLOUR;?>:</th>
            <td><input id="colorpicker" name="color" type="text" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_EM_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processEvent","modules/events/controller.php");?> 
<script type="text/javascript">
$(document).ready(function() {
  $('#colorpicker').colorPicker();
  $('#date_start').dateplustimepicker({
      <?php echo $event->getCalData();?>
  });
  $('#date_end').dateplustimepicker({
	<?php echo $event->getCalData();?>
  });
});
</script>
<?php break;?>
<?php case"view": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo PLG_EM_TITLE4;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><?php echo PLG_EM_INFO4;?></p>
<div class="block-header">
  <h2><span><a href="index.php?do=modules&amp;action=config&amp;mod=events"><?php echo PLG_EM_BACKTO;?></a></span><?php echo PLG_EM_VIEWCAL2;?></h2>
</div>
<div class="block-content">
  <div id="cal-wrap">
    <?php $event->renderCalendar('large');?>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
  function loadList() {
	  $.ajax({
		  url: "modules/events/calendar.php",
		  cache: false,
		  success: function (html) {
			  $("#cal-wrap").html(html);
		  }
	  });
  }

  $(function () {
	  $(".loadevent").live("click", function (event) {
		  var id = $(this).attr('id').replace('eventid_', '');
		  var mytext = $("#eid_" + id).html();
		  var title = $("#eid_" + id).attr('title');

		  $('<div id="event-dialog" title="' + title + '">' + mytext + '</div>').appendTo('body');
		  event.preventDefault();

		  $("#event-dialog").dialog({
			  width: 450,
			  height: "auto",
			  modal: true,
			  close: function (event, ui) {
				  $("#event-dialog").remove();
			  }
		  });
	  });
  });

  $(document).ready(function () {
	  $("a.changedate").live("click", function () {
		  var parent = $(this);
		  var caldata = $(this).attr('id').replace('item_', '');
		  var month = caldata.split(":")[0];
		  var year = caldata.split(":")[1];
		  $.ajax({
			  type: "POST",
			  url: "modules/events/calendar.php",
			  data: {
				  'year': year,
				  'month': month
			  },
			  success: function (data, status) {
				  $("#cal-wrap").fadeIn("fast", function () {
					  $(this).html(data);
				  });
			  }
		  });
		  return false;
	  });
  });
// ]]>
</script>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo PLG_EM_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_EM_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=modules&amp;action=config&amp;mod=events&amp;mod_action=view" class="button-sml"><?php echo PLG_EM_VIEWCAL;?></a> <a href="index.php?do=modules&amp;action=config&amp;mod=events&amp;mod_action=add" class="button-sml"><?php echo PLG_EM_ADD;?></a></span><?php echo PLG_EM_SUBTITLE3 . $content->getModuleName(get("mod"));?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo PLG_EM_TITLE;?></th>
          <th class="left sortable"><?php echo PLG_EM_DSTART;?></th>
          <th class="left sortable"><?php echo PLG_EM_TSTART;?></th>
          <th><?php echo PLG_EM_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($eventrow == 0):?>
        <tr>
          <td colspan="6"><div class="msgInfo"><?php echo PLG_EM_NOEVENT;?></div></td>
        </tr>
        <?php else:?>
        <?php foreach ($eventrow as $emrow):?>
        <tr>
          <th><?php echo $emrow['id'];?>.</th>
          <td><?php echo $emrow['title'.$core->dblang];?></td>
          <td><?php echo dodate($core->short_date, $emrow['date_start']);?></td>
          <td><?php echo $emrow['time_start'];?></td>
          <td class="center"><a href="index.php?do=modules&amp;action=config&amp;mod=events&amp;mod_action=edit&amp;eventid=<?php echo $emrow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_EM_EDIT.': '.$emrow['title'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $emrow['title'.$core->dblang];?>" id="item_<?php echo $emrow['id'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo _DELETE.': '.$emrow['title'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($slrow);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_EM_EVENT, "deleteEvent","modules/events/controller.php");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            4: {
                sorter: false
            },
            5: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>