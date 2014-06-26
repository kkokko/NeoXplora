<?php
include_once 'includes/config.php';
$pagetitle = "News";
include_once 'includes/header.php';

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".bg,.bgup").click(function(e){
//    $(".bg-ch").toggle();
//	alert(e.target.id);
	var str=e.target.id	
	var array = str.split('-');		
//	alert(array[1]);
    $(".open-"+array[1]).toggle();	
    $("#p-"+array[1]).toggle();	
	$(this).closest('.box2').children('.description').toggleClass('largdesc');
	$(this).closest('.box2').children('.description').children('.bg-ch').toggleClass('bg-chhide'); //
	$(this).toggleClass('up');
 return false;	
  });
});
</script>


<div id="content">
    <div class="container relative">

<style>
body{overflow-x:hidden;}

.content {
	width:78%;
	float:right;
	margin-top:20px;
	font-family: Verdana,Geneva,sans-serif;
}
.logo {
	background: url(../images/NeoNewsLOGO.png) no-repeat;
	width:208px;
	height:60px;
	overflow:hidden;
	float:left;
	margin:0 0 0 0;
	text-indent:-9999px;
}
.logo > a {
	height:100%;
	display:block;
	font-family: Verdana,Geneva,sans-serif;
}
form {
	margin:0 0 40px;
	font-family: Verdana,Geneva,sans-serif;
}
/*.search2 {
	border: 0 none;
	margin: 10px 0 0 21px;
	padding: 7px;
	width: 50%;
	font-family: Verdana,Geneva,sans-serif;
}
*/
.search2 {
border: 0 none;
margin: 15px 0 0 21px;
padding: 6px 7px 7px 7px;
display: inline-block;
width: 43%;
font-size:16px; 
color:#717171; 
font-family: Verdana,Geneva,sans-serif;
}

.btn {
display: inline-block;
padding: 0px 0 0 10px;
margin: 0px 0 0;
vertical-align: bottom;
}
/*
.btn {
	display: inline-block;
	padding: 0 0 0 10px;
}
*/
.btn #submit {
	background: none repeat scroll 0 0 #34669A;
	border: 0 none;
	color: #FFFFFF;
	cursor: pointer;
	font-size: 20px;
	margin: 0;
	padding: 1px 16px 3px 13px;
	text-align: center;
	font-family: Verdana,Geneva,sans-serif;
}
.btn #submit:hover {
	background:#24486d;
}
.place-holder {
	text-align: right;
	width: 738px;
	margin-left:95px;
	
}
.sidebar {
	float:left;
	width:19%;
	margin-top:20px;
	font-family: Verdana,Geneva,sans-serif;
}
.sidebar-nav {
	list-style:none;
	margin:0;
	padding:0;
	width:50%;
	font-family: Verdana,Geneva,sans-serif;
}
.sidebar-nav li {
	padding: 0 4px 1px;
	font-family: Verdana,Geneva,sans-serif;
}
.sidebar-nav li a {
	color:#222;
	font-family: Verdana,Geneva,sans-serif;
}
.sidebar-nav .first {
	background:#222;
	padding:2px 10px;
	color:#fff;
	margin:0 0px 5px;
}
.sidebar-nav .last {
	color:#8d8d8d;
	font-family: Verdana,Geneva,sans-serif;
	font-size:14px;
}
.box {
/*	margin: 16px 75px 29px 6px;*/
	margin: 16px 75px 4px 6px;
	overflow: hidden;
	padding: 0 3px;
}
.photo p {
	text-align:left;
	color:#557ca4;
	font-family: Verdana,Geneva,sans-serif;
}
.links {
	list-style:none;
	margin:0;
	padding:0;
	font-family: Verdana,Geneva,sans-serif;
}
.links li {
	padding:0 0 10px;
	color:#557ca4;
	font-family: Verdana,Geneva,sans-serif;
}
.links li a {
	color:#557ca4;
	font-family: Verdana,Geneva,sans-serif;
/*	font-size:10px;	*/
}
.box p {
	vertical-align: text-top;
	margin:0;
	font-family: Verdana,Geneva,sans-serif;
/*	font-size:10px;	*/
}
.description {
	width:394px;
	font-family: Verdana,Geneva,sans-serif;
/*	font-size:10px;*/
 
}
.bg-box {
	margin: 0 0 0 0;
}
.description h2 {
	margin:0 0 10px;
	color:#666;
}
.description p {
	margin:0;
	font-family: Verdana,Geneva,sans-serif;	
	font-size: 14px;
}
.titletext {
    font-weight: bold;
	color: #666666;
/*	font-size:12px;*/
   font-size: medium;
 	
}
.content-holder {
	width:930px;
}
.short_des {

width:92px;

	margin:0;
	color:#999;
/*	font-weight:bold;*/
font-family: Verdana,Geneva,sans-serif;
text-transform:uppercase;

}
.photo img {
	margin:0 0 5px;
}

