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
          <div class="header item">
            Requests:
          </div>
          <a class="item <?php if($this->requestName == "GenerateRep") { echo 'active'; } ?>" href="api.php?name=GenerateRep">
            Generate Representation
          </a>
          <a class="item <?php if($this->requestName == "GenerateProtoGuess") { echo 'active'; } ?>" href="api.php?name=GenerateProtoGuess">
            Guess Proto Split
          </a>
        </div>
      </div>
      <div class="eight wide middle column">
        
        <div class="ui tabular filter menu top attached">
          <a class="active item">Request</a>
          <div class="item right fitted" style="padding-top: 10px;">
            <div class="ui label floating right" style="top: 6px; left: -55px; width: 75px; background-color: transparent; color: #666;">
              API Key:
            </div>
            <div class="ui right input">
              <input type="text" id="ApiKey" value="<?php echo $this->ApiKey; ?>">
            </div>
          </div>
        </div>
        <div class="ui bottom attached segment stacked selection">
          <form action="api.php?name=<?php echo $this->requestName; ?>" method="post" id="requestForm">
            <div class="ui form">
              <?php echo $this->requestHTML; ?>
              <input type="hidden" name="ApiKey" />
              <div class="ui purple submit button" id="executeRequest">Execute</div>
            </div>
          </form>
        </div>
        
        <br/>
        <div class="ui tabular filter menu top attached">
          <a class="active item">Response</a>
        </div>
        <div class="ui segment stacked selection bottom attached">
          <div class="ui form">
            <?php echo $this->responseHTML; ?>
          </div>
        </div>
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