<?php 

if(!isset($_POST['sentenceID'])) exit;

$sentenceID = $_POST['sentenceID'];

require_once "App/Global.php";
$TheGuesses = $server->GuessRepsForSentenceId((int) $sentenceID);

$sentencesData = '<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc4' . $sentenceID . '" guessid="' . $TheGuesses->GetProperty('GuessIdD') .'" class="guess" >'. $TheGuesses->GetProperty('MatchSentenceD')  . '</span><br />
<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc3' . $sentenceID . '" guessid="' . $TheGuesses->GetProperty('GuessIdC') .'" class="guess" >'. $TheGuesses->GetProperty('MatchSentenceC')  . '</span><br />
<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc1' . $sentenceID . '"  guessid="' . $TheGuesses->GetProperty('GuessIdA') .'" class="guess">' . $TheGuesses->GetProperty('MatchSentenceA') . '</span><br>
<span id="matchsentc2' . $sentenceID . '" style="color;color:#999999;"   guessid="' . $TheGuesses->GetProperty('GuessIdB') .'" class="guess">' . $TheGuesses->GetProperty('MatchSentenceB') . '</span><br>';


$repguessesData = '<span guessid="' . $TheGuesses->GetProperty('GuessIdD') .'" class="guess" id="4'. $sentenceID .'r" style="color;color:#999999;">' . $TheGuesses->GetProperty('RepGuessD') . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdC') .'" class="guess" id="3'. $sentenceID .'r" style="color;color:#999999;">' . $TheGuesses->GetProperty('RepGuessC') . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdA') .'" class="guess" id="1'. $sentenceID .'r" style="color;color:#999999;">'. $TheGuesses->GetProperty('RepGuessA')  . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdB') .'" class="guess" id="2'. $sentenceID .'r" style="color;color:#999999;">'. $TheGuesses->GetProperty('RepGuessB')  . '</span><br />';

$srepguessData = '<span guessid="' . $TheGuesses->GetProperty('GuessIdD') .'" id="4'. $sentenceID .'s" class="guess"  style="color;color:#999999;">' . $TheGuesses->GetProperty('SRepGuessD') . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdC') .'" class="guess" id="3'. $sentenceID .'s" style="color;color:#999999;">' . $TheGuesses->GetProperty('SRepGuessC') . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdA') .'" class="guess" id="1'. $sentenceID .'s" style="color;color:#999999;">'. $TheGuesses->GetProperty('SRepGuessA')  . '</span><br />
<span guessid="' . $TheGuesses->GetProperty('GuessIdB') .'" class="guess" id="2'. $sentenceID .'s" style="color;color:#999999;">'. $TheGuesses->GetProperty('SRepGuessB')  . '</span><br />';
                  
$crepguessData = '<span class="guess" style="color;color:#999999;">' . $TheGuesses->GetProperty('CRepGuessA') . '</span><br />';


//for testing purposes
/*$sentencesData = '<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc4' . $sentenceID . '" guessid="' . '' .'" class="guess" >'. '1'  . '</span><br />
<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc3' . $sentenceID . '" guessid="' . '' .'" class="guess" >'. '2'  . '</span><br />
<span style="margin-bottom:5px;color;color:#999999;" id="matchsentc1' . $sentenceID . '"  guessid="' . '' .'" class="guess">' . '3' . '</span><br>
<span id="matchsentc2' . $sentenceID . '" style="color;color:#999999;"   guessid="' . '' .'" class="guess">' . '4' . '</span><br>';


$repguessesData = '<span  guessid="' . '' .'" class="guess"  style="color;color:#999999;">' . '1' . '</span><br />
<span  guessid="' . '' .'" class="guess"  style="color;color:#999999;">' . '2' . '</span><br />
<span  guessid="' . '' .'" class="guess"  style="color;color:#999999;">'. '3'  . '</span><br />
<span  guessid="' . '' .'" class="guess"  style="color;color:#999999;">'. '4'  . '</span><br />';
 
$srepguessData = '<span  guessid="" class="guess"  style="color;color:#999999;">1</span><br />
<span  guessid="" class="guess"  style="color;color:#999999;">2</span><br />
<span  guessid="" class="guess"  style="color;color:#999999;">3</span><br />
<span  guessid="" class="guess"  style="color;color:#999999;">4</span><br />';
                  
$crepguessData = '<span class="guess" style="color;color:#999999;">5</span><br />';
*/

$response = array(
  'sentencesData' => $sentencesData,
  'repguessesData' => $repguessesData,
  'crepguessData' => $crepguessData,
  'srepguessData' => $srepguessData
);

echo json_encode($response);

?>