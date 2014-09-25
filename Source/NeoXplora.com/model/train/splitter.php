<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TTrainSplitter extends TTrain {
    
    public function getSentenceIdFromIds($sentenceIDs = array()) {
      if(!is_array($sentenceIDs) || count($sentenceIDs) == 0) return false;
        
      $wherein = "(";
      for($i = 0; $i < count($sentenceIDs); $i++) {
        $wherein .= $sentenceIDs[$i];
        if($i + 1 != count($sentenceIDs)) $wherein .= ", ";
      }
      $wherein .= ")";
      
      $query = $this->query("SELECT [[sentence.id]] FROM [[sentence]] WHERE [[sentence.id]] IN " . $wherein . " ORDER BY [[sentence.id]] ASC LIMIT 1");
      
      return $this->result($query);
    }
    
    public function countMainProtos($categoryId = -1) {
      $category_cnd = ' INNER JOIN [[page]] p ON se.[[sentence.pageid]] = p.[[page.id]] ';
      if($categoryId > -1) {
        $category_cnd .= ' AND p.[[page.categoryid]] = ' . $categoryId;;
      }
      
      $status = "se.[[sentence.status]] = 'ssFinishedGenerate'";
      
      $query = $this->query("
        SELECT COUNT(DISTINCT pr.[[proto.id]]) AS `total`
        FROM [[sentence]] se 
        INNER JOIN [[proto]] pr ON se.[[sentence.mainprotoid]] = pr.[[proto.id]]
        " . $category_cnd . "
        WHERE " . $status . "
      ");
      
      return $this->result($query);
    }
    
    public function getMainProtos($ignoreIDs = array(), $categoryId = -1) {
      $category_cnd = ' INNER JOIN [[page]] p ON se.[[sentence.pageid]] = p.[[page.id]] ';
      if($categoryId > -1) {
        $category_cnd .= ' AND p.[[page.categoryid]] = ' . $categoryId;;
      }
      
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore = 'AND pr.[[proto.id]] NOT IN (';
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      } 
      
      $status = "se.[[sentence.status]] = 'ssFinishedGenerate'";
      
      $query = $this->query("
        SELECT DISTINCT 
          pr.[[proto.id]] id,
          pr.[[proto.name]] name,
          pr.[[proto.order]] `order`,
          pr.[[proto.pageid]] pageid,
          p.[[page.title]] pagetitle
        FROM [[sentence]] se 
        INNER JOIN [[proto]] pr ON se.[[sentence.mainprotoid]] = pr.[[proto.id]] AND pr.[[proto.parentid]] IS NULL
        " . $category_cnd . "
        WHERE " . $status . "
        " . $ignore . "
        ORDER BY pr.[[proto.pageid]], pr.[[proto.id]]
        LIMIT 1
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
          se.[[sentence.order]] `order`
        FROM [[sentence]] se 
        WHERE [[sentence.protoid]] = :1
        ORDER BY se.[[sentence.id]]
      ", intval($protoId));
      
      return $this->result($query);
    } 
    
  }
?>