.pull-left {
	float:left;
	margin:0 10px 0 10px;
}
.description p span {
	/*color:#999;*/
font-family: Verdana,Geneva,sans-serif;	
font-size:14px;
padding-right:5px;
text-transform:uppercase;
}
.description p.bg-ch {
	color:#33659A;
font-family: Verdana,Geneva,sans-serif;	
font-size:14px;
}

.description p.bg-ch span {
	color:#999;
font-family: Verdana,Geneva,sans-serif;	
font-size:14px;
padding-right:5px;
text-transform:uppercase;
}


 .bg {
	background-image: url(../images/arows.png) ;
	background-repeat:no-repeat;
	background-origin:padding-box;
	background-position:0 0;
	/*margin: 0 8px;*/
	padding: 0 25px 0 0;
	overflow:hidden;
	display:inline-block;
	text-indent:-9999px;
	float:left;
}
/* .bgup {
	background: url(../images/up.png) no-repeat right center;
	margin: 0 8px;
	padding: 0 43px 0 0;
	overflow:hidden;
	display:inline-block;
	text-indent:-9999px;
}*/

.bg.up{
  background-position: 0 -17px;
}

.bgup {
background: url(../images/arows.png) no-repeat 0px -16px;
margin: 0 -40px 0;
padding: 0 82px 0 0;
overflow: hidden;
/* width: 18px; */
display: inline-block;
text-indent: -9999px;
}
.bgup.up {
  background-position: 0 0px;
}
 .btn-re {
   
    text-align: center;
    width: 600px;
	
}
.btn-bg{
	background:#999;
	color:#fff;
	padding:8px 22px 8px 22px;
}


.box2 {
	margin: 16px 18px 0px 0px;
	overflow: hidden;
	padding: 0 3px;
	width:900px;
	padding-bottom:8px;
}

.largdesc {
	width:676px;
}

.bg-chhide
{
display:none;
} 

</style>
    <div class="container relative">
	<div class="content"> <strong class="logo"><a href="#">logo</a></strong>
			<form>
				<input type="text" id="search2" class="search2" value="Coming July 2014" />
				<div class="search_bar btn">
					<input type="submit" id="submit" value="" />
				</div>
			</form>
			<!--<div class="place-holder"> <img src="images/place-holder.jpg" width="730" height="90"   alt="" /> </div>-->
			<div class="content-holder">



<?php 
// empty page for PROD site
if(ENV == 'prod'){
  ?>      
      </div>
      </div>
      </div>          
      </div>
    </div>
    </div>
</div>  
  <?php 
  include_once 'includes/footer.php';
  exit;
} 
?>

<?php 
 //1. DB CONNECTION

   @ $db = new mysqli(HOST, DBU, DBPASS, DB);
   if (mysqli_connect_errno()) {

        echo 'Error: Could not connect to database.  Please try again later.';

        exit;

    } else {/* echo 'Got in'; */

    }
//$qry="SELECT `HighlitesTBL`.*, `HighliteTypesTBL`.HighliteType, `PageTBL`.`title` ,  `PageTBL`.`url`,  `PageTBL`.`body`, `PageTBL`.`source`    FROM `HighlitesTBL` , `HighliteTypesTBL` ,`PageTBL` where `HighliteTypesTBL`.HighliteTypesID = `HighlitesTBL`.highliteTypeID and `PageTBL`.pageID = `HighlitesTBL`.pageID ";	
$qry="SELECT `highlitestbl`.*, `highlitetypestbl`.HighliteType, `pagetbl`.`title` ,  `pagetbl`.`url`,  `pagetbl`.`body`, `pagetbl`.`source`,`pagetbl`.`image`    FROM `highlitestbl` , `highlitetypestbl` ,`pagetbl` where `highlitetypestbl`.HighliteTypesID = `highlitestbl`.highliteTypeID and `pagetbl`.pageID = `highlitestbl`.pageID and `pagetbl`.pageID in (select pageID from clusterpagestbl where clusterID='1')";

