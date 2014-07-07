<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TTrain extends \SkyCore\TModel {
    
    public function getCategory($sStatus, $pStatus, $pStatus2, $ignoreIDs = array()) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND se.[[sentence.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }
    
      $query = $this->query("
        SELECT a.[[category.id]] AS id, a.`pageCount`, a.`trainedCount`
        FROM (
          SELECT c2.[[category.id]], c2.`pageCount`, COUNT(p2.[[page.id]]) AS trainedCount 
          FROM (
            SELECT c.[[category.id]], COUNT(p.[[page.id]]) AS pageCount 
            FROM [[category]] c
            INNER JOIN (
              SELECT DISTINCT pg.[[page.id]], pg.[[page.status]], pg.[[page.categoryid]]
              FROM [[page]] pg
              INNER JOIN [[sentence]] se ON pg.[[page.id]] = se.[[sentence.pageid]]
              WHERE se.[[sentence.status]] = :1 " . $ignore . "
            ) p ON c.[[category.id]] = p.[[page.categoryid]]
            GROUP BY(c.[[category.id]])
          ) c2
          LEFT JOIN [[page]] p2 ON p2.[[page.categoryid]] = c2.[[category.id]] AND p2.[[page.status]] NOT IN (:2, :3)
          GROUP BY (c2.[[category.id]])
        ) a INNER JOIN [[page]] p3 ON p3.[[page.categoryid]] = a.[[category.id]]
        GROUP BY p3.[[page.categoryid]]
        ORDER BY a.`trainedCount` ASC
        LIMIT 1;
      ", $sStatus, $pStatus, $pStatus2);
      //"ssFinishedGenerate", "psFinishedCrawl", "psFinishedGenerate"
      return $this->result($query);
    }
    
    public function getSentence($categoryID, $offset, $sentence_offset, $sStatus, $ignoreIDs = array()) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND se.[[sentence.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }
      
      $query = $this->query("
        SELECT
          se.[[sentence.id]],
          se.[[sentence.name]]
        FROM (
          SELECT DISTINCT p.[[page.id]], p.[[page.status]] 
          FROM [[page]] p
          INNER JOIN [[sentence]] s ON p.[[page.id]] = s.[[sentence.pageid]]
          WHERE p.[[page.categoryid]] = :1
          AND s.[[sentence.status]] = :2
          LIMIT :3, 1
        ) a INNER JOIN [[sentence]] se ON a.[[page.id]] = se.[[sentence.pageid]]
        WHERE se.[[sentence.status]] = :2
        " . $ignore . "
        ORDER BY se.[[sentence.assigneddate]] ASC, se.[[sentence.id]] DESC
        LIMIT :4, 1
      ", $categoryID, $sStatus, $offset, $sentence_offset);
      //ssFinishedGenerate
      return $this->result($query);
    } 
    
    public function countSentences($categoryID, $offset, $sStatus, $ignoreIDs = array()) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND se.[[sentence.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }
      
      $query = $this->query("
        SELECT COUNT(se.[[sentence.id]]) AS sentenceCount
        FROM (
          SELECT DISTINCT p.[[page.id]], p.[[page.status]] 
          FROM [[page]] p
          INNER JOIN [[sentence]] s ON p.[[page.id]] = s.[[sentence.pageid]]
          WHERE p.[[page.categoryid]] = :1
          AND s.[[sentence.status]] = :2
          LIMIT :3, 1
        ) a INNER JOIN [[sentence]] se ON a.[[page.id]] = se.[[sentence.pageid]]
        " . $ignore . "
        WHERE se.[[sentence.status]] = :2
      ", $categoryID, $sStatus, $offset);
      //ssFinishedGenerate
      return $this->result($query);
    } 
    
    public function getSentencesByProtoId($ProtoId) {
      $query = $this->query("
        SELECT s.[[sentence.status]], s.[[sentence.pageid]], p2.[[page.status]] as pageStatus 
        FROM [[sentence]] s
        INNER JOIN [[page]] p2 ON s.[[sentence.pageid]] = p2.[[page.id]]
        WHERE s.[[sentence.pageid]] = 
          (
            SELECT se.[[sentence.pageid]] 
            FROM [[sentence]] se 
            WHERE se.[[sentence.protoid]] = :1
            LIMIT 1
          )
      ", $ProtoId);
      
      return $this->fullresult($query);
    }
    
    public function getSentencesFromPageById($Id) {
      $query = $this->query("
        SELECT s.[[sentence.status]], s.[[sentence.pageid]], p2.[[page.status]] as pageStatus 
        FROM [[sentence]] s
        INNER JOIN [[page]] p2 ON s.[[sentence.pageid]] = p2.[[page.id]]
        WHERE s.[[sentence.pageid]] = 
          (
            SELECT se.[[sentence.pageid]] 
            FROM [[sentence]] se 
            WHERE se.[[sentence.id]] = :1
            LIMIT 1
          )
      ", $Id);
      
      return $this->fullresult($query);
    }
    
  }
?>