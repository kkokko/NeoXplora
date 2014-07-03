<?php

function addParser($objArticle)
{
	global $db;
	
	if (UserSessionManager::LoggedIn()) 
	{
		
		$query = "SELECT max(parserId) as maxItemId FROM newsParsers ";
		$result = dbQuery($db,$query);
		$articleObj = dbFetchObject($result);
		$strMaxSortIndex = $articleObj->maxItemId + 1;
			
		$qry = "INSERT INTO newsParsers 
					(
						parserName, 
						description,
						country,
						beginCueTitle, 
						endCueTitle, 
						beginCueAuthor, 
						endCueAuthor, 
						beginCueContent, 
						endCueContent, 
						beginCueDate, 
						endCueDate, 
						beginCueSection, 
						endCueSection, 
						beginCueImageURL, 
						endCueImageURL,
						beginCueImageCaption, 
						endCueImageCaption,
						beginCueImageCaption2, 
						endCueImageCaption2,
						beginCueIntro, 
						endCueIntro,
						beginCueRmExtraContent, 
						endCueRmExtraContent,
						beginCueRmExtraContent2, 
						endCueRmExtraContent2,
						beginCueRmExtraContent3, 
						endCueRmExtraContent3,
						beginCueRmExtraContent4, 
						endCueRmExtraContent4,
						sort
				) 
				VALUES ('"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->parserName))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->description))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->country))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueTitle))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueTitle))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueAuthor))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueAuthor))) ."', '"
                . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueContent))) ."', '"
                . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueContent))) ."', '"
                . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueDate))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueDate))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueSection))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueSection))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageURL))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageURL))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageCaption))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageCaption))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageCaption2))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageCaption2))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueIntro))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueIntro))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent2))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent2))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent3))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent3))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent4))) ."', '"
				. sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent4))) ."', '"
				. $strMaxSortIndex. "')";
			//echo $qry;
			dbQuery($db,$qry);
	}
}

function editParser($objArticle)
{
	global $db;
	
	if (UserSessionManager::LoggedIn()) 
	{
		$qry = "UPDATE newsParsers Set "	
					. "parserName ='" . sql_safe($objArticle->parserName) . "', "	
					. "description ='" . sql_safe($objArticle->description) . "', "
					. "country ='" . sql_safe($objArticle->country) . "', "												
					. "beginCueTitle ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueTitle))) . "', "	
					. "endCueTitle ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueTitle))) . "', "	
					. "beginCueAuthor ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueAuthor))) . "', "	
					. "endCueAuthor ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueAuthor))) . "', "	
					. "beginCueContent ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueContent))) . "', "	
					. "endCueContent ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueContent))) . "', "	
					. "beginCueDate ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueDate))) . "', "	
					. "endCueDate ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueDate))) . "', "	
					. "beginCueSection ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueSection))) . "', "	
					. "endCueSection ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueSection))) . "', "	
					. "beginCueImageURL ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageURL))) . "', "						
					. "endCueImageURL ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageURL))) . "', "	
					. "beginCueImageCaption ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageCaption))) . "', "						
					. "endCueImageCaption ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageCaption))) . "', "	
					. "beginCueImageCaption2 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueImageCaption2))) . "', "						
					. "endCueImageCaption2 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueImageCaption2))) . "', "	
					. "beginCueRmExtraContent ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent))) . "', "						
					. "endCueRmExtraContent ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent))) . "', "	
					. "beginCueRmExtraContent2 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent2))) . "', "						
					. "endCueRmExtraContent2 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent2))) . "', "	
					. "beginCueRmExtraContent3 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent3))) . "', "						
					. "endCueRmExtraContent3 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent3))) . "', "	
					. "beginCueRmExtraContent4 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueRmExtraContent4))) . "', "						
					. "endCueRmExtraContent4 ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueRmExtraContent4))) . "', "
					. "beginCueIntro ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->beginCueIntro))) . "', "						
					. "endCueIntro ='" . sql_safe(addslashes(htmlspecialchars_decode($objArticle->endCueIntro))) . "' ";				
			$qry .= " WHERE parserId ='" . sql_safe(addslashes($objArticle->parserId)) . "'";
			//echo $qry;exit;
			dbQuery($db,$qry);	
	}
	
}

