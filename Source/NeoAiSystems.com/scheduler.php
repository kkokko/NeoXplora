<?php
define("_VALID_PHP", true);
require_once ("init.php");
include (THEMEDIR . "/header.php");
?>

<script language="javascript" src="assets/train-script.js"></script>
<style type="text/css">
  @import url("assets/train-style.css");
</style>

<div id="container">
  <div class="top">
    <div class="story-title">
      Schedule URLs
    </div>
    <div class="story-controls">
      <ul>
        
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      <table style="width: 40%; margin: 0 auto;" class="form-table" align="center">
        <tr>
          <td style="width: 100px;">URL: </td>
          <td><input tpye="text" /></td>
        </tr>
        <tr>
          <td>Mode: </td>
          <td>
            <select>
              <option>Crawl</option>
              <option>Random</option>
            </select>
          </td>
        </tr>
      </table><br/>
      <table width="100%">
        <tr>
          <th>Sentence</th>
          <th>Representation</th>
        </tr>
      </table>
    </div>
    <div class="pagination">
      1 2 3
    </div>
  </div>
</div>

<?php
include (THEMEDIR . "/footer.php");
?>

