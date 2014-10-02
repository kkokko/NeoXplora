<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TPanelPages extends \SkyCore\TModel {
    
    public function countPages($categoryId, $status) {
      $categoryCnd = '';
      if($categoryId > -1) {
        $categoryCnd = 'AND p.[[page.categoryid]] = ' . $categoryId;
      }
      
      $query = "
        SELECT
          COUNT(DISTINCT p.[[page.id]]) AS `total`
        FROM [[page]] p";
      
      switch($status) {
        case -1:
          $query .= "\n" . $this->conditionPagesToBeAdded();
        break;
        case 1: 
          $query .= "\n" . $this->conditionPagesNotSplit();
        break;
        case 2: 
          $query .= "\n" . $this->conditionPagesNotInterpreted();
        break;
        case 3: 
          $query .= "\n" . $this->conditionPagesNotLinked();
        break;
        case 4: 
          $query .= "\n" . $this->conditionPagesTrained();
        break;
        default:
          $query .= "\n WHERE 1=1 ";
      }

      $query .= "\n" . $categoryCnd;
      //if($status == 2) { $query .= " GROUP BY p.[[page.id]] "; }

      $result = $this->query($query);
      
      return $this->result($result);
    }
    
    public function getPages($offset, $limit, $categoryId, $status) {
      $categoryCnd = '';
      if($categoryId > -1) {
        $categoryCnd = 'AND p.[[page.categoryid]] = ' . $categoryId;
      }
      
      $query = "
        SELECT DISTINCT
          p.[[page.id]], 
          p.[[page.status]], 
          p.[[page.title]],
          p.[[page.body]]
        FROM [[page]] p";
      
      $condition = '';
      switch($status) {
        case -1:
          $query .= "\n" . $this->conditionPagesToBeAdded();
        break;
        case 1: 
          $query .= "\n" . $this->conditionPagesNotSplit();
        break;
        case 2: 
          $query .= "\n" . $this->conditionPagesNotInterpreted();
        break;
        case 3: 
          $query .= "\n" . $this->conditionPagesNotLinked();
        break;
        case 4: 
          $query .= "\n" . $this->conditionPagesTrained();
        break;
        default:
          $query .= "\n WHERE 1=1 ";
      }
      
      $query .= "\n" . $categoryCnd;
      
      if($status == 2) { $query .= " GROUP BY p.[[page.id]] "; }
      $query .= " ORDER BY TRIM(p.[[page.title]]) ASC ";
      $query .= " LIMIT :1, :2 ";
      
      $result = $this->query($query, $offset, $limit);
      
      return $this->result($result);
    }
    
    private function conditionPagesToBeAdded() {
      return " 
        LEFT JOIN [[sentence]] s on p.[[page.id]] = s.[[sentence.pageid]]
        WHERE s.[[sentence.id]] IS NULL";
    }
    
    private function conditionPagesNotSplit() {
      return " 
        INNER JOIN [[sentence]] s ON s.[[sentence.pageid]] = p.[[page.id]]
        WHERE
          s.[[sentence.status]] IN ('ssFinishedGenerate', 'ssTrainedSplit')";
    }
    
    private function conditionPagesNotInterpreted() {
      return " 
        INNER JOIN [[sentence]] s ON s.[[sentence.pageid]] = p.[[page.id]]
        WHERE
          s.[[sentence.status]] IN ('ssReviewedSplit', 'ssTrainedRep')";
    }
    
    private function conditionPagesNotLinked() {
      return "
        LEFT JOIN (
          SELECT COUNT(*) AS total, s1.[[sentence.pageid]] FROM [[sentence]] s1 GROUP BY s1.[[sentence.pageid]]
        ) a1 ON p.[[page.id]] = a1.[[sentence.pageid]]
        LEFT JOIN (
          SELECT COUNT(*) AS totalR, s2.[[sentence.pageid]] FROM [[sentence]] s2 WHERE s2.[[sentence.status]] = 'ssReviewedRep' GROUP BY s2.[[sentence.pageid]]
        ) a2 ON p.[[page.id]] = a2.[[sentence.pageid]]
        WHERE a1.total = a2.totalR
          AND p.[[page.status]] <> 'psReviewedCRep'";
    }
    
    private function conditionPagesTrained() {
      return "
        WHERE p.[[page.status]] = 'psReviewedCRep'";
    }
    
  }
?>