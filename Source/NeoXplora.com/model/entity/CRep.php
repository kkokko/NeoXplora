<?php
namespace NeoX\Entity;

require_once APP_DIR . "/app/system/Entity.php";

class TCRep extends \SkyCore\TEntity {

  public static $entityname = "crep";

  //Table Name in the DB
  public static $tablename = "neox_crep";

  //Table Fields in the DB
  public static $tok_id = "Id";
  public static $tok_pageid = "PageId";
  public static $tok_sentenceid = "SentenceId";
  public static $tok_parentcrepid = "ParentCRepId";

}
?>