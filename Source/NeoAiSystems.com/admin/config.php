<?php
  /**
   * Configuration
   *
   * @version $Id: config.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Configuration")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<div class="block-top-header">
  <h1><img src="images/settings-sml.png" alt="" /><?php echo _CG_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CG_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CG_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td colspan="2"><div class="button arrow">
                <input type="submit" value="<?php echo _CG_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _CG_SITENAME;?>: <?php echo required();?></th>
            <td><input name="site_name" type="text" class="inputbox" value="<?php echo $core->site_name;?>" size="55" />
              <?php echo tooltip(_CG_SITENAME_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_COMPANY;?>:</th>
            <td><input name="company" type="text" class="inputbox" value="<?php echo $core->company;?>" size="55"/>
              <?php echo tooltip(_CG_COMPANY_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_WEBURL;?>: <?php echo required();?></th>
            <td><input name="site_url" type="text" class="inputbox" value="<?php echo $core->site_url;?>" size="55" />
              <?php echo tooltip(_CG_WEBURL_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_WEBEMAIL;?>: <?php echo required();?></th>
            <td><input name="site_email" type="text" class="inputbox" value="<?php echo $core->site_email;?>" size="55" />
              <?php echo tooltip(_CG_WEBEMAIL_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_THEME;?>:</th>
            <td><select name="theme" class="custombox" id="themeswitch" style="width:250px">
                <option value=""><?php echo _CG_THEME_SEL;?></option>
                <?php getTemplates(WOJOLITE."/theme/", $core->theme)?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CG_THEME_VAR;?>:</th>
            <td id="themeOptions"></td>
          </tr>
          <tr>
            <th><?php echo _CG_BGIMG;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="bgimg" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _CG_DELBGIMG;?>:</th>
            <td><span class="input-out">
              <input name="dellbgimg" type="checkbox" value="1" class="checkbox"/>
              </span></td>
          </tr>
          <tr>
            <th><?php echo _CG_BGREP;?>:</th>
            <td><span class="input-out">
              <label for="repbg-1"><?php echo _YES;?></label>
              <input type="radio" name="repbg" id="repbg-1" value="1" <?php getChecked($core->repbg, 1); ?> />
              <label for="repbg-2"><?php echo _NO;?></label>
              <input type="radio" name="repbg" id="repbg-2" value="0" <?php getChecked($core->repbg, 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _CG_BGALIGN;?>:</th>
            <td><span class="input-out">
              <label for="bgalign-1"><?php echo _CG_BGALIGN_L;?></label>
              <input type="radio" name="bgalign" id="bgalign-1" value="left" <?php getChecked($core->bgalign, "left"); ?> />
              <label for="bgalign-2"><?php echo _CG_BGALIGN_R;?></label>
              <input type="radio" name="bgalign" id="bgalign-2" value="right" <?php getChecked($core->bgalign, "right"); ?> />
              <label for="bgalign-3"><?php echo _CG_BGALIGN_C;?></label>
              <input type="radio" name="bgalign" id="bgalign-3" value="center" <?php getChecked($core->bgalign, "center"); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _CG_BGFIXED;?>:</th>
            <td><span class="input-out">
              <label for="bgfixed-1"><?php echo _YES;?></label>
              <input type="radio" name="bgfixed" id="bgfixed-1" value="1" <?php getChecked($core->bgfixed, 1); ?> />
              <label for="bgfixed-2"><?php echo _NO;?></label>
              <input type="radio" name="bgfixed" id="bgfixed-2" value="0" <?php getChecked($core->bgfixed, 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _CG_BGCOLOR;?>:</th>
            <td><input id="colorpicker" name="bgcolor" type="text" value="#<?php echo str_replace("#", "", $core->bgcolor);?>" /></td>
          </tr>
          <tr>
            <th><?php echo _CG_LOGO;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="logo" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _CG_LOGO_DEL;?>:</th>
            <td><span class="input-out">
              <input name="dellogo" type="checkbox" value="1" class="checkbox"/>
              <?php echo tooltip(_CG_LOGO_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_SHOW_LOGIN;?>:</th>
           <td><span class="input-out">
              <label for="showlogin-1"><?php echo _YES;?></label>
              <input type="radio" name="showlogin" id="showlogin-1" value="1" <?php getChecked($core->showlogin, 1); ?> />
              <label for="showlogin-2"><?php echo _NO;?></label>
              <input type="radio" name="showlogin" id="showlogin-2" value="0" <?php getChecked($core->showlogin, 0); ?> />
              <?php echo tooltip(_CG_SHOW_LOGIN_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_SHOW_SEARCH;?>:</th>
           <td><span class="input-out">
              <label for="showsearch-1"><?php echo _YES;?></label>
              <input type="radio" name="showsearch" id="showsearch-1" value="1" <?php getChecked($core->showsearch, 1); ?> />
              <label for="showsearch-2"><?php echo _NO;?></label>
              <input type="radio" name="showsearch" id="showsearch-2" value="0" <?php getChecked($core->showsearch, 0); ?> />
              <?php echo tooltip(_CG_SHOW_SEARCH_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_OFFLINE;?>:</th>
            <td><span class="input-out">
              <label for="offline-1"><?php echo _YES;?></label>
              <input type="radio" name="offline" id="offline-1" value="1" onclick="$('.offline-data').show();" <?php getChecked($core->offline, 1); ?> />
              <label for="offline-2"><?php echo _NO;?></label>
              <input type="radio" name="offline" id="offline-2" value="0" onclick="$('.offline-data').hide();" <?php getChecked($core->offline, 0); ?> />
              <?php echo tooltip(_CG_OFFLINE_T);?></span></td>
          </tr>
          <tr class="offline-data">
            <th><?php echo _CG_OFFLINE_TIME;?>:</th>
            <td><input name="offline_data" type="text" class="inputbox" id="mdate" value="<?php echo $core->offline_data;?>" size="25" />
              <?php echo tooltip(_CG_OFFLINE_TIME_T);?></td>
          </tr>
          <tr class="offline-data">
            <th><?php echo _CG_OFFLINE_MSG;?>:</th>
            <td><textarea name="offline_msg" cols="50" rows="6"><?php echo $core->offline_msg;?></textarea>
              <?php echo tooltip(_CG_OFFLINE_MSG_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_EUCOOKIE;?>:</th>
            <td><span class="input-out">
              <label for="eucookie-1"><?php echo _YES;?></label>
              <input type="radio" name="eucookie" id="eucookie-1" value="1" <?php getChecked($core->eucookie, 1); ?> />
              <label for="eucookie-2"><?php echo _NO;?></label>
              <input type="radio" name="eucookie" id="eucookie-2" value="0"  <?php getChecked($core->eucookie, 0); ?> />
              <?php echo tooltip(_CG_EUCOOKIE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_SEO;?>:</th>
            <td><span class="input-out">
              <label for="seo-1"><?php echo _YES;?></label>
              <input name="seo" type="radio" id="seo-1"  value="1" <?php getChecked($core->seo, 1); ?> />
              <label for="seo-2"><?php echo _NO;?></label>
              <input name="seo" type="radio" id="seo-2" value="0" <?php getChecked($core->seo, 0); ?> />
              <?php echo tooltip(_CG_SEO_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_PERPAGE;?>:</th>
            <td><input name="perpage" type="text" class="inputbox" value="<?php echo $core->perpage;?>" size="5" />
              <?php echo tooltip(_CG_PERPAGE_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_SHORTDATE;?>:</th>
            <td><select class="custombox" name="short_date" style="width:200px">
                <?php echo $core->getShortDate();?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CG_LONGDATE;?>:</th>
            <td><select class="custombox" name="long_date" id="long_date" style="width:200px">
                <?php echo $core->getLongDate();?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CG_DTZ;?>:</th>
            <td><?php echo $core->getTimezones();?></td>
          </tr>
          <tr>
            <th><?php echo _CG_WEEKSTART;?>:</th>
            <td><select class="custombox" name="weekstart" style="width:200px">
                <?php echo $core->weekList();?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CG_LANG;?>:</th>
            <td><select name="lang" class="custombox" style="width:200px">
                <?php foreach($core->langList() as $lang):?>
                <?php $sel = ($core->lang == $lang['flag']) ? ' selected="selected"' : '';?>
                <option value="<?php echo $lang['flag'];?>"<?php echo $sel;?> style="background:url(<?php echo SITEURL . "/lang/" . $lang['flag'];?>.png) 98% center no-repeat"><?php echo $lang['name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CG_LANG_SHOW;?>:</th>
            <td><span class="input-out">
              <label for="show_lang-1"><?php echo _YES;?></label>
              <input type="radio" name="show_lang" id="show_lang-1" value="1" <?php getChecked($core->show_lang, 1); ?> />
              <label for="show_lang-2"><?php echo _NO;?></label>
              <input type="radio" name="show_lang" id="show_lang-2" value="0" <?php getChecked($core->show_lang, 0); ?> />
              <?php echo tooltip(_CG_LANG_SHOW_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_THUMB_WH;?>: <?php echo required();?></th>
            <td><input name="thumb_w" type="text" class="inputbox" value="<?php echo $core->thumb_w;?>" size="5"/>
              /
              <input name="thumb_h" type="text" class="inputbox" value="<?php echo $core->thumb_h;?>" size="5"/>
              <?php echo tooltip(_CG_THUMB_WH_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_IMG_WH;?>: <?php echo required();?></th>
            <td><input name="img_w" type="text" class="inputbox" value="<?php echo $core->img_w;?>" size="5"/>
              /
              <input name="img_h" type="text" class="inputbox" value="<?php echo $core->img_h;?>" size="5"/>
              <?php echo tooltip(_CG_IMG_WH_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_AVATAR_WH;?>: <?php echo required();?></th>
            <td><input name="avatar_w" type="text" class="inputbox" value="<?php echo $core->avatar_w;?>" size="5"/>
              /
              <input name="avatar_h" type="text" class="inputbox" value="<?php echo $core->avatar_h;?>" size="5"/>
              <?php echo tooltip(_CG_AVATAR_WH_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_CURRENCY;?>: <?php echo required();?></th>
            <td><input name="currency" type="text" class="inputbox" value="<?php echo $core->currency;?>" size="5"/>
              <?php echo tooltip(_CG_CURRENCY_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_CUR_SYMBOL;?>:</th>
            <td><input name="cur_symbol" type="text" class="inputbox" value="<?php echo $core->cur_symbol;?>" size="5"/>
              <?php echo tooltip(_CG_CUR_SYMBOL_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_REGVERIFY;?>:</th>
            <td><span class="input-out">
              <label for="reg_verify-1"><?php echo _YES;?></label>
              <input name="reg_verify" type="radio" id="reg_verify-1"  value="1" <?php getChecked($core->reg_verify, 1); ?> />
              <label for="reg_verify-2"><?php echo _NO;?></label>
              <input name="reg_verify" type="radio" id="reg_verify-2" value="0" <?php getChecked($core->reg_verify, 0); ?> />
              <?php echo tooltip(_CG_REGVERIFY_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_AUTOVERIFY;?>:</th>
            <td><span class="input-out">
              <label for="auto_verify-1"><?php echo _YES;?></label>
              <input name="auto_verify" type="radio" id="auto_verify-1"  value="1" <?php getChecked($core->auto_verify, 1); ?> />
              <label for="auto_verify-2"><?php echo _NO;?></label>
              <input name="auto_verify" type="radio" id="auto_verify-2" value="0" <?php getChecked($core->auto_verify, 0); ?> />
              <?php echo tooltip(_CG_AUTOVERIFY_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_REGALOWED;?>:</th>
            <td><span class="input-out">
              <label for="reg_allowed-1"><?php echo _YES;?></label>
              <input name="reg_allowed" type="radio" id="reg_allowed-1"  value="1" <?php getChecked($core->reg_allowed, 1); ?> />
              <label for="reg_allowed-2"><?php echo _NO;?></label>
              <input name="reg_allowed" type="radio" id="reg_allowed-2" value="0" <?php getChecked($core->reg_allowed, 0); ?> />
              <?php echo tooltip(_CG_REGALOWED_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_NOTIFY_ADMIN;?>:</th>
            <td><span class="input-out">
              <label for="notify_admin-1"><?php echo _YES;?></label>
              <input name="notify_admin" type="radio" id="notify_admin-1"  value="1" <?php getChecked($core->notify_admin, 1); ?> />
              <label for="notify_admin-2"><?php echo _NO;?></label>
              <input name="notify_admin" type="radio" id="notify_admin-2" value="0" <?php getChecked($core->notify_admin, 0); ?> />
              <?php echo tooltip(_CG_NOTIFY_ADMIN_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_ENABLE_FB;?>:</th>
            <td><span class="input-out">
              <label for="enablefb-1"><?php echo _YES;?></label>
              <input name="enablefb" type="radio" id="enablefb-1"  value="1" <?php getChecked($core->enablefb, 1); ?> />
              <label for="enablefb-2"><?php echo _NO;?></label>
              <input name="enablefb" type="radio" id="enablefb-2" value="0" <?php getChecked($core->enablefb, 0); ?> />
              <?php echo tooltip(_CG_ENABLE_FB_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_FB_API;?>:</th>
            <td><input name="fbapi" type="text" class="inputbox" value="<?php echo $core->fbapi;?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _CG_FB_SECRET;?>:</th>
            <td><input name="fbsecret" type="text" class="inputbox" value="<?php echo $core->fbsecret;?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _CG_USERLIMIT;?>:</th>
            <td><input name="user_limit" type="text" size="5" value="<?php echo $core->user_limit;?>" class="inputbox" />
              <?php echo tooltip(_CG_USERLIMIT_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_LOGIN_ATTEMPT;?>:</th>
            <td><input name="flood" type="text" class="inputbox" value="<?php echo $core->flood;?>" size="5"/>
              <input name="attempt" type="text" class="inputbox" value="<?php echo $core->attempt;?>" size="5"/>
              <?php echo tooltip(_CG_LOGIN_ATTEMPT_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_LOG_ON;?>:</th>
            <td><span class="input-out">
              <label for="logging-1"><?php echo _YES;?></label>
              <input name="logging" type="radio" id="logging-1"  value="1" <?php getChecked($core->logging, 1); ?> />
              <label for="logging-2"><?php echo _NO;?></label>
              <input name="logging" type="radio" id="logging-2" value="0" <?php getChecked($core->logging, 0); ?> />
              <?php echo tooltip(_CG_LOG_ON_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_MAILER;?>:</th>
            <td><select class="custombox" name="mailer" id="mailerchange" style="width:200px">
                <option value="PHP"<?php if ($core->mailer == "PHP") echo " selected=\"selected\"";?>>PHP Mailer</option>
                <option value="SMTP"<?php if ($core->mailer == "SMTP") echo " selected=\"selected\"";?>>SMTP Mailer</option>
                <option value="SMAIL"<?php if ($core->mailer == "SMAIL") echo " selected=\"selected\"";?>>Sendmail</option>
              </select>
              <?php echo tooltip(_CG_MAILER_T);?></td>
          </tr>
          <tr class="showsmail">
            <th><?php echo _CG_SMAILPATH;?>: <?php echo required();?></th>
            <td><input name="sendmail" type="text" class="inputbox" value="<?php echo $core->sendmail;?>" size="55" />
              <?php echo tooltip(_CG_SMAILPATH_T);?></td>
          </tr>
          <tr class="showsmtp">
            <th><?php echo _CG_SMTP_HOST;?>:</th>
            <td><input name="smtp_host" type="text" class="inputbox" value="<?php echo $core->smtp_host;?>" size="55" />
              <?php echo tooltip(_CG_SMTP_HOST_T);?></td>
          </tr>
          <tr class="showsmtp">
            <th><?php echo _CG_SMTP_USER;?>:</th>
            <td><input name="smtp_user" type="text" class="inputbox" value="<?php echo $core->smtp_user;?>" size="55" /></td>
          </tr>
          <tr class="showsmtp">
            <th><?php echo _CG_SMTP_PASS;?>:</th>
            <td><input name="smtp_pass" type="text" class="inputbox" value="<?php echo $core->smtp_pass;?>" size="55"/></td>
          </tr>
          <tr class="showsmtp">
            <th><?php echo _CG_SMTP_PORT;?>:</th>
            <td><input name="smtp_port" type="text" class="inputbox" value="<?php echo $core->smtp_port;?>" size="5" />
              <?php echo tooltip(_CG_SMTP_PORT_T);?></td>
          </tr>
          <tr class="showsmtp">
            <th><?php echo _CG_SMTP_SSL;?>:</th>
            <td><span class="input-out">
              <label for="is_ssl-1"><?php echo _YES;?></label>
              <input name="is_ssl" type="radio" id="is_ssl-1"  value="1" <?php getChecked($core->is_ssl, 1); ?> />
              <label for="is_ssl-2"><?php echo _NO;?></label>
              <input name="is_ssl" type="radio" id="is_ssl-2" value="0" <?php getChecked($core->is_ssl, 0); ?> />
              <?php echo tooltip(_CG_SMTP_SSL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _CG_GA;?>:</th>
            <td><textarea name="analytics" cols="50" rows="6"><?php echo $core->analytics;?></textarea>
              <?php echo tooltip(_CG_GA_T);?><br />
              <small><?php echo _CG_GA_I;?></small></td>
          </tr>
          <tr>
            <th><?php echo _CG_METAKEY;?>:</th>
            <td><input name="metakeys" type="text" class="inputbox" value="<?php echo $core->metakeys;?>" size="55" />
              <?php echo tooltip(_CG_METAKEY_T);?></td>
          </tr>
          <tr>
            <th><?php echo _CG_METADESC;?>:</th>
            <td><textarea name="metadesc" cols="50" rows="6"><?php echo $core->metadesc;?></textarea>
              <?php echo tooltip(_CG_METADESC_T);?></td>
          </tr>
        </tbody>
      </table>
      <input name="doconfig" type="hidden" value="1" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processConfig");?> 
<script type="text/javascript">
// <![CDATA[
 function loadThemeOpts() {
	$.ajax({
		type: 'post',
		url: "controller.php",
		data: 'themeoption=<?php echo $core->theme;?>',
		cache: false,
		success: function (html) {
			$("#themeOptions").html(html);
		}
	});
}
$(document).ready(function () {
  $('#colorpicker').colorPicker();
  (<?php echo $core->offline;?> == 1 ) ? $('.offline-data').fadeIn().show() : $('.offline-data').hide();
  $('#mdate').dateplustimepicker({
	  <?php
      	  $caldata = "dateFormat: 'yy:mm:dd',timeFormat: 'hh:mm:ss',";
		  $caldata .= "dayNames: [ '"._SUNDAY."', '"._MONDAY."', '"._TUESDAY."', '"._WEDNESDAY."', '"._THURSDAY."', '"._FRIDAY."', '"._SATURDAY."'],";
		  $caldata .= "dayNamesMin: ['"._SU."', '"._MO."', '"._TU."', '"._WE."', '"._TH."', '"._FR."', '"._SA."'],";
		  $caldata .= "dayNamesShort: ['"._SUN."', '"._MON."', '"._TUE."', '"._WED."', '"._THU."', '"._FRI."', '"._SAT."'],";
		  $caldata .= "monthNames: ['"._JAN."', '"._FEB."', '"._MAR."', '"._APR."', '"._MAY."', '"._JUN."', '"._JUL."', '"._AUG."', '"._SEP."', '"._OCT."', '"._NOV."', '"._DEC."'],";
		  $caldata .= "monthNamesShort: ['"._JA_."', '"._FE_."', '"._MA_."', '"._AP_."', '"._MY_."', '"._JU_."', '"._JL_."', '"._AU_."', '"._SE_."', '"._OC_."', '"._NO_."', '"._DE_."'],";
		  $caldata .= "firstDay: " . ($core->weekstart - 1) . ",";
		  $caldata .= "hourGrid: 4,";
		  $caldata .= "minuteGrid: 10,";
		  $caldata .= "secondGrid: 60";
		  print $caldata;?>
  });
	var res2 = '<?php echo $core->mailer;?>';
		(res2 == "SMTP" ) ? $('.showsmtp').fadeIn().show() : $('.showsmtp').hide();
    $('#mailerchange').change(function () {
		var res = $("#mailerchange option:selected").val();
		(res == "SMTP" ) ? $('.showsmtp').fadeIn().show() : $('.showsmtp').hide();
    });
	
    (res2 == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
    $('#mailerchange').change(function () {
        var res = $("#mailerchange option:selected").val();
        (res == "SMAIL") ? $('.showsmail').show() : $('.showsmail').hide();
    });

    loadThemeOpts();

    $('#themeswitch').change(function () {
        var option = $(this).val();
        $.post('controller.php', {
            themeoption: option
        }, function (data) {
            $('#themeOptions').html(data);
        });

    });
});
// ]]>
</script>