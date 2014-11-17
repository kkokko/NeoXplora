<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  
    <!-- Site Properities -->
    <title>NeoXplora - API XML</title>
  
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700' rel='stylesheet' type='text/css'>
  
    <link rel="stylesheet" type="text/css" href="semantic/packaged/css/semantic.css">
  
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.js"></script>
    <script src="semantic/packaged/javascript/semantic.js"></script>
    <script src="js/apixml.js"></script>
  
  </head>

  <body id="home">
    <div class="ui celled grid">
      <div class="two wide left column ">
        <div class="ui vertical purple menu fluid">
          <a class="item" href="panel.php">
            Go back to panel
          </a>
          <div class="header item">
            Requests:
          </div>
          <a class="item <?php if($this->requestName == "GenerateRep") { echo 'active'; } ?>" href="api.php?name=GenerateRep">
            Generate Representation
          </a>
          <a class="item <?php if($this->requestName == "GenerateProtoGuess") { echo 'active'; } ?>" href="api.php?name=GenerateProtoGuess">
            Guess Proto Split
          </a>
          <a class="item <?php if($this->requestName == "SentenceMatch") { echo 'active'; } ?>" href="api.php?name=SentenceMatch">
            Sentence Match
          </a>
        </div>
      </div>
      <div class="eight wide middle column">
        <?php echo $this->requestHTML; ?>
        <br/>
        <?php echo $this->responseHTML; ?>
      </div>
      <div class="six wide right column">
        <div class="2 fluid ui buttons">
        <div class="ui purple button">XML</div>
        <div class="or"></div>
        <div class="ui button">JSON</div>
      </div>
      <br/>
      <div class="ui form">
        <div class="field">
          <label>Request</label>
          <textarea readonly="readonly" style="word-wrap:normal; white-space: pre;"><?php echo $this->requestXML; ?></textarea>
        </div>
        <div class="field">
          <label>Response</label>
          <textarea readonly="readonly" style="word-wrap:normal; white-space: pre;"><?php echo $this->responseXML; ?></textarea>
        </div>
      </div>
    </div>
  </body>
</html>