<?php
  /**
   * Account Template
   *
   * @version $Id: account.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Full Layout -->
  <div class="row grid_24">
  <div id="page">
    <h1><?php echo _UA_TITLE1;?></h1>
    <p class="info"><?php echo _UA_INFO1;?></p>
    <?php if($listpackrow ):?>
    <div class="box">
      <h3><?php echo _UA_SEL_MEMBERSHIP;?></h3>
      <table class="display">
        <tr>
          <td><?php echo _MS_TITLE;?>:</td>
          <?php foreach ($listpackrow as $prow):?>
          <td><strong><?php echo $prow['title'.$core->dblang];?></strong></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <td><?php echo _MS_PRICE;?>:</td>
          <?php foreach ($listpackrow as $prow):?>
          <td><?php echo $core->formatMoney($prow['price']);?></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <td><?php echo _MS_PERIOD;?>:</td>
          <?php foreach ($listpackrow as $prow):?>
          <td><?php echo $prow['days'] . ' ' .$member->getPeriod($prow['period']);?></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <td><?php echo _MS_RECURRING;?>:</td>
          <?php foreach ($listpackrow as $prow):?>
          <td><?php echo ($prow['recurring']) ? _YES : _NO;?></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <td><?php echo _MS_DESC;?>:</td>
          <?php foreach ($listpackrow as $prow):?>
          <td><small><?php echo $prow['description'.$core->dblang];?></small></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <?php foreach ($listpackrow as $prow):?>
          <td class="center"><?php if($prow['price'] == 0):?>
            <a href="javascript:void(0);" class="add-cart" id="item_<?php echo $prow['id'].':FREE';?>"> <img src="<?php echo SITEURL;?>/assets/activate.png" alt="" title="<?php echo _UA_ACTIVATE;?>"/> </a>
            <?php else:?>
            <?php if($gatelist):?>
            <?php foreach($gatelist as $grow):?>
            <?php if ($grow['active']):?>
            <a href="javascript:void(0);" class="add-cart" id="item_<?php echo $prow['id'].':'.$grow['id'];?>"> <img src="<?php echo SITEURL.'/gateways/'.$grow['dir'].'/'.$grow['name'].'.png';?>" alt="" class="tooltip" title="<?php echo $grow['displayname'];?>"/> </a>
            <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
            <?php endif;?></td>
          <?php endforeach;?>
        </tr>
      </table>
    </div>
    <div id="show-result"></div>
    <?php endif;?>
    <div class="box top10">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <h3><?php echo _UA_SUBTITLE1;?></h3>
        <div id="msgholder"></div>
        <table class="display">
          <tr>
            <th><?php echo _USERNAME;?>:</th>
            <td><div class="placeholder">
                <input name="username" type="text" disabled="disabled" class="inputbox" value="<?php echo $row['username'];?>" size="45" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _PASSWORD;?>:</th>
            <td><div class="placeholder">
                <input name="password" type="password"  class="inputbox" size="45" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _UR_EMAIL;?>: <?php echo required(true);?></th>
            <td><div class="placeholder">
                <input name="email" type="text" class="inputbox" value="<?php echo $row['email'];?>" size="45" maxlength="40" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _UR_FNAME;?>: <?php echo required(true);?></th>
            <td><div class="placeholder">
                <input name="fname" type="text" class="inputbox" value="<?php echo $row['fname'];?>" size="45" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _UR_LNAME;?>: <?php echo required(true);?></th>
            <td><div class="placeholder">
                <input name="lname" type="text" class="inputbox" value="<?php echo $row['lname'];?>" size="45" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _UR_AVATAR;?>:</th>
            <td><input name="avatar"  type="file" class="fileinput"/></td>
          </tr>
          <tr>
            <th><?php echo _UR_IS_NEWSLETTER;?>:</th>
            <td><input type="radio" name="newsletter" value="1" <?php getChecked($row['newsletter'], 1); ?> />
              <?php echo _YES;?> &nbsp;
              <input type="radio" name="newsletter" value="0" <?php getChecked($row['newsletter'], 0); ?>/>
              <?php echo _NO;?></td>
          </tr>
          <tr>
            <th><?php echo _UR_DATE_REGGED;?>:</th>
            <td><?php echo dodate($core->long_date, $row['created']);?></td>
          </tr>
          <tr>
            <th><?php echo _UR_LASTLOGIN;?>:</th>
            <td><?php echo dodate($core->long_date, $row['lastlogin']);?></td>
          </tr>
          <tr>
            <td><input name="dosubmit" type="submit" value="<?php echo _UA_UPDATE;?>"  class="button"/></td>
            <td><div style="position:relative"> <span style="position:absolute;right:0;top:-70px">
                <?php if($row['fbid']):?>
                <img src="http://graph.facebook.com/<?php echo $row['fbid'];?>/picture?type=square" alt="<?php echo $row['username'];?>" class="avatar"/>
                <?php elseif ($row['avatar']):?>
                <img src="<?php echo UPLOADURL;?>avatars/<?php echo $row['avatar'];?>" alt="<?php echo $row['username'];?>" class="avatar"/>
                <?php else:?>
                <img src="<?php echo UPLOADURL;?>avatars/blank.png" alt="<?php echo $row['username'];?>" class="avatar"/>
                <?php endif;?>
                </span></div></td>
          </tr>
        </table>
      </form>
    </div>
    <div class="box top10">
      <table class="display">
        <tr>
          <th><?php echo _UA_CUR_MEMBERSHIP;?></th>
          <th><?php if($row['membership_id'] == 0) :?>
            <?php echo _UA_NO_MEMBERSHIP;?>
            <?php else:?>
            <?php echo $mrow['title'.$core->dblang];?>
            <?php endif;?></th>
        </tr>
        <tr>
          <th><?php echo _UA_VALID_UNTIL;?></th>
          <th><?php if($row['membership_id'] == 0) :?>
            --/--
            <?php else:?>
            <?php echo dodate($core->long_date, $mrow['mem_expire']);?>
            <?php endif;?></th>
        </tr>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php echo $core->doForm("processUser","ajax/controller.php");?> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $("a.add-cart").live("click", function () {
        var parent = $(this);
        $.ajax({
            type: "POST",
            url: "ajax/controller.php",
            data: 'addtocart=' + $(this).attr('id').replace('item_', ''),
            success: function (msg) {
                $("#show-result").html(msg);

            }
        });
        return false;
    });
});
// ]]>
</script> 
<!-- Full Layout /--> 