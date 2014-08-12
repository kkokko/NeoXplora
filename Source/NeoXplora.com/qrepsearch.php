<?php
error_reporting(E_ALL);
include_once 'includes/config.php';
$pagetitle = "QREP Search";
include_once 'includes/header.php';
include_once 'includes/qrepconfig.php';

$strSearchQrep = $_REQUEST['q'];

if($strSearchQrep != '')
{
    $arrSearchQrep = explode(' CONTAINING ',$strSearchQrep);
    $strFinSearchQrep = $arrSearchQrep[1];
    preg_match_all("/\((([^()]*|(?R))*)\)/", $strFinSearchQrep, $matches);

    $strSubAtt = "";
    foreach($matches[0] as $key => $value) //for main attribute
    {
        $strFinSearchQrep = str_replace($value, '', $strFinSearchQrep);
    }
    foreach($matches[1] as $key => $value) //for sub attribute
    {
        $strSubAtt .= getSubAttributeString($value, "").", ";
    }
 
    $strFinSearchQrep = $strFinSearchQrep.", ".substr($strSubAtt,0,-2); // Concat Main string and Sub string
    $arrExpSearchrep = explode(', ',$strFinSearchQrep);
    
    $arrPropertyTypeCol = array("=","<","<=",">",">=","!=");
    $arrPropertyTypeColVal = array("=" => "otEquals", 
                                   "<" => "otSmaller", 
                                   "<=" => "otSmallerOrEqual" , 
                                   ">" => "otGreater", 
                                   ">=" => "otGreaterOrEqual" ,
                                   "!=" => "otDiffers");
                                   
    $strWhere = "";
    $strPropValWhere = "";
    foreach($arrExpSearchrep as $key => $value)
    { 
      if(strpos($value, '.') !== false)
      { 
          if($strWhere != '')
            $strWhere .= " or ";
          
          $strWhere .= " ( ";
          $strWhere .= " `PropertyType` = 'ptAttribute' ";
          
          $strPT = '';
          foreach ($arrPropertyTypeCol as $propertyType) 
          {
            if (strpos($value, $propertyType) !== false) {
                $strPT = $propertyType; // field value found in a string
            }
          }
          
          if($strPT != '')
          {
             $pattern =  '/\.([^'.preg_quote($strPT, '/').']*?)'.preg_quote($strPT, '/').'/';
             preg_match($pattern, $value, $matches);
             $strWhere .= " and `Key` = '".trim($matches[1])."' ";
          }
          else 
          {
            $arrPropKey = explode('.',$value);
            $strWhere .= " and `Key` = '".trim($arrPropKey[1])."' ";
          }
          
          if($strPT != '')
          $arrPropKeyVal = explode($strPT,$value);
          
          $strKeyVal = $arrPropKeyVal[1];
          
          $strWhere .= " and `Id` IN (SELECT KeyId FROM neox_reppropertyvalue WHERE ( `OperatorType` = '".$arrPropertyTypeColVal[$strPT]."' and `Value` = '".trim($strKeyVal)."' ) )";
          $strWhere .= " ) ";
          
          if($strPropValWhere != '')
            $strPropValWhere .= " or ";
        if($strPT != '')
        $arrPropKeyVal = explode($strPT,$value);
        $strPropValWhere .= " ( `OperatorType` = '".$arrPropertyTypeColVal[$strPT]."' and `Value` = '".trim($arrPropKeyVal[1])."' ) ";
        
          
      }
      else if(strpos($value, ':') !== false)
        {
          /* 
          if($strWhere != '')
            $strWhere .= " or ";
          
          $strWhere .= " ( ";
          $strWhere .= " `PropertyType` = 'ptEvent' ";
          
          $strPT = '';
          foreach ($arrPropertyTypeCol as $propertyType) 
          {
            if (strpos($value, $propertyType) !== false) {
                $strPT = $propertyType; // field value found in a string
            }
          }
          
          if(strpos($value, '=') !== false)
          {
            //preg_match('/\:([^\=]*?)\=/', $value, $matches);
             $pattern =  '/\:([^'.preg_quote($strPT, '/').']*?)'.preg_quote($strPT, '/').'/';
             preg_match($pattern, $value, $matches);
             $strWhere .= " and `Key` = '".trim($matches[1])."' ";
          }
          else 
          {
            $arrPropKey = explode(':',$value);
            $strWhere .= " and `Key` = '".trim($arrPropKey[1])."' ";
          }
          $strWhere .= " ) ";
           */
        }
        
       
        /*if($strPropValWhere != '')
            $strPropValWhere .= " or ";
        if($strPT != '')
        $arrPropKeyVal = explode($strPT,$value);
        $strPropValWhere .= " ( `OperatorType` = '".$arrPropertyTypeColVal[$strPT]."' and `Value` = '".trim($arrPropKeyVal[1])."' ) ";
        */
        
    }
   // echo $strWhere;
   // echo $strPropValWhere;
                    
  /*echo $qry = "SELECT count(*) as cntKey, PageId FROM 
            (
            SELECT nrk.Id, PageId FROM neox_reppropertykey nrk INNER JOIN neox_repentity nre 
                  ON nre.Id = nrk.ParentEntityId WHERE (".str_replace('`Id`','nrk.Id',$strWhere).") 
            UNION 
            SELECT nrk.Id, PageId FROM neox_reppropertykey nrk INNER JOIN neox_repentity nre 
                  ON nre.Id = nrk.ParentEntityId WHERE ParentEntityId IN ( 
                    SELECT ParentEntityId FROM neox_reppropertykey as nk WHERE (".str_replace('`Id`','nk.Id',$strWhere).") ) and PropertyType = 'ptEvent' 
            ) as resultTB 
            GROUP BY PageId ORDER BY cntKey DESC";
    
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
    
    while($arrResult = mysql_fetch_array($result))
    {
      $arrURL[$arrResult['PageId']] = $arrResult['PageId']; 
    } */
    
    $qry = "SELECT * FROM neox_reppropertykey nrk WHERE (".str_replace('`Id`','nrk.Id',$strWhere).") 
            UNION 
            SELECT * FROM neox_reppropertykey nrk WHERE ParentEntityId IN ( 
                    SELECT ParentEntityId FROM neox_reppropertykey as nk WHERE (".str_replace('`Id`','nk.Id',$strWhere).") ) and PropertyType = 'ptEvent' 
            ";
    
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
    
    $strKeyId = '';
    while($arrResult = mysql_fetch_array($result))
    {
      $strKeyId .= $arrResult['Id'].', '; 
    }
    
    $strKeyId = substr($strKeyId, 0, -2);
    $qry = "SELECT count(*) as cntKey, PageId FROM neox_repentity nre INNER JOIN neox_reppropertykey nrk ON nrk.ParentEntityId = nre.Id and nrk.Id IN (".$strKeyId.") GROUP BY PageId";
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
    
    $i=0;
    $strPageId = '';
    while($arrResult = mysql_fetch_array($result))
    {
      $arrResultURL[$i]['PageId'] = $arrResult['PageId']; 
      $arrResultURL[$i]['cntKey'] = $arrResult['cntKey']; 
      $strPageId .= $arrResult['PageId'].', ';
      $i++;
    }
    $strPageId = substr($strPageId, 0, -2);
 
        
     // For ParentKeyId
    $qry = "SELECT * FROM neox_reppropertykey nrk WHERE nrk.ParentKeyId IN (".$strKeyId.")  ";
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
     
    while($arrResult = mysql_fetch_array($result))
    {
      $arrParentKeyRes = getPageIdFromParentKeyId($arrResult['ParentKeyId']);
        
        $flgAdd = 0;
        for($j=0; $j<= $i; $j++)
        {
          if($arrResultURL[$j]['PageId'] == $arrParentKeyRes['PageId'])
          {
            $arrResultURL[$j]['cntKey'] = $arrResultURL[$j]['cntKey'] + $arrParentKeyRes['cntKey'];
          }
          
        }
        
        if(strpos($strPageId,$arrParentKeyRes['PageId']) === false) 
        {
          $flgAdd = 1; 
        }
        
        if($flgAdd == 1)
        {
          $arrResultURL[$i]['PageId'] = $arrParentKeyRes['PageId']; 
          $arrResultURL[$i]['cntKey'] = $arrParentKeyRes['cntKey']; 
          $i++;
        }
      
    }
    
    // For ParentValueId
    $qry = "SELECT * FROM neox_reppropertykey WHERE Id IN (SELECT nrv.KeyId FROM neox_reppropertykey nrk INNER JOIN neox_reppropertyvalue nrv ON nrv.Id = nrk.ParentValueId 
                  WHERE nrv.KeyId IN (".$strKeyId.") )";
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
    
    while($arrResult = mysql_fetch_array($result))
    {
      if($arrResult['ParentEntityId'] != null)
      {
        $arrParentValueRes = getPageIdFromParentKeyId($arrResult['Id']);
      }
      else if($arrResult['ParentKeyId'] != null)
      {
        $arrParentValueRes = getPageIdFromParentKeyId($arrResult['ParentKeyId']);
      }
      else if($arrResult['ParentValueId'] != null)
      {
        $arrParentValueResTes = getPageIdFromParentValueId($arrResult['ParentValueId']);
        
        if($arrParentValueResTes['ParentEntityId'] != '')
        {
          $arrParentValueRes = getPageIdFromParentKeyId($arrParentValueResTes['Id']);
        }
        else if($arrParentValueResTes['ParentKeyId'] != '')
        {
          $arrParentValueRes = getPageIdFromParentKeyId($arrParentValueResTes['ParentKeyId']);
        }
      }
      
      $flgAdd = 0;
        for($j=0; $j<= $i; $j++)
        {
          if($arrResultURL[$j]['PageId'] == $arrParentValueRes['PageId'])
          {
            $arrResultURL[$j]['cntKey'] = $arrResultURL[$j]['cntKey'] + $arrParentValueRes['cntKey'];
          }
          
        }
        
        if(strpos($strPageId,$arrParentValueRes['PageId']) === false) 
        {
          $flgAdd = 1; 
        }
        
        if($flgAdd == 1)
        {
          $arrResultURL[$i]['PageId'] = $arrParentValueRes['PageId']; 
          $arrResultURL[$i]['cntKey'] = $arrParentValueRes['cntKey']; 
          $i++;
        }
    }
    
    // Sort Desc
    $arrURL = array_msort($arrResultURL, array('cntKey'=>SORT_DESC));
   
    //print_r($arrURL);
    //echo FULLBASE;
    //$rows = $result->fetch_assoc();
    //print_r($rows);
}
//exit;
?>
<link href="<?php echo FULLBASE; ?>style/search_results.css" rel="stylesheet" type="text/css" />
<script src="<?php echo FULLBASE; ?>js/search.js"></script>
<div id="content">
    <div class="container relative searchPageContainer">
        <div id ="search_box" class="search_box" >
            <div id='logo_box_wrp'>
                <div class="logo"><img src="images/NeoXploraLOGO.png" alt="" border="0" /></div>
            </div>
            <form id="qrepSearchForm" name="qrepSearchForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div class="search_bar row">
                <input type="text" name="q" id="q" />
                <input type="submit" value="" />
            </div>
            </form>
        </div>
    


