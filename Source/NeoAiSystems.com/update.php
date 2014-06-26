<html>
<input type="submit" value="Update Sources" onclick="window.location='update.php?action=update';" />

<?php
  $TheAction = isset($_GET["action"]) ? $_GET["action"] : 1;
  switch ($TheAction) {
    case 'update':
      exec('svn up https://svn.skyproject.ro:8443/svn/Source/CustomProducts/2014/PaulKp/WebSite/html . --non-interactive --username neoai --password neOp3%f --trust-server-cert');
      exec('svn up https://svn.skyproject.ro:8443/svn/Source/Products/SkyFramework/trunk/SkyPhp SkpFW --non-interactive --username neoai --password neOp3%f --trust-server-cert');
	  
      echo "Sources updated";
      break;
    default:
      break;
  }
?>
  
</html>