$result=  $db->query($qry);
if(!$result){
  exit;
}
$rows = $result->fetch_assoc();
$id=$rows['highliteTypeID'];
$pid=$rows['pageID'] ;
$tit=explode('-',$rows['title']);


$shortsource="SELECT `highlitetypestbl`.HighliteType ,`pagetbl`.source, `pagetbl`.url FROM `highlitestbl`, `highlitetypestbl`, `pagetbl`
where `highlitetypestbl`.`HighliteTypesID`=`highlitestbl`.HighliteTypeID and `pagetbl`.pageID=`highlitestbl`.pageID and pagetbl.pageID in (SELECT pageID
FROM `clusterpagestbl` where  `clusterpagestbl`.`clusterID`='1' )";

$shortsourceresult=  $db->query($shortsource);
$srcshort="";
while ($rws = $shortsourceresult->fetch_assoc())
{
$srcshort.="<span>".$rws["HighliteType"]."</span>". $rws["source"]."&nbsp;";
}


?>     
			<div class="box2">
                <a id="btntoggle-<?php echo $rows['pageID']  ?>" class="bg btntoggle up" href="">link</a>
					<div  class="short_des pull-left open-<?php echo $rows['pageID']  ?>">
						<p><?php echo $rows['HighliteType']  ?></p>
					</div>
					<div class="description pull-left" >
						<a href="<?php echo $rows['url']  ?>"  target="_blank"><span class="titletext"><?php echo $tit[0];//$rows['title']  ?></span></a>
						<p><?php echo $rows['body']  ?></p>
						<p class="bg-ch bg-chhide"> <?php echo $srcshort; ?></p>
					</div>
					<div class="pull-left open-<?php echo $rows['pageID']  ?>" style="width:150px">
						<span><a href="<?php echo $rows['url']  ?>" target="_blank"><span class="dpost"><?php echo $rows['source']  ?></span></a></span><br />

						<?php 
						$img=$rows['image'];
                        $rows = $result->fetch_assoc();
                        while ($id==$rows['highliteTypeID']) { ?>
                        <span><a href="<?php echo $rows['url']  ?>" target="_blank"><span class="dpost"><?php echo $rows['source']  ?></span></a></span><br />
                        <?php 
                        $rows = $result->fetch_assoc();
                         } ?>                        

					</div>
					<div class="photo pull-left"> <img src="<?php echo $img;//$rows['image']  ?>" width="100" alt="" />
						<!-- p>Washington post</p -->
					</div>                    
				</div>
<!-- Session -->           
<?php 

$qry="SELECT `highlitestbl`.*, `highlitetypestbl`.HighliteType, `pagetbl`.`title` ,  `pagetbl`.`url`,  `pagetbl`.`body`, `pagetbl`.`source`    FROM `HighlitesTBL` , `highlitetypestbl` ,`pagetbl` where `highlitetypestbl`.HighliteTypesID = `highlitestbl`.highliteTypeID and `pagetbl`.pageID = `highlitestbl`.pageID "."  and `highlitetypestbl`.HighliteTypesID > 1 and `pagetbl`.pageID in (select pageID from clusterpagestbl where clusterID='1')";	
$result=  $db->query($qry);
$rows = $result->fetch_assoc();
$id=$rows['highliteTypeID'];
?>

<?php 
$brk=1;
$shortdec='';
while ($id==$rows['highliteTypeID']) {   
if ($brk==4) break;
if ($brk++==2) echo "";
$sorce=preg_split('/[\s,]+/', $rows['source'] , 3);


?>      
				<div class="box bg-box open-<?php echo $pid  ?>">
					<div  class="short_des pull-left" style="margin-left: 34px;">
						<p><?php if ($shortdec!=$rows['HighliteType'])echo $shortdec=$rows['HighliteType']; else echo "&nbsp;"  ?></p>
					</div>
					<div class="description pull-left"  >
						<p style="float:left;"><?php echo trim($rows['title'])  ?><span style="margin-left:3px;"><a href="<?php echo $rows['url']  ?>"  target="_blank"><span class="dpost"><?php echo trim($rows['source']);  ?></span></a></span></p>
					</div>
				</div>
<?php $rows = $result->fetch_assoc();
$id=$rows['highliteTypeID'];  } ?>  
<!-- End Session -->      

