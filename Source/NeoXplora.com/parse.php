<?php
$news = simplexml_load_file('http://news.google.com.au/news/feeds?q&output=rss');

$feeds = array();

$i = 0;

 //1. DB CONNECTION

//    @ $db = new mysqli('internal-db.s179668.gridserver.com', 'db179668_mspkp', 'mspkp123', 'db179668_newsdb');
    @ $db = new mysqli('localhost', 'root', '', 'paul');	
   if (mysqli_connect_errno()) {

        echo 'Error: Could not connect to database.  Please try again later.';

        exit;

    } else {/* echo 'Got in'; */

    }

$cnt=1;
foreach ($news->channel->item as $item) 
{
echo "<br><b>";
//foreach((string)$item->description as $element) 
//       echo $element->src . '<br>';
	   

$doc = new DOMDocument();
@$doc->loadHTML((string)$item->description);

$tags = $doc->getElementsByTagName('img');

//foreach ($tags as $tag) {
//       $image= $tag->getAttribute('src');
//}	   
echo "</b>"	   ;
//print_r($item);
    preg_match('@src="([^"]+)"@', $item->description, $match);
    $parts = explode('<font size="-1">', $item->description);

    $feeds[$i]['title'] = (string) $item->title;
    $feeds[$i]['link'] = (string) $item->link;
echo     $feeds[$i]['image'] = $match[1];
    $feeds[$i]['site_title'] = strip_tags($parts[1]);
    $feeds[$i]['page'] = strip_tags($parts[2]);
    $feeds[$i]['pubDate'] =  (string) $item->pubDate;	
    $feeds[$i]['category'] =  (string) $item->category;		




	$query2 = "insert into `PageTBL` (title,url,body,source,image,dated,Section) values ('".$feeds[$i]['title']."','".$feeds[$i]['link']."','".$feeds[$i]['page']."','".$feeds[$i]['site_title']."','".$feeds[$i]['image']."','".$feeds[$i]['pubDate']."','".$feeds[$i]['category']."')";
	$result2 = $db->query($query2);
	$ParentID=$db->insert_id;


	$query2 = "insert into `ClusterTBL` (parentPageID,Section) values ('".$ParentID."','".$feeds[$i]['category']."')";
	$result2 = $db->query($query2);
	$clusterID=$db->insert_id;
	if ($ParentID!=0){

	$query2 = "insert into `ClusterPagesTBL` (clusterID,pageID,Summary) values ('".$clusterID."','".$ParentID."','')";

	$result2 = $db->query($query2);


/*if ($cnt<4 ){
	$type=1;
	$typetext="Latest";
	}
else if ($cnt<7 ){
	$type=2;
	$typetext="Unique";	
	}
else {	$type=3;
	$typetext="Opinion";	
}
*/
if ($cnt<4 ){
	$type=1;
	$typetext="Latest";
	}
else if ($cnt<7 ){
	$type=2;
	$typetext="Unique";	
	}
else if ($cnt<10 ){	$type=3;
	$typetext="Detailed";	
}
else if ($cnt<13 ){	$type=4;
	$typetext="Concise";	
}
else if ($cnt<16 ){	$type=5;
	$typetext="Opinion";	
}
else if ($cnt<19 ){	$type=6;
	$typetext="Blog";	
}
else if ($cnt<22 ){	$type=6;
	$typetext="Background";	
}




$cnt++;

	$query2 = "insert into `HighlitesTBL` (PageID,HighliteTypeID,HighliteText) values ('".$ParentID."','".$type."','')";

	$result2 = $db->query($query2);

	
	
		  	 

	
}


$newtext1= strip_tags($item->description,"<a><font>");

$newtext1= str_replace('<font size="-1" color="#6f6f6f">',"#",$newtext1);
$newtext1= str_replace("</font>"," </font>|",$newtext1);

$newtext2= strip_tags($newtext1,"<a>|");

$val = explode('|',$newtext2);

//print_r($val);

for ($k=5;$k<count($val);$k=$k+2)
{
$str= strip_tags($val[$k]);
//echo "<br>";
$pattern = '!(https?://[^\s]+)!'; // refine this for better/more specific results    
if (preg_match_all($pattern, $val[$k], $matches)) 
{      
list(, $links) = ($matches);      
//print_r($links);  

}
//echo "<br>";
parse_str(str_replace('amp;','',$links[0]), $query);
 $links[0]= @str_replace("\\","",substr($query['url'], 0, strpos($query['url'], '"')));

if (empty($str)) continue;
$val2 = explode('#',$str);
if (empty($val2[0]) || empty($val2[1]) || empty($links[0])) continue;
//print_r($val2);
//echo "<br>";
	$query2 = "insert into `PageTBL` (title,url,body,source,dated,Section) values ('".$val2[0]."','".$links[0]."','','".$val2[1]."','".$feeds[$i]['pubDate']."','".$feeds[$i]['category']."')";

	$result2 = $db->query($query2);

	$pageID=$db->insert_id;

	$query2 = "insert into `ClusterPagesTBL` (clusterID,pageID,Summary) values ('".$clusterID."','".$pageID."','')";

	$result2 = $db->query($query2);



if ($cnt<4 ){
	$type=1;
	$typetext="Latest";
	}
else if ($cnt<7 ){
	$type=2;
	$typetext="Unique";	
	}
else {	$type=3;
	$typetext="Opinion";	
}
$cnt++;

	$query2 = "insert into `HighlitesTBL` (PageID,HighliteTypeID,HighliteText) values ('".$pageID."','".$type."','')";

	$result2 = $db->query($query2);



}




	

    $i++;
}

echo '<pre>';
//print_r($feeds);
echo "done";
echo '</pre>';
?>