<div id="searchResults">
  <div class="searchResultContainer">
  <?php if(count($arrURL) > 0) {
    foreach($arrURL as $strVal){ 
    echo '<div class="searchResultItem"><a href=""><span class="titletext">'.getPageNameByID($strVal['PageId']).'</span></a></div>';
    }
  } ?>
  </div>
</div>
</div>
</div>
<?php
include_once 'includes/footer.php';

function getPageNameByID($pageId)
{
  global $link1;
  $qry = "SELECT * FROM neox_page WHERE Id = '".$pageId."' ";
  $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
  $arrResult = mysql_fetch_assoc($result);
  
  return $arrResult['Name'];
}

/*
 * Get Sub attribute for bracket attribute
 */
function getSubAttributeString($strVal, $strResVal)
{
   if(strpos($strVal, "(") !== false)
   {
      preg_match_all("/\((([^()]*|(?R))*)\)/", $strVal, $matches);
      
      foreach($matches[1] as $key => $value) // For Sub attribute
      {
          $strResVal .= getSubAttributeString($value, $strResVal). ", ";
      }
      foreach($matches[0] as $key => $value) // For Main Attribute
      {
          $strResVal .= str_replace($value, '', $strVal);
      }
   }  
   else 
   {
     $strResVal = $strVal;
   }
   
   return $strResVal;
}