<?php 

$qry="SELECT `HighlitesTBL`.*, `HighliteTypesTBL`.HighliteType, `PageTBL`.`title` ,  `PageTBL`.`url`,  `PageTBL`.`body`, `PageTBL`.`source`    FROM `HighlitesTBL` , `HighliteTypesTBL` ,`PageTBL` where `HighliteTypesTBL`.HighliteTypesID = `HighlitesTBL`.highliteTypeID and `PageTBL`.pageID = `HighlitesTBL`.pageID  and `HighliteTypesTBL`.HighliteTypesID > 2   group by `HighliteTypesTBL`.HighliteTypesID and `pagetbl`.pageID in (select pageID from clusterpagestbl where clusterID='1')";	
$result=  $db->query($qry);
while ($rows = $result->fetch_assoc()) {
$sorce=preg_split('/[\s,]+/', $rows['source'] , 3);
?>
        
  
<!-- Session -->                
				<div class="box bg-box open-<?php echo $pid  ?>">
					<div  class="short_des pull-left" style="margin-left: 34px;">
						<p><?php echo $rows['HighliteType']  ?></p>
					</div>
					<div class="description pull-left" >
						<p style="float:left;"><?php echo trim($rows['title'])  ?><span style="margin-left:3px;"><a href="<?php echo $rows['url']  ?>"  target="_blank"><span class="dpost"><?php echo trim($rows['source']);  ?></span></a></span></p>
					</div>
				</div>            
<?php } ?>                




<?php 

//$loop=true;
//$position=3;

