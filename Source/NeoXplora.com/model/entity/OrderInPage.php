<?php
  namespace NeoX\Entity;
  
  require_once APP_DIR . "/app/system/Entity.php";
  class TOrderInPage extends \SkyCore\TEntity {
	  
    //Table Name in the DB
    public static $tablename = "neox_orderinpage";
    
    //Table Fields in the DB
    public static $tok_id = "Id";
    public static $tok_pageid = "PageId";
    public static $tok_protoid = "ProtoId";
    public static $tok_sentenceid = "SentenceId";
    public static $tok_order = "Order";
    public static $tok_indentation = "Indentation";
    
    public function getMinOrderForSentenceIds($sentenceIds) {
      if(!count($sentenceIds)) {
        $sentenceIds = array(-1);
      }
      $where_in = "(" . implode(", ", $sentenceIds) . ")";
      
      $query = $this->query("
        SELECT MIN([[orderinpage.order]]) AS Min 
        FROM [[orderinpage]]
        WHERE [[orderinpage.sentenceid]] IN " . $where_in . "
      ");
      
      return $this->result($query);
    }
    
    public function increaseIndentation($sentenceIds) {
      if(!count($sentenceIds)) {
        $sentenceIds = array(-1);
      }
      $where_in = "(" . implode(", ", $sentenceIds) . ")";
      
      $query = $this->query("
        UPDATE [[orderinpage]]
        SET
          [[orderinpage.indentation]] = [[orderinpage.indentation]] + 1
        WHERE [[orderinpage.sentenceid]] IN " . $where_in . "
      ");
      
      return $this->result($query);
    }
    
    public function increaseOrder($pageId, $minOrder) {
      $query = $this->query("
        UPDATE [[orderinpage]]
        SET
          [[orderinpage.order]] = [[orderinpage.order]] + 1
        WHERE [[orderinpage.order]] >= :1 AND [[orderinpage.pageid]] = :2
      ", $minOrder, $pageId);
      
      return $this->result($query);
    }
    
	}
?>