<?php
  /**
   * Get Image Preview
   *
   * @package CMS Pro
   * @author wojocms.com
   * @copyright 2010
   * @version $Id: getimage.php, v2.00 2011-04-20 14:20:26 gewa Exp $
   */

  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  require_once(WOJOLITE . "admin/modules/gallery/admin_class.php");
  $gallery = new Gallery();
  
  $img = sanitize($_GET['image']);
  $folder = sanitize($_GET['folder']);
  
  $imagesource = WOJOLITE . $gallery->galpath . $folder.'/'.$img;

  if (!file_exists($imagesource))
      die();
  $filetype = substr($imagesource, strlen($imagesource) - 4, 4);
  if ($filetype == ".gif")
      $image = @imagecreatefromgif($imagesource);
  if ($filetype == ".jpg")
      $image = @imagecreatefromjpeg($imagesource);
  if ($filetype == ".png")
      $image = @imagecreatefrompng($imagesource);
  if (empty($image))
      die();
  $watermark = @imagecreatefrompng(UPLOADS.'watermark.png');
  $imagewidth = imagesx($image);
  $imageheight = imagesy($image);
  $watermarkwidth = imagesx($watermark);
  $watermarkheight = imagesy($watermark);
  
  /* For centerd watermark */
  $startwidth = (($imagewidth - $watermarkwidth) / 2);
  $startheight = (($imageheight - $watermarkheight) / 2);
  
  /* Bottom Right Corner  */
  //$startwidth = (($imagewidth - $watermarkwidth));
  //$startheight = (($imageheight - $watermarkheight));
  
  imagecopy($image, $watermark, $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
  header("Content-type: image/jpeg");
  header('Content-Disposition: inline; filename=' . $imagesource);
  imagejpeg($image);
  imagedestroy($image);
  imagedestroy($watermark);
?>