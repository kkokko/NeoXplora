<?php
  /**
   * Comments Template
   *
   * @version $Id: template.tpl.php, v2.00 2011-04-20 16:17:34 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="commentWrap" id="wrapper_<?php echo $row['id'];?>">
  <div class="row">
  <div class="col grid_3">
    <figure>
      <?php if($row['fbid']):?>
      <img src="http://graph.facebook.com/<?php echo $row['fbid'];?>/picture?type=square" alt="<?php echo $row['username'];?>" class="avatar"/>
      <?php elseif ($row['avatar']):?>
      <img src="<?php echo UPLOADURL;?>avatars/<?php echo $row['avatar'];?>" alt="<?php echo $row['username'];?>" class="avatar"/>
      <?php else:?>
      <img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $row['username'];?>" class="avatar"/>
      <?php endif;?>
    </figure>
    </div>
    <div class="col grid_21">
    <div class="comment-info">
      <h4>
        <?php if($com->show_username):?>
        <?php if($com->show_www):?>
        <a href="<?php echo $row['www'];?>" target="_blank"><?php echo $row['username'];?></a>
        <?php else:?>
        <?php echo $row['username'];?>
        <?php endif;?>
        <?php endif;?>
        <?php if ($com->show_email) echo $row['email'];?>
      </h4>
      <div class="coment-date top10"><?php echo dodate($com->dateformat, $row['created']);?> - <a class="reply-link" href="#reply" onclick="updateOptions(<?php echo $row['id'];?>);" id="doreplay-<?php echo $row['id'];?>"><?php echo MOD_CM_REPLY2;?></a></div>
      <div class="comment-body"> <?php echo cleanOut($row['body']);?> </div>
      </div>
    </div>
  </div>
</div>