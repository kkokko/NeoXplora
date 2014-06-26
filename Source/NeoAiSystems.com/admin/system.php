<?php
  /**
   * System
   *
   * @version $Id: system.php, v1.00 2013-03-05 14:15:22 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("System")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<div class="block-top-header">
  <h1><img src="images/pdf-sml.png" alt="" /><?php echo SYS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo SYS_INFO;?></p>
<div class="block-border">
  <div class="block-header">
    <ul class="idTabs" id="tabs">
      <li><a href="#cms"><?php echo SYS_CMS_INF;?></a></li>
      <li><a href="#php"><?php echo SYS_PHP_INF;?></a></li>
      <li><a href="#server"><?php echo SYS_SER_INF;?></a></li>
    </ul>
    <h2><?php echo SYS_SUB;?></h2>
  </div>
  <div class="block-content">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td><div id="cms" class="tab_content">
            <table cellspacing="0" cellpadding="0" class="forms">
              <tbody>
                <tr>
                  <th width="200"><?php echo SYS_CMS_VER;?>:</th>
                  <td>v<?php echo $core->version;?> <span id="version"> </span></td>
                </tr>
                <tr>
                  <th><?php echo SYS_ROOT_URL;?>:</th>
                  <td><?php echo SITEURL;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_ROOT_PATH;?>:</th>
                  <td><?php echo WOJOLITE;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_UPL_URL;?>:</th>
                  <td><?php echo UPLOADURL;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_UPL_PATH;?>:</th>
                  <td><?php echo UPLOADS;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_DEF_LANG;?>:</th>
                  <td><?php echo strtoupper($core->lang);?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="php" class="tab_content">
            <table cellspacing="0" cellpadding="0" class="forms">
              <tbody>
                <tr>
                  <th width="200"><?php echo SYS_PHP_VER;?>:</th>
                  <td><?php echo phpversion();?></td>
                </tr>
                <tr>
                  <?php $gdinfo = gd_info();?>
                  <th><?php echo SYS_GD_VER;?>:</th>
                  <td><?php echo $gdinfo['GD Version'];?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_MQR;?>:</th>
                  <td><?php echo (ini_get('magic_quotes_gpc')) ? _ON : _OFF;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_LOG_ERR;?>:</th>
                  <td><?php echo (ini_get('log_errors')) ? _ON : _OFF;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_MEM_LIM;?>:</th>
                  <td><?php echo ini_get('memory_limit');?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_RG;?>:</th>
                  <td><?php echo (ini_get('register_globals')) ? _ON : _OFF;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_SM;?>:</th>
                  <td><?php echo (ini_get('safe_mode')) ? _ON : _OFF;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_UMF;?>:</th>
                  <td><?php echo ini_get('upload_max_filesize'); ?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_PMF;?>:</th>
                  <td><?php echo ini_get('post_max_size');?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_SSP;?>:</th>
                  <td><?php echo ini_get('session.save_path' );?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="server" class="tab_content">
            <table cellspacing="0" cellpadding="0" class="forms">
              <tbody>
                <tr>
                  <th width="200"><?php echo SYS_SER_OS;?>:</th>
                  <td><?php echo php_uname('s')." (".php_uname('r').")";?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_SER_API;?>:</th>
                  <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_SER_MR;?>:</th>
                  <td><?php echo ($core->seo) ? _ON : _OFF;?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_SER_DB;?>:</th>
                  <td><?php echo mysqli_get_client_info();?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_DBV;?>:</th>
                  <td><?php echo mysqli_get_server_info($db->getLink());?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_MEMALO;?>:</th>
                  <td><?php echo ini_get('memory_limit');?></td>
                </tr>
                <tr>
                  <th><?php echo SYS_STS;?>:</th>
                  <td><?php echo getSize(disk_free_space("."));?></td>
                </tr>
              </tbody>
            </table>
          </div></td>
      </tr>
    </table>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    var url = 'http://www.wojoscripts.com/version.json?callback=?';
    $.ajax({
        type: 'GET',
        url: url,
        async: false,
        jsonpCallback: 'jsonCallback',
        contentType: "application/json",
        dataType: 'jsonp',
        success: function (json) {
            if (json.versions[0].cmspro !== <?php echo $core->version;?> ) {
                $("#version").html('(Latest Version: v.' + json.versions[0].cmspro + ' Released: ' + json.versions[0].cmsupdated + ')');
            }
        }
    });
});
</script>