function deleteParser($objArticle)
{
	global $db;
	
	if (UserSessionManager::LoggedIn()) 
	{
		$qry="DELETE FROM newsParsers WHERE parserId = '" . $objArticle->parserId ."'";
		dbQuery($db,$qry);
	}
	
}

function deleteParserURL($objArticle)
{
	global $db;
	
	if (UserSessionManager::LoggedIn()) 
	{
		$qry="DELETE FROM newsParserURL WHERE parserURLId = '" . $objArticle->parserURLId ."'";
		dbQuery($db,$qry);
	}
	
}


function addParserURL($objArticle)
{
	global $db;
	
	if (UserSessionManager::LoggedIn()) 
	{
		
		$query = "SELECT count(parserURLId) as cntParserId FROM newsParserURL 
					WHERE parserURL = '". sql_safe($objArticle->strArticleURL) ."' 
						AND parserId = '".sql_safe($objArticle->parserId)."'";
		$result = dbQuery($db,$query);
		$articleObj = dbFetchObject($result);
		
		if($articleObj->cntParserId <= 0)
		{
			$query = "SELECT max(parserURLId) as maxItemId FROM newsParserURL ";
			$result = dbQuery($db,$query);
			$articleObj = dbFetchObject($result);
			$strMaxSortIndex = $articleObj->maxItemId + 1;
				
			$qry = "INSERT INTO newsParserURL 
						(
							parserId, 
							parserURL,
							parserFlag,
							date, 
							sort
					) 
					VALUES ('"
					. sql_safe($objArticle->parserId) ."', '"
					. sql_safe($objArticle->strArticleURL) ."', '"
					. sql_safe($objArticle->strParserFlag) ."', '"
					. date('Y-m-d H:i:s') ."', '"
					. $strMaxSortIndex. "')";
				//echo $qry;
				dbQuery($db,$qry);
		}
		else
		{
			$qry = "UPDATE newsParserURL Set "	
					. "parserFlag ='" . sql_safe($objArticle->strParserFlag) . "' "	;
			$qry .= " WHERE parserId ='" . sql_safe($objArticle->parserId) . "' AND parserURL = '". sql_safe($objArticle->strArticleURL) ."'";
			//echo $qry;exit;
			dbQuery($db,$qry);	
		}
	}
}

function getParserURL($intParserId, $strParserFlag)
{
	global $db;
	
	$query = "SELECT * FROM newsParserURL 
					WHERE parserFlag = '". $strParserFlag ."' 
						AND parserId = '". $intParserId ."'";
		$result = dbQuery($db,$query);
		//$articleObj = dbFetchObject($result);
		
	return $result;
	
}

function testParser($objArticle)
{
	global $db;
	
	$arrResult = array();
	
	if(!empty($objArticle->strArticleURL))
	{
		$strURLContent =  getURLContent($objArticle->strArticleURL,0);
		//echo '<PRE>';print_r($objArticle);
		//echo $objArticle->strArticleURL.$strURLContent.'11';exit;
		$arrResult['title'] = getTextBetweenTags($strURLContent, $objArticle->beginCueTitle, $objArticle->endCueTitle);
		$arrResult['author'] = getTextBetweenTags($strURLContent, $objArticle->beginCueAuthor, $objArticle->endCueAuthor);
		//echo $objArticle->beginCueContent;
		$arrResult['content'] = getTextBetweenTags($strURLContent, $objArticle->beginCueContent, $objArticle->endCueContent);
	
		$strExtraContnent = getTextBetweenTags($strURLContent, '<div class="video ">', '</div> <!-- // .video .js-tab-content -->');
		$arrResult['content']  = str_replace($strExtraContnent, "", $arrResult['content']);
		
		$strExtraContnent = getTextBetweenTags($strURLContent, '<div class="controls fader-controls js-fader-controls">', '</div>');
		$arrResult['content']  = str_replace($strExtraContnent, "", $arrResult['content']);
		
		$arrResult['date'] = getTextBetweenTags($strURLContent, $objArticle->beginCueDate, $objArticle->endCueDate);
		$arrResult['section'] = getTextBetweenTags($strURLContent, $objArticle->beginCueSection, $objArticle->endCueSection);
		
		
		if($objArticle->beginCueRmExtraContent != '' && $objArticle->endCueRmExtraContent != '')
		{
			$arrResult['rmExtraContent'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueRmExtraContent, $objArticle->endCueRmExtraContent);
		//echo '<PRE>'.print_r($arrResult['rmExtraContent']).'om<br /><br />om';
		//exit;
			
			$arrResult['content']  = str_replace($arrResult['rmExtraContent'], "", $arrResult['content']);
			
			$arrShareLink = array('Shares','Tweets','Stumble','Email','More +');
			$arrResult['content']  = str_replace($arrShareLink, "", $arrResult['content']);
			
			//echo $arrResult['content'];exit;
		}
		else
		{
			$arrResult['rmExtraContent'] = '';
		}
		
		if($objArticle->beginCueRmExtraContent2 != '' && $objArticle->endCueRmExtraContent2 != '')
		{
			$arrResult['rmExtraContent2'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueRmExtraContent2, $objArticle->endCueRmExtraContent2);
		
		
			$arrResult['content']  = str_replace($arrResult['rmExtraContent2'], "", $arrResult['content']);
		}
		else
		{
			$arrResult['rmExtraContent2'] = '';
		}
		
		if($objArticle->beginCueRmExtraContent3 != '' && $objArticle->endCueRmExtraContent3 != '')
		{
			$arrResult['rmExtraContent3'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueRmExtraContent3, $objArticle->endCueRmExtraContent3);
						
			$arrResult['content']  = str_replace($arrResult['rmExtraContent3'], "", $arrResult['content']);
		}
		else
		{
			$arrResult['rmExtraContent3'] = '';
		}
		
		if($objArticle->beginCueRmExtraContent4 != '' && $objArticle->endCueRmExtraContent4 != '')
		{
			$arrResult['rmExtraContent4'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueRmExtraContent4, $objArticle->endCueRmExtraContent4);
				//echo '<PRE>'.print_r($arrResult['rmExtraContent4']).'om<br /><br />om';exit;		
			$arrResult['content']  = str_replace($arrResult['rmExtraContent4'], "", $arrResult['content']);
		}
		else
		{
			$arrResult['rmExtraContent4'] = '';
		}
		
		
		$arrResult['script'] = getTextBetweenTagsAll($strURLContent, '<script>', '</script>');
		$arrResult['style'] = getTextBetweenTagsAll($strURLContent, '<style type="text/css">', '</style>');
		
		$arrResult['content']  = str_replace($arrResult['script'], "", $arrResult['content']);
		$arrResult['content']  = str_replace($arrResult['style'], "", $arrResult['content']);
		
			
		$arrResult['urlContent'] = $strURLContent;
		
		$arrResult['content']  = str_replace($arrResult['title'], "", $arrResult['content']);
		$arrResult['content']  = str_replace($arrResult['author'], "", $arrResult['content']);
		$arrResult['content']  = str_replace($arrResult['date'], "", $arrResult['content']);	
		
		preg_match_all('/<img([^>]+)\>/i', $arrResult['content'], $images);
		//$arrResult['imageurl'] = getTextBetweenTags($strURLContent, $objArticle->beginCueImageURL, $objArticle->endCueImageURL);
		$arrResult['imageurl'] = $images[0];
		
		foreach ($arrResult['imageurl'] as $key => $value){
		  
		   $arrResult['content']  = str_replace($value, "", $arrResult['content']);
		}
		$arrResult['imageurl']  = str_replace('data-baseurl','src',$arrResult['imageurl']);
		$arrResult['imageurl']  = str_replace('data-src','src',$arrResult['imageurl']);
		
		if($objArticle->beginCueImageCaption != '' && $objArticle->endCueImageCaption != '')
		{
			$arrResult['imagecaption'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueImageCaption, $objArticle->endCueImageCaption);
		
			
			 $arrResult['content']  = str_replace($arrResult['imagecaption'], "", $arrResult['content']);
			 /*foreach ($arrResult['imagecaption'] as $key => $value){ echo $value;
			   $arrResult['content']  = str_replace(str_replace('itemprop="image"/>','',$value), "", $arrResult['content']);
			   $arrResult['content']  = str_replace($objArticle->beginCueImageCaption, "", $arrResult['content']);
			}
			echo $arrResult['content'];exit;*/
			
		}
		else
		{
			$arrResult['imagecaption'] = array();
		}
		
		if($objArticle->beginCueImageCaption2 != '' && $objArticle->endCueImageCaption2 != '')
		{
			$arrResult['imagecaption2'] = getTextBetweenTagsAll($strURLContent, $objArticle->beginCueImageCaption2, $objArticle->endCueImageCaption2);
		
			foreach ($arrResult['imagecaption2'] as $key => $value){
			   $arrResult['content']  = str_replace(str_replace('itemprop="image"/>','',$value), "", $arrResult['content']);
			   $arrResult['content']  = str_replace($objArticle->beginCueImageCaption2, "", $arrResult['content']);
			  
			}
				
		}
		else
		{
			$arrResult['imagecaption2'] = array();
		}
		
		
		if($objArticle->beginCueIntro != '' && $objArticle->endCueIntro != '')
		{
			$arrResult['intro'] = getTextBetweenTags($strURLContent, $objArticle->beginCueIntro, $objArticle->endCueIntro);
			
		
			$arrResult['content']  = str_replace($arrResult['intro'], "", $arrResult['content']);
		}
		else
		{
			$arrResult['intro'] = '';
		}	
		
		//echo '<PRE>';print_r($images);exit;
	}
	
	//echo '<PRE>';print_r($arrResult);exit;
	
	return $arrResult;
}

function getURLContent($strURL,$flgSleep=0)
{
	$_curl = curl_init();
	curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($_curl, CURLOPT_COOKIEFILE, '../cookiePath.txt');
	curl_setopt($_curl, CURLOPT_COOKIEJAR, '../cookiePath.txt');
	curl_setopt($_curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; InfoPath.1)');
	curl_setopt($_curl, CURLOPT_FOLLOWLOCATION, true); //new added
	curl_setopt($_curl, CURLOPT_URL, $strURL);
	curl_setopt($_curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
	curl_setopt($_curl, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
	$rtn = curl_exec( $_curl );	
	
	if($rtn == false)
	{
		if($flgSleep == 0)
		{
			//echo "om";
			curl_close($_curl);
			sleep(10);
			//echo $strURL.'kk';
			return getURLContent($strURL, 1);
		}
		else
		{
			//echo curl_error($_curl);exit;
			return $rtn;
		}
	}
	else
	{
	
		curl_close($_curl);
		
		return $rtn;
	}
}

function getURLContent1($strURL)
{

$_h = curl_init();
curl_setopt($_h, CURLOPT_HEADER, 1);
curl_setopt($_h, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($_h, CURLOPT_HTTPGET, 1);
curl_setopt($_h, CURLOPT_URL, $strURL );
curl_setopt($_h, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2 );

var_dump(curl_exec($_h));
var_dump(curl_getinfo($_h));
var_dump(curl_error($_h));
}
function getTextBetween($content,$start,$end)
{
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function getTextBetweenTags($string, $startTag, $endTag)
{
	/*echo $pattern = "@<$startTag>(.*?)<\/$endTag>@i";
	preg_match_all($pattern, $string, $matches);*/
  $delimiter = '#';
  $regex = $delimiter . preg_quote(stripslashes(htmlspecialchars_decode($startTag)),'/')
                    . '(.*?)' 
                    . preg_quote(stripslashes(htmlspecialchars_decode($endTag)),'/') 
                    . $delimiter 
                    . 's';
	/*echo  $regex = $delimiter . $startTag 
                    . '(.*?)' 
                    . $endTag 
                    . $delimiter 
                    . 's';*/
    preg_match($regex,$string,$matches);
	
	//echo '<PRE>';print_r($matches);
	return $matches[1];
}

function getTextBetweenTagsAll($string, $startTag, $endTag)
{
	/*echo $pattern = "@<$startTag>(.*?)<\/$endTag>@i";
	preg_match_all($pattern, $string, $matches);*/
  $delimiter = '#';
  $regex = $delimiter . preg_quote(stripslashes(htmlspecialchars_decode($startTag)),'/')
                    . '(.*?)' 
                    . preg_quote(stripslashes(htmlspecialchars_decode($endTag)),'/') 
                    . $delimiter 
                    . 's';
	/*echo  $regex = $delimiter . $startTag 
                    . '(.*?)' 
                    . $endTag 
                    . $delimiter 
                    . 's';*/
    preg_match_all($regex,$string,$matches);
	
	//echo '<PRE>';print_r($matches);
	return $matches[0];
}

function countryDropDown($country="", $name = "country")
{
	$countries = array("AF" => "Afghanistan",
				"AX" => "Åland Islands",
				"AL" => "Albania",
				"DZ" => "Algeria",
				"AS" => "American Samoa",
				"AD" => "Andorra",
				"AO" => "Angola",
				"AI" => "Anguilla",
				"AQ" => "Antarctica",
				"AG" => "Antigua and Barbuda",
				"AR" => "Argentina",
				"AM" => "Armenia",
				"AW" => "Aruba",
				"AU" => "Australia",
				"AT" => "Austria",
				"AZ" => "Azerbaijan",
				"BS" => "Bahamas",
				"BH" => "Bahrain",
				"BD" => "Bangladesh",
				"BB" => "Barbados",
				"BY" => "Belarus",
				"BE" => "Belgium",
				"BZ" => "Belize",
				"BJ" => "Benin",
				"BM" => "Bermuda",
				"BT" => "Bhutan",
				"BO" => "Bolivia",
				"BA" => "Bosnia and Herzegovina",
				"BW" => "Botswana",
				"BV" => "Bouvet Island",
				"BR" => "Brazil",
				"IO" => "British Indian Ocean Territory",
				"BN" => "Brunei Darussalam",
				"BG" => "Bulgaria",
				"BF" => "Burkina Faso",
				"BI" => "Burundi",
				"KH" => "Cambodia",
				"CM" => "Cameroon",
				"CA" => "Canada",
				"CV" => "Cape Verde",
				"KY" => "Cayman Islands",
				"CF" => "Central African Republic",
				"TD" => "Chad",
				"CL" => "Chile",
				"CN" => "China",
				"CX" => "Christmas Island",
				"CC" => "Cocos (Keeling) Islands",
				"CO" => "Colombia",
				"KM" => "Comoros",
				"CG" => "Congo",
				"CD" => "Congo, The Democratic Republic of The",
				"CK" => "Cook Islands",
				"CR" => "Costa Rica",
				"CI" => "Cote D'ivoire",
				"HR" => "Croatia",
				"CU" => "Cuba",
				"CY" => "Cyprus",
				"CZ" => "Czech Republic",
				"DK" => "Denmark",
				"DJ" => "Djibouti",
				"DM" => "Dominica",
				"DO" => "Dominican Republic",
				"EC" => "Ecuador",
				"EG" => "Egypt",
				"SV" => "El Salvador",
				"GQ" => "Equatorial Guinea",
				"ER" => "Eritrea",
				"EE" => "Estonia",
				"ET" => "Ethiopia",
				"FK" => "Falkland Islands (Malvinas)",
				"FO" => "Faroe Islands",
				"FJ" => "Fiji",
				"FI" => "Finland",
				"FR" => "France",
				"GF" => "French Guiana",
				"PF" => "French Polynesia",
				"TF" => "French Southern Territories",
				"GA" => "Gabon",
				"GM" => "Gambia",
				"GE" => "Georgia",
				"DE" => "Germany",
				"GH" => "Ghana",
				"GI" => "Gibraltar",
				"GR" => "Greece",
				"GL" => "Greenland",
				"GD" => "Grenada",
				"GP" => "Guadeloupe",
				"GU" => "Guam",
				"GT" => "Guatemala",
				"GG" => "Guernsey",
				"GN" => "Guinea",
				"GW" => "Guinea-bissau",
				"GY" => "Guyana",
				"HT" => "Haiti",
				"HM" => "Heard Island and Mcdonald Islands",
				"VA" => "Holy See (Vatican City State)",
				"HN" => "Honduras",
				"HK" => "Hong Kong",
				"HU" => "Hungary",
				"IS" => "Iceland",
				"IN" => "India",
				"ID" => "Indonesia",
				"IR" => "Iran, Islamic Republic of",
				"IQ" => "Iraq",
				"IE" => "Ireland",
				"IM" => "Isle of Man",
				"IL" => "Israel",
				"IT" => "Italy",
				"JM" => "Jamaica",
				"JP" => "Japan",
				"JE" => "Jersey",
				"JO" => "Jordan",
				"KZ" => "Kazakhstan",
				"KE" => "Kenya",
				"KI" => "Kiribati",
				"KP" => "Korea, Democratic People's Republic of",
				"KR" => "Korea, Republic of",
				"KW" => "Kuwait",
				"KG" => "Kyrgyzstan",
				"LA" => "Lao People's Democratic Republic",
				"LV" => "Latvia",
				"LB" => "Lebanon",
				"LS" => "Lesotho",
				"LR" => "Liberia",
				"LY" => "Libyan Arab Jamahiriya",
				"LI" => "Liechtenstein",
				"LT" => "Lithuania",
				"LU" => "Luxembourg",
				"MO" => "Macao",
				"MK" => "Macedonia, The Former Yugoslav Republic of",
				"MG" => "Madagascar",
				"MW" => "Malawi",
				"MY" => "Malaysia",
				"MV" => "Maldives",
				"ML" => "Mali",
				"MT" => "Malta",
				"MH" => "Marshall Islands",
				"MQ" => "Martinique",
				"MR" => "Mauritania",
				"MU" => "Mauritius",
				"YT" => "Mayotte",
				"MX" => "Mexico",
				"FM" => "Micronesia, Federated States of",
				"MD" => "Moldova, Republic of",
				"MC" => "Monaco",
				"MN" => "Mongolia",
				"ME" => "Montenegro",
				"MS" => "Montserrat",
				"MA" => "Morocco",
				"MZ" => "Mozambique",
				"MM" => "Myanmar",
				"NA" => "Namibia",
				"NR" => "Nauru",
				"NP" => "Nepal",
				"NL" => "Netherlands",
				"AN" => "Netherlands Antilles",
				"NC" => "New Caledonia",
				"NZ" => "New Zealand",
				"NI" => "Nicaragua",
				"NE" => "Niger",
				"NG" => "Nigeria",
				"NU" => "Niue",
				"NF" => "Norfolk Island",
				"MP" => "Northern Mariana Islands",
				"NO" => "Norway",
				"OM" => "Oman",
				"PK" => "Pakistan",
				"PW" => "Palau",
				"PS" => "Palestinian Territory, Occupied",
				"PA" => "Panama",
				"PG" => "Papua New Guinea",
				"PY" => "Paraguay",
				"PE" => "Peru",
				"PH" => "Philippines",
				"PN" => "Pitcairn",
				"PL" => "Poland",
				"PT" => "Portugal",
				"PR" => "Puerto Rico",
				"QA" => "Qatar",
				"RE" => "Reunion",
				"RO" => "Romania",
				"RU" => "Russian Federation",
				"RW" => "Rwanda",
				"SH" => "Saint Helena",
				"KN" => "Saint Kitts and Nevis",
				"LC" => "Saint Lucia",
				"PM" => "Saint Pierre and Miquelon",
				"VC" => "Saint Vincent and The Grenadines",
				"WS" => "Samoa",
				"SM" => "San Marino",
				"ST" => "Sao Tome and Principe",
				"SA" => "Saudi Arabia",
				"SN" => "Senegal",
				"RS" => "Serbia",
				"SC" => "Seychelles",
				"SL" => "Sierra Leone",
				"SG" => "Singapore",
				"SK" => "Slovakia",
				"SI" => "Slovenia",
				"SB" => "Solomon Islands",
				"SO" => "Somalia",
				"ZA" => "South Africa",
				"GS" => "South Georgia and The South Sandwich Islands",
				"ES" => "Spain",
				"LK" => "Sri Lanka",
				"SD" => "Sudan",
				"SR" => "Suriname",
				"SJ" => "Svalbard and Jan Mayen",
				"SZ" => "Swaziland",
				"SE" => "Sweden",
				"CH" => "Switzerland",
				"SY" => "Syrian Arab Republic",
				"TW" => "Taiwan, Province of China",
				"TJ" => "Tajikistan",
				"TZ" => "Tanzania, United Republic of",
				"TH" => "Thailand",
				"TL" => "Timor-leste",
				"TG" => "Togo",
				"TK" => "Tokelau",
				"TO" => "Tonga",
				"TT" => "Trinidad and Tobago",
				"TN" => "Tunisia",
				"TR" => "Turkey",
				"TM" => "Turkmenistan",
				"TC" => "Turks and Caicos Islands",
				"TV" => "Tuvalu",
				"UG" => "Uganda",
				"UA" => "Ukraine",
				"AE" => "United Arab Emirates",
				"GB" => "United Kingdom",
				"US" => "United States",
				"UM" => "United States Minor Outlying Islands",
				"UY" => "Uruguay",
				"UZ" => "Uzbekistan",
				"VU" => "Vanuatu",
				"VE" => "Venezuela",
				"VN" => "Viet Nam",
				"VG" => "Virgin Islands, British",
				"VI" => "Virgin Islands, U.S.",
				"WF" => "Wallis and Futuna",
				"EH" => "Western Sahara",
				"YE" => "Yemen",
				"ZM" => "Zambia",
				"ZW" => "Zimbabwe");
				
	//ksort($countries);
	
	$strCountry = '<select name="'.$name.'" style="width:180px;">';
	$strCountry .= '<option value="">Select Country</option>';
	foreach($countries as $key => $value) 
	{
		if($country == htmlspecialchars($value))
			$strSelect = 'selected="selected"';
		else
			$strSelect = '';
			
		$strCountry .= '<option value="'.htmlspecialchars($value).'" '.$strSelect.'>'.htmlspecialchars($value).'</option>';
	}
	$strCountry .= '</select>';
	
	return $strCountry;
	
}
	
function getTextBetweenTagsfffff($string, $startTag, $endTag)
{
	/*echo $pattern = "@<$startTag>(.*?)<\/$endTag>@i";
	preg_match_all($pattern, $string, $matches);*/
	
	$delimiter = '#';
/*$startTag = '<h1 class="heading">';
$endTag = '</h1>';*/
echo ($startTag)."om";
  echo  $regex = $delimiter . preg_quote(stripslashes(htmlspecialchars_decode($startTag)), '/') 
                    . '(.*?)' 
                    . preg_quote(stripslashes(htmlspecialchars_decode($endTag)), '/') 
                    . $delimiter 
                    . 's';
	/*echo  $regex = $delimiter . $startTag 
                    . '(.*?)' 
                    . $endTag 
                    . $delimiter 
                    . 's';*/
    preg_match($regex,$string,$matches);
	
	echo '<PRE>';print_r($matches);
	return $matches[1];
}
?>