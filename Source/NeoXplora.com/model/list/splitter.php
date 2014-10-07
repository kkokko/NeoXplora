<?php
  namespace NeoX\Model;
  
  require_once APP_DIR . "/app/system/Model.php";
  class TListSplitter extends \SkyCore\TModel {
    
    public function getSplitsList($offset, $limit, $status, $search) {
      $status_cond = '';
      if($status != "" && strtolower($status) != "any") {
        $status_cond = " AND se.[[sentence.status]] = '" . $status . "'";
      }
      $search_cond = '';
      if($search != "") {
        $search_cond = " AND (
            a.SplitText LIKE '%" . $search . "%' OR
            a.proto LIKE '%" . $search . "%'
        ) ";
      }
      
      $query = $this->query("
        SELECT DISTINCT a.* 
        FROM (
          SELECT 
            pr.[[proto.id]] id, 
            pr.[[proto.name]] proto,
            pr.[[proto.mainprotoid]] mainprotoid,
            GROUP_CONCAT(splits.Name SEPARATOR ' ● ') SplitText 
          FROM [[proto]] pr
          INNER JOIN ( 
            select * from (
              SELECT [[proto.id]] Id, [[proto.name]] Name, [[proto.order]] `Order`, [[proto.parentid]] ParentId
              FROM [[proto]]
              UNION
              SELECT [[sentence.id]], [[sentence.name]], [[sentence.order]], [[sentence.protoid]]
              FROM [[sentence]]
            ) a order by `Order`
          ) splits ON pr.[[proto.id]] = splits.ParentId
          GROUP BY pr.[[proto.id]], pr.[[proto.mainprotoid]]
          HAVING COUNT(splits.Id) > 1
          ORDER BY pr.[[proto.pageid]], pr.[[proto.order]]
        ) a 
        LEFT JOIN [[sentence]] se ON se.[[sentence.mainprotoid]] = a.mainprotoid
        WHERE 1=1
        " . $status_cond . "
        " . $search_cond . "
        GROUP BY a.id
        LIMIT :1, :2
      ", intval($offset), intval($limit));
      
      return $this->result($query);
    }
    
    public function countSplitsList($status, $search) {
      $status_cond = '';
      if($status != "" && strtolower($status) != "any") {
        $status_cond = " AND se.[[sentence.status]] = '" . $status . "'";
      }
      $search_cond = '';
      if($search != "") {
        $search_cond = " AND (
            a.SplitText LIKE '%" . $search . "%' OR
            a.proto LIKE '%" . $search . "%'
        ) ";
      }
      
      $query = $this->query("
        SELECT COUNT(*) AS `total` FROM (
          SELECT a.* FROM 
          (
            SELECT 
              pr.[[proto.id]] id,
              pr.[[proto.name]] proto, 
              pr.[[proto.mainprotoid]] mainprotoid,
              GROUP_CONCAT(splits.Name SEPARATOR ' ● ') SplitText
            FROM [[proto]] pr
            INNER JOIN (
              SELECT [[proto.id]] Id, [[proto.name]] Name, [[proto.parentid]] ParentId
              FROM [[proto]]
              UNION
              SELECT [[sentence.id]], [[sentence.name]], [[sentence.protoid]]
              FROM [[sentence]]
            ) splits ON pr.[[proto.id]] = splits.ParentId
            GROUP BY pr.[[proto.id]], pr.[[proto.mainprotoid]]
            HAVING COUNT(splits.Id) > 1  
          ) a 
          LEFT JOIN [[sentence]] se ON se.[[sentence.mainprotoid]] = a.mainprotoid
          WHERE 1=1
          " . $status_cond . "
          " . $search_cond . "
          GROUP BY a.id
        ) b
      ");
      
      return $this->result($query);
    }
    
  }
?>
