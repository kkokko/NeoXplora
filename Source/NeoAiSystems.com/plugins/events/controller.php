<?php
  /**
   * Calendar
   *
   * @version $Id: calendar.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");

  require_once(WOJOLITE . "admin/modules/events/lang/" . $core->language . ".lang.php");
  require_once(WOJOLITE . "admin/modules/events/admin_class.php");
  
  $calendar = new eventManager(); 
  
  $day = isset($_GET['d']) ? sanitize($_GET['d'],2) : 0;
  $month = isset($_GET['m']) ? sanitize($_GET['m'],2) : 0;
  $year = isset($_GET['y']) ? sanitize($_GET['y'],4) : 0;
  $eventrow = $calendar->getAllEvents($day, $month, $year)
?>
<?php if(!$eventrow):?>
<div class="msgError"><?php echo PLG_EM_EVENT_ERR;?></div>
<?php else:?>
<div class="event-wrapper">
  <div class="event-list">
  <?php foreach($eventrow as $row):?>
    <h3 class="event-title"><span><?php echo PLG_EM_TSE . ': ' . $row['stime'] . '/' . $row['etime'] . '</span>' . $row['title' . $core->dblang];?></h3>
    <?php if ($row['venue' . $core->dblang]):?>
    <h6 class="event-venue"><?php echo $row['venue' . $core->dblang];?></h6>
    <?php endif;?>
    <hr />
    <div class="event-desc"><?php echo cleanOut($core->out_url($row['body' . $core->dblang]));?></div>
    <span class="contact-info-toggle"><?php echo PLG_EM_CONTACT;?></span>
    <div class="event-contact">
      <?php if ($row['venue' . $core->dblang]):?>
      <div><?php echo $row['contact_person'];?></div>
      <?php endif;?>
      <?php if ($row['venue' . $core->dblang]):?>
      <div><?php echo $row['contact_email'];?></div>
      <?php endif;?>
      <?php if ($row['venue' . $core->dblang]):?>
      <div><?php echo $row['contact_phone'];?></div>
      <?php endif;?>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php endif;?>