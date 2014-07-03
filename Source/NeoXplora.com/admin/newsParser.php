<?php 
include_once '../includes/config.php';
$pagetitle = "News parser";
include_once '../includes/header.php';
include_once '../includes/incArticleFunctions.php';

if (!UserSessionManager::LoggedIn()) {
        header('location: ' . FULLBASE . 'index.php');
        die;
    }

global $db;

$rowsPerPage = 20;
$page = (isset($_REQUEST['change_page'])) ? $_REQUEST['change_page'] : 0;
$startRec = $page * $rowsPerPage;
$sortBy = (isset($_REQUEST['sortBy'])) ? $_REQUEST['sortBy'] : 0;
$sortOrder = (isset($_REQUEST['sortOrder'])) ? $_REQUEST['sortOrder'] : 0;

$pageStr = "&change_page=" . sql_safe($page);
$orderStr =  "&sortBy=" . sql_safe($sortBy) . "&sortOrder=" . sql_safe($sortOrder);

if($sortBy == "0")//if sorting strings are not set. sorty by first name and ascending
	$sortBy = "parserId";
if($sortOrder == "0")
	$sortOrder = "DESC";
	
$qryCnt="SELECT count(parserId) as rowCount FROM newsParsers";
	
$qry= "SELECT *
	FROM newsParsers";

$qry .= " ORDER BY " . sql_safe($sortBy) . " " . sql_safe($sortOrder) . " " ;

$resultuser = dbQuery($db,$qry);

$resRowCount = dbQuery($db,$qryCnt);
$rowCount = dbFetchObject($resRowCount);

		
?>
<script language="text/javascript" src="<?php echo FULLBASE; ?>admin/templates/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo FULLBASE; ?>admin/templates/datatable.css" />
<link rel="stylesheet" type="text/css" href="<?php echo FULLBASE; ?>admin/templates/datatableui.css" />
<style>
.content {
	width:70%;
	float:left;
	margin-top:20px;
	margin-left:20px;
	font-family: Verdana,Geneva,sans-serif;
}
</style>
<div class="content">
    <div class="container relative">
        
  
	<div class="articleTable" >
		 
		<table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-bottom:1px solid #eee;margin-top:3px;">
			<tr>
				<td colspan="6">
                	<div class="floatleft">
                    	<div class="floatleft">
                        	
                        </div>
                    </div>
                    <div class="clearleft"></div>
                    <div style="margin-bottom:5px;">
					<a title="Edit"  href="<?php echo FULLBASE; ?>admin/editNewsParser.php"><img src="images/add.gif" hspace="1" border="0"> Add new parser</a>
                    </div>
				</td>
				
			</tr>
			
        </table>
         
		<style type="text/css">
			#example_wrapper .fg-toolbar { font-size: 0.8em }
			#theme_links span { float: left; padding: 2px 10px; }
			#example_wrapper { -webkit-box-shadow: 2px 2px 6px #666; box-shadow: 2px 2px 6px #666; border-radius: 5px; }
			#example tbody {
				border-left: 1px solid #AAA;
				border-right: 1px solid #AAA;
			}
			#example thead th:first-child { border-left: 1px solid #AAA; }
			#example thead th:last-child { border-right: 1px solid #AAA; }
		</style>
        <div id="example_wrapper" class="dataTables_wrapper">
      	<table width="100%" class="display" id="dt3">
        	<thead>
                <tr>
                    <th>Parser Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
			<?php 
				$bgcolor = "Odd";
				if(dbGetRows($resultuser))
				{
					while($q = dbFetchObject($resultuser))
					{
						$bgcolor = ($bgcolor == "Even") ? "Odd" : "Even";
						?>
						
						<script language="JavaScript" type="text/javascript">
							function delete_parser(parserId){
								window.location = ('<?php echo FULLBASE; ?>admin/editNewsParser.php?action=deleteParser&parserId=' + parserId);
							}
						</script>
							<tr>
								<td height="20px" width="400px">
									<strong><?php echo $q->parserName ?></strong>
								</td>
								
								
								<td align="right" nowrap>
									<a title="Edit"  href="<?php echo FULLBASE; ?>admin/editNewsParser.php?action=edit&parserId=<?php echo $q->parserId; ?>"><img src="images/edit.gif" hspace="1" border="0"></a>
												
									
									<a href="#" title="Delete" onClick="if(confirm('Are you sure you want to delete parser <?php echo htmlentities($q->parserName)?>'))  delete_parser(<?php echo $q->parserId ?>);"><img src="images/delete.gif" border="0"></a>
									
								</td>
							</tr>
						<?php
					}
				}
			?>
        	</tbody>
		</table>
        </div>
		<script> /* SCRIPTS */
          $(function () {
            $('#dt3').dataTable( {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
				"aaSorting": [[ 2, "desc" ]]
            }); /* For the data tables */
          });
        </script>
	</div>
    </div>
</div>
<?php
include_once '../includes/footer.php';
?>