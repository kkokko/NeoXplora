
<?php 
include 'tpl/header.tpl.php';

$page = '';
$template = '';
if(isset($_REQUEST['page'])) 
{
	$page = $_REQUEST['page'];
	if(isset($_REQUEST['tpl'])) 
	{
		$template = $_REQUEST['tpl'];
	}
	else 
	{
		die('404: Not found');
	}
}
include 'tpl/'.$page.'/'.$template.'.php';

?>

	<p>&nbsp;</p><p>&nbsp;</p>
	
	<a href="admin.php?page=admin&tpl=dash">Admin</a>
<?php 

include 'tpl/footer.tpl.php';
?>
