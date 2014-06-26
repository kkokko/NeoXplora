<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $gal = new Gallery();
?>
<?php
  /* Update Configuration*/
  if (isset($_POST['processGallery'])):
  $gal->galid = (isset($_POST['galid'])) ? $_POST['galid'] : 0; 
  $gal->updateConfig();
  endif;
?>
<?php
  /* Delete Gallery */
  if (isset($_POST['deleteGallery']))
      : if (intval($_POST['deleteGallery']) == 0 || empty($_POST['deleteGallery']))
      : redirect_to("index.php?do=modules&action=config&mod=galler");
  endif;
  
  $id = intval($_POST['deleteGallery']);
  $folder = getValue("folder", "mod_gallery_config", "id='" . $id . "'");
  $dirname = WOJOLITE . $gal->galpath . $folder;
  
  delete_directory($dirname);

  $action = $db->delete("mod_gallery_config", "id='" . $id . "'");
  $db->delete("mod_gallery_images", "gallery_id='" . $id . "'");

  $title = sanitize($_POST['title']);
  
  print ($action) ? $wojosec->writeLog(_GALLERY .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(_GALLERY .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);   
  endif;
?>
<?php
  /* Update Gallery Order */
  if (isset($_POST['sortPhotos'])) :
      foreach ($_POST['gid'] as $k => $v) :
          $p = $k + 1;
          
          $data['sorting'] = $p;
          
          $db->update("mod_gallery_images", $data, "id='" . $v . "'");
      endforeach;
 endif;
?>
<?php
  /* Delete Image */
  if (isset($_POST['deletePhoto'])): 
  
  list($id, $folder) = explode("::", $_POST['deletePhoto']);
  
  $id = intval($id);
  $folder = sanitize($folder);
  $dirname = WOJOLITE . $gal->galpath . $folder;

  $img = getValue("thumb", "mod_gallery_images", "id='" . $id . "'");
  
  @unlink($dirname . '/'.$img);
  @unlink($dirname . '/thumbs/'.$img);

  $db->delete("mod_gallery_images", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_IMAGE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(_IMAGE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS); 
  endif;

  /* == Rename Photo == */
  if (isset($_POST['renamePhoto'])): 
  $id = intval($_POST['renamePhoto']);
  
  $data['title'.$core->dblang] = sanitize($_POST['title']);
  $data['description'.$core->dblang] = sanitize($_POST['desc']);
  
  $db->update("mod_gallery_images", $data, "id='" . $id . "'");
    
  print ($db->affected()) ? $wojosec->writeLog(_IMAGE .' <strong>'.$data['title'.$core->dblang].'</strong> '._UPDATED, "", "no", "module") . $core->msgOk(_IMAGE .' <strong>'.$data['title'.$core->dblang].'</strong> '._UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS); 
  endif;
  
  
  /* == Load Photos == */
  if (isset($_POST['loadPhotos'])):
      $gid = intval($_POST['gid']);
	  $gfolder = sanitize($_POST['gfolder']);
	  
      $gal->loadPhotos($gid, $gfolder);
  endif;

  /* == Upload Photos == */
  if (isset($_POST['fileid'])):
       $gid = intval($_REQUEST['gid']);
	   $gfolder = sanitize($_REQUEST['gfolder']);
       $gal->doUpload($gid, $gfolder);
  endif;
?>