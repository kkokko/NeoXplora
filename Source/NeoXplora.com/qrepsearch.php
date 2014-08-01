<?php
error_reporting(E_ALL);
include_once 'includes/config.php';
$pagetitle = "Search";
include_once 'includes/header.php';
//@ $db = new mysqli('localhost', 'root', '', 'zadmin_neoxplora');
//@ $db = new mysqli('127.0.0.1', 'userneo123', 'edu3uvy4e', 'zadmin_neo123');

$strSearchQrep = $_REQUEST['q'];

if($strSearchQrep != '')
{
    $arrSearchQrep = explode(' CONTAINING ',$strSearchQrep);
    $strFinSearchQrep = $arrSearchQrep[1];
    preg_match_all("/\((([^()]*|(?R))*)\)/", $strFinSearchQrep, $matches);
    
    foreach($matches[0] as $key => $value) // mod is rem
    {
        $strFinSearchQrep = str_replace($value, '', $strFinSearchQrep);
    }
    
    $arrExpSearchrep = explode(', ',$strFinSearchQrep);
    //print_r($arrExpSearchrep);
    
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
      if(strpos($value, '.'))
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
          $strWhere .= " ) ";
          
      }
      else if(strpos($value, ':'))
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
          
          if(strpos($value, '='))
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
        
       
        if($strPropValWhere != '')
            $strPropValWhere .= " or ";
        if($strPT != '')
        $arrPropKeyVal = explode($strPT,$value);
        $strPropValWhere .= " ( `OperatorType` = '".$arrPropertyTypeColVal[$strPT]."' and `Value` = '".trim($arrPropKeyVal[1])."' ) ";
        
        
    }
   // echo $strWhere;
   // echo $strPropValWhere;
    
   $qry="SELECT count(*) as cntKey, nre.PageId FROM neox_repentity as nre 
                INNER JOIN neox_reppropertykey as nrp ON nrp.ParentEntityId = nre.Id 
                WHERE nrp.ParentEntityId IN
                (
                  SELECT ParentEntityId FROM neox_reppropertykey WHERE ($strWhere) and Id IN 
                  (
                    SELECT KeyId FROM neox_reppropertyvalue WHERE $strPropValWhere
                  )
                  UNION
                  SELECT ParentEntityId FROM neox_reppropertykey WHERE ParentEntityId IN 
                  (
                    SELECT ParentEntityId FROM neox_reppropertykey WHERE ($strWhere) and Id IN 
                    (
                      SELECT KeyId FROM neox_reppropertyvalue WHERE $strPropValWhere
                    )
                  )
                  and PropertyType = 'ptEvent'
                  
                )
                GROUP BY nre.PageId ORDER BY cntKey DESC ";
    
    $result=  mysql_query($qry, $link);
    
    while($arrResult = mysql_fetch_array($result))
    {
      $arrURL[$arrResult['PageId']] = $arrResult['PageId']; 
    }
    //print_r($arrURL);
    //echo FULLBASE;
    //$rows = $result->fetch_assoc();
    //print_r($rows);
}
?>
<link href="<?php echo FULLBASE; ?>style/search_results.css" rel="stylesheet" type="text/css" />
<script src="<?php echo FULLBASE; ?>js/search.js"></script>
<div id="content">
    <div class="container relative">
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
    </div>


<div id="searchResults">
  <div class="searchResultContainer">
  <?php if(count($arrURL) > 0) {
    foreach($arrURL as $strVal){
    echo '<a href="">'.$strVal.'</a><br />';
    }
  } ?>
  </div>
</div>
</div>
<?php
include_once 'includes/footer.php';
?>