<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TFixOrder extends \SkyCore\TModel {
      
    public function getMainProtos() {
      $query = $this->query("
        SELECT DISTINCT 
          pr.[[proto.id]] id,
          pr.[[proto.name]] name,
          pr.[[proto.order]] `order`,
          pr.[[proto.pageid]] pageid
        FROM [[sentence]] se 
        INNER JOIN [[proto]] pr ON se.[[sentence.mainprotoid]] = pr.[[proto.id]]
        ORDER BY pr.[[proto.pageid]], pr.[[proto.order]]
      ");
      
      return $this->result($query);
    }
    
    public function getChildProtos($protoId) {
      $query = $this->query("
        SELECT 
          pr.[[proto.id]] id,
          pr.[[proto.name]] name,
          pr.[[proto.order]] `order`,
          pr.[[proto.pageid]] pageid
        FROM [[proto]] pr 
        WHERE [[proto.parentid]] = :1
        ORDER BY pr.[[proto.id]]
      ", intval($protoId));
      
      return $this->result($query);
    }
    
    public function getSentences($protoId) {
      $query = $this->query("
        SELECT 
          se.[[sentence.id]] id,
          se.[[sentence.name]] name,
          se.[[sentence.pageid]] pageid,
          se.[[sentence.order]] `order`,
          se.[[sentence.status]] status
        FROM [[sentence]] se 
        WHERE [[sentence.protoid]] = :1
        ORDER BY se.[[sentence.id]]
      ", intval($protoId));
      
      return $this->result($query);
    }
    
    public function insertProtoOrder($pageId, $protoId, $order, $indentation) {
      $this->query("
        INSERT INTO [[orderinpage]] ([[orderinpage.pageid]], [[orderinpage.protoid]], [[orderinpage.order]], [[orderinpage.indentation]]) VALUES (:1, :2, :3, :4)
      ", intval($pageId), intval($protoId), intval($order), intval($indentation));
    }
    
    public function insertSentenceOrder($pageId, $sentenceId, $order, $indentation) {
      $this->query("
        INSERT INTO [[orderinpage]] ([[orderinpage.pageid]], [[orderinpage.sentenceid]], [[orderinpage.order]], [[orderinpage.indentation]]) VALUES (:1, :2, :3, :4)
      ", intval($pageId), intval($sentenceId), intval($order), intval($indentation));
    }
    
  }
?>