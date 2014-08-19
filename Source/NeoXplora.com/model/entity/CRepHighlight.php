<?php
namespace NeoX\Entity;

require_once APP_DIR . "/app/system/Entity.php";

class TCRepHighlight extends \SkyCore\TEntity {

  public static $entityname = "crephighlight";

  //Table Name in the DB
  public static $tablename = "neox_crep_highlight";

  //Table Fields in the DB
  public static $tok_id = "Id";
  public static $tok_pageid = "PageId";
  public static $tok_crepid = "CRepId";
  public static $tok_from = "From";
  public static $tok_until = "Until";
  public static $tok_style = "Style";

}
?>