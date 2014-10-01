<?php
  namespace NeoX\Model;
  
  require_once __DIR__ . "/../Train.php";
  class TTrainLinker extends TTrain {
    
    public function getCategory($ignoreIDs = array(), $pStatus = "psFinishedCrawl", $pStatus2 = "psFinishedGenerate", $sStatus = null) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND pg.[[page.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }
    
      $query = $this->query("
        SELECT a.[[category.id]], a.`pageCount`, a.`trainedCount`
        FROM (
          SELECT c2.[[category.id]], c2.`pageCount`, COUNT(p2.[[page.id]]) AS trainedCount 
          FROM (
            SELECT c.[[category.id]], COUNT(p.[[page.id]]) AS pageCount 
            FROM [[category]] c
            INNER JOIN (
              SELECT DISTINCT pg.[[page.id]], pg.[[page.status]], pg.[[page.categoryid]]
              FROM [[page]] pg
              WHERE pg.[[page.status]] = :1 " . $ignore . "
            ) p ON c.[[category.id]] = p.[[page.categoryid]]
            GROUP BY(c.[[category.id]])
          ) c2
          LEFT JOIN [[page]] p2 ON p2.[[page.categoryid]] = c2.[[category.id]] AND p2.[[page.status]] NOT IN (:2, :3)
          GROUP BY (c2.[[category.id]])
        ) a INNER JOIN [[page]] p3 ON p3.[[page.categoryid]] = a.[[category.id]]
        GROUP BY p3.[[page.categoryid]]
        ORDER BY a.`trainedCount` ASC
        LIMIT 1;
      ", "psReviewedRep", $pStatus, $pStatus2);
      //"ssFinishedGenerate", "psFinishedCrawl", "psFinishedGenerate"
      return $this->result($query);
    }
    
    public function getPageByCategoryID($categoryID, $offset = 0, $ignoreIDs = array()) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND p.[[page.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }  
      
      $query = $this->query("
        SELECT DISTINCT p.[[page.id]], p.[[page.status]], p.[[page.title]]
        FROM [[page]] p
        INNER JOIN [[sentence]] se ON p.[[page.id]] = se.[[sentence.pageid]] AND p.[[page.status]] IN (:1, :2)
        WHERE p.[[page.categoryid]] = :3 " . $ignore . "
        ORDER BY p.[[page.assigneddate]] ASC, p.[[page.id]] ASC
        LIMIT :4,1
      ", 'psReviewedRep', 'psTrainingCRep', intval($categoryID), intval($offset));
      
      return $this->result($query);
    }
    
    public function getPage($ignoreIDs = array(), $categoryId) {
      $ignore = '';
      if(is_array($ignoreIDs) && count($ignoreIDs) > 0) {
        $ignore .= " AND p.[[page.id]] NOT IN (";
        for($i = 0; $i < count($ignoreIDs); $i++) {
          $ignore .= "'" . $ignoreIDs[$i] . "'";
          if($i != count($ignoreIDs) - 1) $ignore .= ', ';
        }
        $ignore .= ") ";
      }
      
      $category_cnd = '';
      if($categoryId > -1) {
        $category_cnd = ' AND p.[[page.categoryid]] = ' . $categoryId;
      }
      
      $query = $this->query("
        SELECT p.[[page.id]], p.[[page.status]], p.[[page.title]] 
        FROM [[page]] p
        LEFT JOIN (
          SELECT COUNT(*) AS total, s1.[[sentence.pageid]] FROM [[sentence]] s1 GROUP BY s1.[[sentence.pageid]]
        ) a1 ON p.[[page.id]] = a1.[[sentence.pageid]]
        LEFT JOIN (
          SELECT COUNT(*) AS totalR, s2.[[sentence.pageid]] FROM [[sentence]] s2 WHERE s2.[[sentence.status]] = 'ssReviewedRep' GROUP BY s2.[[sentence.pageid]]
        ) a2 ON p.[[page.id]] = a2.[[sentence.pageid]]
        WHERE a1.total = a2.totalR
        AND p.[[page.status]] <> 'psReviewedCRep'
        " . $category_cnd . "
        " . $ignore . "
        GROUP BY p.[[page.id]]
        ORDER BY p.[[page.id]]");
        
      return $this->result($query);
    }

    public function countPages($categoryId) {
      $category_cnd = '';
      if($categoryId > -1) {
        $category_cnd = ' AND p.[[page.categoryid]] = ' . $categoryId;
      }
      
      $query = $this->query("
        SELECT COUNT(p.[[page.id]]) AS `total`
        FROM [[page]] p
        LEFT JOIN (
          SELECT COUNT(*) AS total, s1.[[sentence.pageid]] FROM [[sentence]] s1 GROUP BY s1.[[sentence.pageid]]
        ) a1 ON p.[[page.id]] = a1.[[sentence.pageid]]
        LEFT JOIN (
          SELECT COUNT(*) AS totalR, s2.[[sentence.pageid]] FROM [[sentence]] s2 WHERE s2.[[sentence.status]] = 'ssReviewedRep' GROUP BY s2.[[sentence.pageid]]
        ) a2 ON p.[[page.id]] = a2.[[sentence.pageid]]
        WHERE a1.total = a2.totalR
        AND p.[[page.status]] <> 'psReviewedCRep'
        " . $category_cnd . "
        GROUP BY p.[[page.id]]");
        
      return $this->result($query);
    }
    
    public function getCReps($pageid) {
      $query = $this->query("
        SELECT cr.[[crep.id]] AS `CRepId`, s.[[sentence.id]] AS `SentenceId`, s.[[sentence.rep]] AS `Rep`, s.[[sentence.name]] AS `Sentence`
        FROM [[crep]] cr
        INNER JOIN [[sentence]] s ON cr.[[crep.sentenceid]] = s.[[sentence.id]]
        INNER JOIN [[proto]] pr on s.[[sentence.mainprotoid]] = pr.[[proto.id]]
        WHERE cr.[[crep.pageid]] = :1 AND [[crep.parentcrepid]] IS NULL
        ORDER BY pr.[[proto.order]], s.[[sentence.protoid]], s.[[sentence.order]], cr.[[crep.id]]
      ", intval($pageid));
      
      return $this->result($query);
    }
    
    public function getHighlights($CRepId) {
      $query = $this->query("
        SELECT ch.[[crephighlight.from]] AS `From`, ch.[[crephighlight.until]] AS `Until`, ch.[[crephighlight.style]] AS `Style`
        FROM [[crephighlight]] ch
        WHERE ch.[[crephighlight.crepid]] = :1
        ORDER BY ch.[[crephighlight.id]] ASC 
      ", intval($CRepId));
      
      return $this->result($query);
    }
    
    public function getChildren($CRepId) {
      $query = $this->query("
        SELECT cr.[[crep.id]] AS `CRepId`
        FROM [[crep]] cr
        WHERE cr.[[crep.parentcrepid]] = :1
        ORDER BY cr.[[crep.id]] ASC 
      ", intval($CRepId));
      
      return $this->result($query);
    }
    
  }
?>