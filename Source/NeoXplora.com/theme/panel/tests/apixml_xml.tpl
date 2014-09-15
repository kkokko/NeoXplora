<div style="width: 550px; float:left;">
  Request: <br/>
  <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 200px; margin-top: 10px;' id='req'><?php echo $this->requestxml; ?></textarea><br/><br/>
  <button id="run" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button">
    <span class="ui-button-text">Run</span>
  </button><br/>
  <br/><br/>Response:<br/>
  <textarea style='word-wrap:normal; white-space: pre; width: 600px; height: 300px; margin-top: 10px;' id='resp'><?php echo $this->responsexml; ?></textarea><br/><br/>
</div>
<div style="width: 280px; margin-left: 20px; float:left; text-align: right;">
  <div style='margin-bottom: 5px;'>Requests:</div>
  <button id="generateRep" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only loadXML" role="button">
    <span class="ui-button-text">Generate Rep</span>
  </button><br/>
  <button id="generateRep2" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only loadXML" role="button">
    <span class="ui-button-text">Generate Rep 2</span>
  </button><br/>
  <button id="guessProto" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only loadXML" role="button">
    <span class="ui-button-text">GenerateProtoGuess</span>
  </button><br/>
</div>
<div style="clear:both"></div>