function getPageIdFromParentKeyId($ParentKeyId)
{
  global $link1;
  $qry = "SELECT count(*) as cntKey, PageId FROM neox_repentity nre INNER JOIN neox_reppropertykey nrk ON nrk.ParentEntityId = nre.Id and nrk.Id = '".$ParentKeyId."' ";
  $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
  $arrResult = mysql_fetch_assoc($result);
  
  //print_r($arrResult);
  if($arrResult['PageId'] == '')
  {
    $qry = "SELECT * FROM neox_reppropertykey WHERE Id = '".$ParentKeyId."' ";
    $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
    $arrResult1 = mysql_fetch_assoc($result);
    $arrResult = getPageIdFromParentKeyId($arrResult1['ParentKeyId']);
  }
  
  return $arrResult;
}

function getPageIdFromParentValueId($ParentValueId)
{
  global $link1;
  $qry = "SELECT * FROM neox_reppropertykey WHERE Id IN (SELECT nrv.KeyId FROM neox_reppropertykey nrk INNER JOIN neox_reppropertyvalue nrv ON nrv.Id = nrk.ParentValueId  
                  WHERE nrv.Id = '".$ParentValueId."') ";
  $result=  mysql_query($qry, $link1) or die("error : " . mysql_error($link1));
  $arrResult = mysql_fetch_assoc($result);
  
  if($arrResult['ParentValueId'] != null)
  {
    $arrResult = getPageIdFromParentValueId($arrResult['ParentValueId']);
  }
  
  return $arrResult;
 }

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}

?>