$qry=' SELECT * FROM `clustertbl` where parentPageID!=0 LIMIT 1 , 30';
$result=  $db->query($qry);
while ($rows = $result->fetch_assoc()) 
{
	
	$startpoint=$rows['parentPageID'];
 	$clusterid=$rows['clusterID'];

 	$qry2="select * from clusterpagestbl where clusterID='$clusterid'";
	$result2=  $db->query($qry2);
	$chk=1;
	$potions="";
	$shortdec='';	
	while ($rows2 = $result2->fetch_assoc()) 
	{
		$pagid=$rows2['pageID'];

$qry3="SELECT `highlitestbl`.*, `highlitetypestbl`.HighliteType, `pagetbl`.`title` ,  `pagetbl`.`url`,  `pagetbl`.`body`, `pagetbl`.`source`,`pagetbl`.`image`    FROM `highlitestbl` , `highlitetypestbl` ,`pagetbl` where `highlitetypestbl`.HighliteTypesID = `highlitestbl`.highliteTypeID and `pagetbl`.pageID = `highlitestbl`.pageID  and `pagetbl`.pageID = '$pagid'";

$result3=  $db->query($qry3);
$rows3 = $result3->fetch_assoc();
$id=$rows['highliteTypeID'];

//		$qry3="SELECT * FROM `pagetbl` where pageID='$pagid'";
//		$result3=  $db->query($qry3);
//		$rows3 = $result3->fetch_assoc();

$pid=$pagid;
if ($chk==1)
$refID=$pagid;


$img= $rows3['image'];

if (empty($rows3['image']))
$img= 'images/noimage.jpg';



//	$qry4="SELECT * FROM highlitestbl as a,   highlitetypestbl as b where a.highliteTypeID=b.HighliteTypesID  and a.pageID='$pagid' ";
//	$result4=  $db->query($qry4);
//	$rows4 = $result4->fetch_assoc();


if ($chk==1){
$tit=explode('-',$rows3['title']);


$shortsource="SELECT `highlitetypestbl`.HighliteType ,`pagetbl`.source, `pagetbl`.url FROM `highlitestbl`, `highlitetypestbl`, `pagetbl`
where `highlitetypestbl`.`HighliteTypesID`=`highlitestbl`.HighliteTypeID and `pagetbl`.pageID=`highlitestbl`.pageID and pagetbl.pageID in (SELECT pageID
FROM `clusterpagestbl` where  `clusterpagestbl`.`clusterID`='$clusterid' )";

$shortsourceresult=  $db->query($shortsource);
$srcshort="";
while ($rws = $shortsourceresult->fetch_assoc())
{
$srcshort.="<span>".$rws["HighliteType"]."</span>". $rws["source"]."&nbsp;";
}



?>

			<div class="box2">
                <a id="btntoggle-<?php echo $refID  ?>" class="bg btntoggle " href="">link</a>
					<div  class="short_des pull-left open-<?php echo $refID  ?>" style="display: none;">
						<p><?php echo $rows3['HighliteType']  ?></p>
					</div>
					<div class="description pull-left largdesc" >
						<a href="<?php echo $rows3['url'] ?>"  target="_blank"><span class="titletext"><?php echo $tit[0];//$rows3['title']  ?></span></a>
						<p><?php echo $rows3['body']  ?></p>
						<p class="bg-ch  bg-chhide"> <?php echo $srcshort; ?></p>                        
					</div>
					<div class="pull-left open-<?php echo $refID  ?>" style="width:150px; display:none;">
						<span><a href="<?php echo $rows3['url']  ?>" target="_blank"><span class="dpost"><?php echo $rows3['source']  ?></span></a></span><br />

						<?php 
                        $rows3 = $result3->fetch_assoc();
                        while ($id==$rows3['highliteTypeID']) { ?>
                        <span><a href="<?php echo $rows3['url']  ?>" target="_blank"><span class="dpost"><?php echo $rows3['source']  ?></span></a></span><br />
                        <?php 
                        $rows3 = $result3->fetch_assoc();
						if (!$row3)
							break;
                         } ?>                        

					</div>
					<div class="photo pull-left"> <img src="<?php echo $img ?>" width="100" alt="" />
						<!-- p>Washington post</p -->
					</div>                    
				</div>
<?php } ?>


<!-- Session -->           
<?php 

if ($chk++>1){


  $qry5="SELECT `highlitestbl`.*, `highlitetypestbl`.HighliteType, `pagetbl`.`title` ,  `pagetbl`.`url`,  `pagetbl`.`body`, `pagetbl`.`source`, `pagetbl`.`image`    FROM `HighlitesTBL` , `highlitetypestbl` ,`pagetbl` where `highlitetypestbl`.HighliteTypesID = `highlitestbl`.highliteTypeID and `pagetbl`.pageID = `highlitestbl`.pageID "."  and `highlitetypestbl`.HighliteTypesID > 1 and `pagetbl`.pageID='$pid'";	
$result5=  $db->query($qry5);
$rows5 = $result5->fetch_assoc();
$id=$rows5['highliteTypeID'];
?>

<?php 
$brk=1;


while ($id==$rows5['highliteTypeID']) {   
if ($brk==4) break;
if ($brk++==2) echo "";
$sorce=preg_split('/[\s,]+/', $rows5['source'] , 3);

if (!empty($rows5['title']))
{
?>      
				<div class="box bg-box open-<?php echo $refID  ?>" style="display: none;">
					<div  class="short_des pull-left" style="margin-left: 34px;">
						<p><?php if (trim($shortdec)!=trim($rows5['HighliteType'])) echo $shortdec=trim($rows5['HighliteType']); else echo "&nbsp;"  ?></p>
					</div>
					<div class="description pull-left"  >
						<p style="float:left;"><?php echo trim($rows5['title'])  ?><span style="margin-left:3px;"><a href="<?php echo $rows5['url']  ?>"  target="_blank"><span class="dpost"><?php echo trim($rows5['source']);  ?></span></a></span></p>
					</div>
				</div>
<?php 
}
$rows5 = $result5->fetch_assoc();
$id=$rows5['highliteTypeID'];  } ?>  
<!-- End Session -->      

<?php } 

?>


      



<?php

	}

}

 ?>


					</div>
										</div>

<div class="sidebar">
				<ul class="sidebar-nav">
					<li class="first">All</li>
					<li><a href="#">Australia</a></li>
					<li><a href="#">Word</a></li>
					<li><a href="#">Business</a></li>
					<li><a href="#">Sports</a></li>
					<li><a href="#">Entertainment</a></li>
					<li><a href="#">Health</a></li>
					<li><a href="#">Science</a></li>
					<li><a href="#">Tech</a></li>
					<li class="last">+ your interests</li>
				</ul>
			</div>                                        

				</div>


				
		
					
			</div>
		</div>

        
    </div>
</div>
        

<?php
include_once 'includes/footer.php';
?>