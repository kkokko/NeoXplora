<?php
  /**
   * Features
   *
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php include(THEMEDIR."/header.php");?>
<link href="<?php echo SITEURL;?>/assets/highliter.css" rel="stylesheet" type="text/css"/>
<!-- Full Layout -->
<div class="container">
  <div class="row grid_24">
  <div id="page">
    <div class="box">
      <h2>Default grid system <small>24 fluid columns with a responsive twist</small></h2>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
        <div class="col grid_2 gridbox">2</div>
      </div>
      <div class="row top20">
        <div class="col grid_8 gridbox">8</div>
        <div class="col grid_8 gridbox">8</div>
        <div class="col grid_8 gridbox">8</div>
      </div>
      <div class="row top20">
        <div class="col grid_12 gridbox">12</div>
        <div class="col grid_12 gridbox">12</div>
      </div>
      <div class="row top20">
        <div class="col grid_6 gridbox">6</div>
        <div class="col grid_6 gridbox">6</div>
        <div class="col grid_6 gridbox">6</div>
        <div class="col grid_6 gridbox">6</div>
      </div>
      <div class="row top20">
        <div class="col grid_6 gridbox">6</div>
        <div class="col grid_8 gridbox">8</div>
        <div class="col grid_5 gridbox">5</div>
        <div class="col grid_5 gridbox">5</div>
      </div>
      <div class="row top20">
        <div class="col grid_24 gridbox">24</div>
      </div>
      <div class="row top20">
        <div class="col grid_8 top5">
          <p>The default grid system provided in CMS pro! utilizes <strong>24 fluid columns</strong> that render out at any widths. Below 767px viewports, the columns stack vertically. </p>
        </div>
        <div class="col grid_8 top5">
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"row"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_8"</span><span class="tag">&gt;</span><span>...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_16"</span><span class="tag">&gt;</span><span>...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
        <div class="col grid_8 top5">
          <p>As shown here, a basic layout can be created with two "columns", each spanning a number of the 24 foundational columns we defined as part of our grid system.</p>
        </div>
      </div>
      <div class="hr2"></div>
      <h2>Lists</h2>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_6">
          <h4>Unordered Bullets</h4>
          <p><code>&lt;ul class="bullets"&gt;</code></p>
          <ul class="bullets top10">
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
          </ul>
        </div>
        <div class="col grid_6">
          <h4>Unordered Checks</h4>
          <p><code>&lt;ul class="check"&gt;</code></p>
          <ul class="check top10">
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
          </ul>
        </div>
        <div class="col grid_6">
          <h4>Unordered Stars</h4>
          <p><code>&lt;ul class="star"&gt;</code></p>
          <ul class="star top10">
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
          </ul>
        </div>
        <div class="col grid_6">
          <h4>Ordered</h4>
          <p><code>&lt;ol class="numbers"&gt;</code></p>
          <ol class="numbers top10">
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
          </ol>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Labels</h4>
          <div  class="top10">Lorem ipsum dolor sit amet, <span class="label label-ok">consectetur</span> adipisicing elit, sed do eiusmod tempor incididunt ut <span class="label label-error">labore et</span> dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut <span class="label label-alert">aliquip</span> ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore <span class="label label-info">eu fugiat</span> nulla pariatur.</div>
        </div>
        <div class="col grid_12">
          <h4>Label Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"label&nbsp;label-ok"</span><span class="tag">&gt;</span><span>your&nbsp;text</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"label&nbsp;label-info"</span><span class="tag">&gt;</span><span>your&nbsp;text</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"label&nbsp;label-alert"</span><span class="tag">&gt;</span><span>your&nbsp;text</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"label&nbsp;label-error"</span><span class="tag">&gt;</span><span>your&nbsp;text</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Progress Bars</h4>
          <div class="top10">
            <div class="progress-bar">
              <div class="green" style="width:20%"></div>
            </div>
            <div class="progress-bar top20">
              <div class="blue" style="width:40%"></div>
            </div>
            <div class="progress-bar top20">
              <div class="yellow" style="width:60%"></div>
            </div>
            <div class="progress-bar top20">
              <div class="red" style="width:80%"></div>
            </div>
          </div>
        </div>
        <div class="col grid_12">
          <h4>Progress Bars Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"progress-bar&nbsp;blue"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">style</span><span>=</span><span class="attribute-value">"width:20%"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"progress-bar&nbsp;yellow"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">style</span><span>=</span><span class="attribute-value">"width:40%"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"progress-bar&nbsp;green"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">style</span><span>=</span><span class="attribute-value">"width:60%"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"progress-bar&nbsp;red"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">style</span><span>=</span><span class="attribute-value">"width:80%"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Togglable Tabs</h4>
          <ul class="tabs">
            <li><a data-title="tab1" href="#">Tab 1</a></li>
            <li><a data-title="tab2" href="#">Tab 2</a></li>
            <li><a data-title="tab3" href="#">Tab 3</a></li>
          </ul>
          <div class="tab-content clearfix">
            <div id="tab1">Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.</div>
            <div id="tab2">Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.</div>
            <div id="tab3">Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.</div>
          </div>
        </div>
        <div class="col grid_12">
          <h4>Togglable Tabs Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">ul</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"tabs"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">data-title</span><span>=</span><span class="attribute-value">"tab1"</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span class="tag">&gt;</span><span>Tab&nbsp;1</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">data-title</span><span>=</span><span class="attribute-value">"tab2"</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span class="tag">&gt;</span><span>Tab&nbsp;2</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">data-title</span><span>=</span><span class="attribute-value">"tab3"</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span class="tag">&gt;</span><span>Tab&nbsp;3</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">ul</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"tab-content&nbsp;clearfix"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"tab1"</span><span class="tag">&gt;</span><span>&nbsp;Tab&nbsp;1&nbsp;content...&nbsp;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"tab2"</span><span class="tag">&gt;</span><span>&nbsp;Tab&nbsp;2&nbsp;content...&nbsp;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"tab3"</span><span class="tag">&gt;</span><span>&nbsp;Tab&nbsp;3&nbsp;content...&nbsp;</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Accordion</h4>
          <div class="accordion">
            <div class="accowrap">
              <h4>Our Clients <span class="chevron"></span></h4>
              <div class="acco-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas neque diam, luctus at laoreet in, auctor ut tellus. Etiam enim lacus, ornare et tempor, rhoncus rhoncus sem.</p>
                <p>Aliquam volutpat arcu et nibh mollis eleifend pharetra lorem scelerisque. Donec vel enim purus, id viverra neque. Cras in velit ante, eget pellentesque sem. Duis tincidunt erat quam. Etiam placerat sapien elit.</p>
              </div>
            </div>
            <div class="accowrap">
              <h4>Our Mission <span class="chevron"></span></h4>
              <div class="acco-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas neque diam, luctus at laoreet in, auctor ut tellus. Etiam enim lacus, ornare et tempor, rhoncus rhoncus sem.</p>
                <p>Aliquam volutpat arcu et nibh mollis eleifend pharetra lorem scelerisque. Donec vel enim purus, id viverra neque. Cras in velit ante, eget pellentesque sem. Duis tincidunt erat quam. Etiam placerat sapien elit.</p>
              </div>
            </div>
            <div class="accowrap">
              <h4>Our Company <span class="chevron"></span></h4>
              <div class="acco-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas neque diam, luctus at laoreet in, auctor ut tellus. Etiam enim lacus, ornare et tempor, rhoncus rhoncus sem.</p>
                <p>Aliquam volutpat arcu et nibh mollis eleifend pharetra lorem scelerisque. Donec vel enim purus, id viverra neque. Cras in velit ante, eget pellentesque sem. Duis tincidunt erat quam. Etiam placerat sapien elit.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col grid_12">
          <h4>Accordion Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"accordion clearfix"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"accowrap"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>Our Clients</span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"chevron"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"acco-content"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>Aliquam&nbsp;volutpat&nbsp;arcu&nbsp;....</span><span class="tag">&lt;/</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"accowrap"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>Our Mission</span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"chevron"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"acco-content"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>Aliquam&nbsp;volutpat&nbsp;arcu&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"accowrap"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>Our Company</span><span class="tag">&lt;</span><span class="tag-name">span</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"chevron"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">h4</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"acco-content"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>Aliquam&nbsp;volutpat&nbsp;arcu&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">p</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Carousel</h4>
          <div class="box">
            <h4>Your title goes here</h4>
            <div class="carousel">
              <ul class="slides">
                <li>Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico.</li>
                <li>Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico.</li>
                <li>Labore indoctum ullamcorper et his. volumus corpora verterem cum in, has in maiorum repudiare disputationi. Sed electram aliquando disputando ad.Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico.</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col grid_12">
          <h4>Carousel Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"carousel clearfix"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">ul</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"slides"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>Labore....</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>Labore....</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>Labore....</span><span class="tag">&lt;/</span><span class="tag-name">li</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;&nbsp;</span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">ul</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Message Boxes</h4>
          <div class="msgOk"> <span>Success!</span>Eum ad enim laoreet graecis, ad vel sumo justo doming. </div>
          <div class="msgAlert"> <span>Alert!</span>Eum ad enim laoreet graecis, ad vel sumo justo doming. </div>
          <div class="msgInfo"> <span>Info!</span>Eum ad enim laoreet graecis, ad vel sumo justo doming. </div>
          <div class="msgError"> <span>Error!</span>Eum ad enim laoreet graecis, ad vel sumo justo doming. </div>
        </div>
        <div class="col grid_12">
          <h4>Message Boxes Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"msgOk"</span><span class="tag">&gt;</span><span>&nbsp;</span><span class="tag">&lt;</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Success!</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Your&nbsp;content&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"msgAlert"</span><span class="tag">&gt;</span><span>&nbsp;</span><span class="tag">&lt;</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Alert!</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Your&nbsp;content&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"msgInfo"</span><span class="tag">&gt;</span><span>&nbsp;</span><span class="tag">&lt;</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Info!</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Your&nbsp;content&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"msgError"</span><span class="tag">&gt;</span><span>&nbsp;</span><span class="tag">&lt;</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Error!</span><span class="tag">&lt;/</span><span class="tag-name">span</span><span class="tag">&gt;</span><span>Your&nbsp;content&nbsp;...</span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Responsive Tables</h4>
          <table class="datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Sonet dus</th>
                <th>Eirmod an</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Te natum eruditi</td>
                <td>Ex of modus pri</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Te natum eruditi</td>
                <td>Ex of modus pri</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Te natum eruditi</td>
                <td>Ex of modus pri</td>
              </tr>
              <tr>
                <td>4</td>
                <td>Te natum eruditi</td>
                <td>Ex of modus pri</td>
              </tr>
              <tr>
                <td>5</td>
                <td>Te natum eruditi</td>
                <td>Ex of modus pri</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col grid_12">
          <h4>Responsive Tables Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">table</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"datatable"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">thead</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">th</span><span class="tag">&gt;</span><span>Sonet&nbsp;dus</span><span class="tag">&lt;/</span><span class="tag-name">th</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">thead</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">tbody</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">td</span><span class="tag">&gt;</span><span>Ex&nbsp;of&nbsp;modus&nbsp;pri</span><span class="tag">&lt;/</span><span class="tag-name">td</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">td</span><span class="tag">&gt;</span><span>Ex&nbsp;of&nbsp;modus&nbsp;pri</span><span class="tag">&lt;/</span><span class="tag-name">td</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">tr</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">tbody</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">table</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_12">
          <h4>Buttons</h4>
          <a href="#" class="button">Normal Button</a> <a href="#" class="button butred">Red Button</a> <a href="#" class="button butblue">Blue Button</a>
          <p class="top5"><a href="#" class="button butgreen">Green Button</a> <a href="#" class="button butyellow">Yellow Button</a> <a href="#" class="button butorange">Orange Button</a></p>
        </div>
        <div class="col grid_12">
          <h4>Buttons Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button"</span><span class="tag">&gt;</span><span>Normal&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button&nbsp;butred"</span><span class="tag">&gt;</span><span>Red&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button&nbsp;butblue"</span><span class="tag">&gt;</span><span>Blue&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button&nbsp;butgreen"</span><span class="tag">&gt;</span><span>Green&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button&nbsp;butyellow"</span><span class="tag">&gt;</span><span>Yellow&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"#"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"button&nbsp;butorange"</span><span class="tag">&gt;</span><span>Orange&nbsp;Button</span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_24">
          <h4>Boxes</h4>
          <div class="box"> <code>&lt;div class="box"&gt; ... &lt;/div&gt;</code> Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico. </div>
          <div class="box bluebox top10"> <code>&lt;div class="box bluebox"&gt; ... &lt;/div&gt;</code> Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico. </div>
          <div class="box redbox top10"> <code>&lt;div class="box redbox"&gt; ... &lt;/div&gt;</code> Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico. </div>
          <div class="box greenbox top10"> <code>&lt;div class="box greenbox"&gt; ... &lt;/div&gt;</code> Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico. </div>
          <div class="box whitebox top10"> <code style="color:#000">&lt;div class="box whitebox"&gt; ... &lt;/div&gt;</code> Autem comprehensam et duo. Sed et dicunt commodo qualisque. Ut vix eius iudico. </div>
        </div>
      </div>
      <div class="hr2"></div>
      <div class="row">
        <div class="col grid_6">
          <h4>Image Lightbox</h4>
          <figure><a href="<?php echo UPLOADURL;?>/images/pages/demo5.jpg" class="fancybox zoom"><img src="<?php echo UPLOADURL;?>/images/pages/thumb_demo5.jpg" /></a></figure>
        </div>
        <div class="col grid_6">
          <h4>Video Lightbox</h4>
          <figure><a href="http://vimeo.com/29193046" data-media="media" class="video zoom"><img src="<?php echo UPLOADURL;?>/images/pages/thumb_demo6.jpg" /></a></figure>
        </div>
        <div class="col grid_12">
          <h4>Image/Video Lightbox Code</h4>
          <div class="dp-highlighter">
            <div class="bar"></div>
            <ol class="dp-xml" start="1">
              <li class="alt"><span><span>Image&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"images/pages/demo5.jpg"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"fancybox&nbsp;zoom"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">img</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"images/pages/thumb_demo5.jpg"</span><span>&nbsp;</span><span class="tag">/&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>Video&nbsp;&nbsp;</span></li>
              <li class=""><span><span class="tag">&lt;</span><span class="tag-name">a</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"http://vimeo.com/29193046"</span><span>&nbsp;</span><span class="attribute">data-media</span><span>=</span><span class="attribute-value">"media"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"video&nbsp;zoom"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">img</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"images/pages/thumb_demo6.jpg"</span><span>&nbsp;</span><span class="tag">/&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">a</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php include(THEMEDIR."/footer.php");?>