<?php

function generate_sentences($AString) {
  $splitSentences = splitIntoSentences($AString);
  
  try {
    require_once "NeoShared/Server/App/Global.php";
    $ThePOSs = $server->GetPosForSentences($splitSentences);
  } catch(Exception $e) {
    return array(array("sentence" => trim($AString), "pos" => ""));
  }
  
  $sentences = array();
  for($i = 0; $i < count($splitSentences); $i++) {
    $sentences[] = array("sentence" => trim($splitSentences[$i]), "pos" => $ThePOSs->Item($i)->GetProperty("Name"));
  }
  return $sentences;
}

function splitIntoSentences($AString, $isBody = true) {
  $sentences = array();
  $currentSentence = "";
  $inQuote = false;
  $abbreviations = array(" Mr.", " Mrs.", " Ms.", " e.g.", " etc.", " i.e.", " i. e.", "Dr.", " Prof.", " Sr.", " Jr.", " No.", " St.", "p.m."); 
  //replace quotes

  $AString = replaceInvalidChars(htmlspecialchars_decode($AString, ENT_QUOTES));
  
  //If the ENTIRE story is INSIDE ONLY one open and ONLY one closed quote, please remove them BEFORE processing.
  if( $isBody && (substr_count($AString, '"') == 2) && ($AString[0] == '"') && ($AString[strlen($AString)-1] == '"')) {
    $AString = str_replace ('"', '', $AString);
  }
  for($i=0; $i< strlen($AString); $i++) {
    if ($AString[$i] == '"'){
      $currentSentence .= $AString[$i];
      $inQuote = !$inQuote;
      continue;
    }
    if( ( (($AString[$i] == ".") || ($AString[$i] == "!") || ($AString[$i] == "?") )  && (!$inQuote)) ){    
      if(trim($currentSentence) == ''){
        continue;
      }
      if( ($AString[$i] == "!") || ($AString[$i] == "?") ){
        $currentSentence .= $AString[$i];
      }
      
      //Check for possible abbreviations that shouldn't break into sentences
      if( $AString[$i] == ".") {
        $ignore = false;
        foreach($abbreviations as $abbreviation) {
          $flag = true;
          for($j = 0; $j < strlen($abbreviation); $j++) {
            $index = $i - strlen($abbreviation) + $j + 1;
            if($index < 0 || $index >= strlen($AString) || strtolower($abbreviation[$j]) != strtolower($AString[$index])) {
              $flag = false;
              break;
            }
          }
          if($flag) {
            $ignore = true;
            break;
          }
        }
        if($ignore) {
          $currentSentence .= $AString[$i];
          continue;
        }
      }
      
      /*if( ($AString[$i] == ".") && ($i >= 0) && $i ){
        $currentSentence .= $AString[$i];
      }*/             
      array_push($sentences, trim($currentSentence));
      $currentSentence = "";
      continue;
    }
    $currentSentence .= $AString[$i];
  }
  if(trim($currentSentence) != ''){
    array_push($sentences, $currentSentence);
  }
  return $sentences;
}

function replaceInvalidChars($AString){
  $quotes = array(
        "\xC2\xAB"   => '"', // « (U+00AB) in UTF-8
        "\xC2\xBB"   => '"', // » (U+00BB) in UTF-8
        "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
        "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
        "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
        "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
        "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
        "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
        "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
        "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
        "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
        "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
        "\r" => "",
        "\n" => " ",
        "\t" => " ",
        "\xe2\x80\x94" => '-',
        "\xe2\x80\x93" => '-'
        
        //http://www.i18nqa.com/debug/table-iso8859-1-vs-windows-1252.html  special characters 
    );
    return strtr($AString, $quotes);
}

?>