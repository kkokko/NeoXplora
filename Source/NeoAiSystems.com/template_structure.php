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
        <!-- Content Start -->
        <h2>Creating new template utilizing responsive layout grid</h2>
        <div class="hr2"></div>
        Each CMS pro m2 template contains 3 main files plus additional files for user registration, login, search results and site map.<br />
        First I will start explaining header.php file and it's structure.
        <div class="hr2"></div>
        <div class="row">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>&lt;!doctype&nbsp;html</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">head</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>....</span></li>
                <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">head</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Document&nbsp;type&nbsp;declaration&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Opening&nbsp;header&nbsp;tag&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Javascript&nbsp;and&nbsp;css&nbsp;files&nbsp;will&nbsp;go&nbsp;here&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;header&nbsp;tag&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="row top20">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;$content-</span><span class="tag">&gt;</span><span>getMeta();&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>var&nbsp;<span class="attribute">THEMEURL</span><span>&nbsp;=&nbsp;</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;THEMEURL;&nbsp;?&gt;"</span><span>;&nbsp;&nbsp;</span></span></li>
                <li class=""><span>var&nbsp;<span class="attribute">SITEURL</span><span>&nbsp;=&nbsp;</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;&nbsp;?&gt;"</span><span>;&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>getThemeStyle();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Function&nbsp;that&nbsp;dynamically&nbsp;process&nbsp;all&nbsp;meta&nbsp;tags&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Javascript&nbsp;global&nbsp;variables&nbsp;needed&nbsp;for&nbsp;ajax&nbsp;calls&nbsp;&nbsp;</span></li>
                <li class=""><span>&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;</span></li>
                <li class=""><span>Function&nbsp;that&nbsp;dynamically&nbsp;loads&nbsp;all&nbsp;stylesheets&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="row top20">
          <div class="col grid_24">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/jquery.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/jquery-ui.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/tables.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/global.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/cycle.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/flex.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;THEMEURL;?&gt;/master.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/fancybox/jquery.fancybox.pack.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/fancybox/helpers/jquery.fancybox-media.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">link</span><span>&nbsp;</span><span class="attribute">rel</span><span>=</span><span class="attribute-value">"stylesheet"</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/css"</span><span>&nbsp;</span><span class="attribute">href</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/fancybox/jquery.fancybox.css"</span><span>&nbsp;</span><span class="attribute">media</span><span>=</span><span class="attribute-value">"screen"</span><span>&nbsp;</span><span class="tag">/&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
            <br />
            <span class="label label-ok">All the above javascript/css files are needed, and must be included in the same order as shown.</span>
            <div class="top10">Next we'll include EU cookie law script: You can ignore this part if you don't need it</div>
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;if($core-</span><span class="tag">&gt;</span><span>eucookie):</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span>&nbsp;</span><span class="attribute">src</span><span>=</span><span class="attribute-value">"&lt;?php&nbsp;echo&nbsp;SITEURL;?&gt;/assets/eu_cookies.js"</span><span class="tag">&gt;</span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;</span><span class="tag-name">script</span><span>&nbsp;</span><span class="attribute">type</span><span>=</span><span class="attribute-value">"text/javascript"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;</span></span></li>
                <li class=""><span>$(document).ready(function&nbsp;()&nbsp;{&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;$("body").acceptCookies({&nbsp;&nbsp;</span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;position:&nbsp;'top',&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notice:&nbsp;'<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;EU_NOTICE;</span><span class="tag">?&gt;</span><span>',&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;accept:&nbsp;'<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;EU_ACCEPT;</span><span class="tag">?&gt;</span><span>',&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;decline:&nbsp;'<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;EU_DECLINE;</span><span class="tag">?&gt;</span><span>',&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;decline_t:&nbsp;'<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;EU_DECLINE_T;</span><span class="tag">?&gt;</span><span>',&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;whatc:&nbsp;'<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;echo&nbsp;EU_W_COOKIES;</span><span class="tag">?&gt;</span><span>'&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;})&nbsp;&nbsp;</span></li>
                <li class="alt"><span>});&nbsp;&nbsp;</span></li>
                <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">script</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;endif;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="row top20">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>getPluginAssets();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>getModuleAssets();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Function&nbsp;that&nbsp;dynamically&nbsp;loads&nbsp;all&nbsp;plugin&nbsp;assets&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Function&nbsp;that&nbsp;dynamically&nbsp;loads&nbsp;all&nbsp;module&nbsp;assets&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">That's it for the <code>head section</code> How will you design and structure your header it's totally up to you. You can use the existing theme header or create one from scratch.</div>
        <div class="hr2"></div>
        Let's move onto index.php. This file might seem a bit complex, but in reality it's very simple. Since this file switches layout based on plugins included on each page, I will split it into few different sections. First lets examine php standard switch part of it:
        <div class="row top20">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;switch(true):&nbsp;case&nbsp;$totalleft&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1&nbsp;&amp;&amp;&nbsp;$totalright&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>...&nbsp;&nbsp;</span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;case&nbsp;$totalleft&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>...&nbsp;&nbsp;</span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;case&nbsp;$totalright&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>...&nbsp;&nbsp;</span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;default:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>...&nbsp;&nbsp;</span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;endswitch;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>If&nbsp;we&nbsp;have&nbsp;left&nbsp;and&nbsp;right&nbsp;sidebars&nbsp;use&nbsp;this&nbsp;layout&nbsp;&nbsp;</span></span></li>
                <li class=""><span>html&nbsp;part&nbsp;will&nbsp;go&nbsp;here...&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;</span></li>
                <li class=""><span>If&nbsp;we&nbsp;have&nbsp;left&nbsp;only&nbsp;sidebar&nbsp;use&nbsp;this&nbsp;layout&nbsp;&nbsp;</span></li>
                <li class="alt"><span>html&nbsp;part&nbsp;will&nbsp;go&nbsp;here...&nbsp;&nbsp;</span></li>
                <li class=""><span>&nbsp;&nbsp;</span></li>
                <li class="alt"><span>If&nbsp;we&nbsp;have&nbsp;right&nbsp;only&nbsp;sidebar&nbsp;use&nbsp;this&nbsp;layout&nbsp;&nbsp;</span></li>
                <li class=""><span>html&nbsp;part&nbsp;will&nbsp;go&nbsp;here...&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;</span></li>
                <li class=""><span>For&nbsp;everything&nbsp;else&nbsp;use&nbsp;full&nbsp;layout&nbsp;&nbsp;</span></li>
                <li class="alt"><span>html&nbsp;part&nbsp;will&nbsp;go&nbsp;here...&nbsp;&nbsp;</span></li>
                <li class=""><span>&nbsp;&nbsp;</span></li>
                <li class="alt"><span>&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">Both left and right sidebar layout explained.  See demo page <a href="<?php echo SITEURL;?>/All-Modules.html">here</a></div>
        <div class="row top10">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;switch(true):&nbsp;case&nbsp;$totalleft&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1&nbsp;&amp;&amp;&nbsp;$totalright&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"content-left-right"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"row&nbsp;grid_24"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"clearfix"</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"page"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">aside</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"sidebar"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_6"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;include(WOJOLITE&nbsp;.&nbsp;"includes/left_plugins.php");</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">aside</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"maincontent"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_14"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"box"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>displayPage();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">aside</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"sidebar2"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_4"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;include(WOJOLITE&nbsp;.&nbsp;"includes/right_plugins.php");</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">aside</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Switch&nbsp;Starts&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Html&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Inner&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class=""><span>Left&nbsp;Sidebar&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;6&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Left&nbsp;Sidebar&nbsp;File&nbsp;Included&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Left&nbsp;Sidebar&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Main&nbsp;Content&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;14&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Main&nbsp;Content&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Function&nbsp;That&nbsp;Renders&nbsp;Main&nbsp;Content&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Content&nbsp;&nbsp;</span></li>
                <li class=""><span>Right&nbsp;Sidebar&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;4&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Right&nbsp;Sidebar&nbsp;File&nbsp;Included&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Right&nbsp;Sidebar&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Inner&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Switch&nbsp;Break&nbsp;&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">Left sidebar layout explained.  See demo page <a href="<?php echo SITEURL;?>/Our-Contact-Info.html">here</a></div>
        <div class="row top10">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;case&nbsp;$totalleft&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"content-left"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"row&nbsp;grid_24"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"page"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"clearfix"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">aside</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"sidebar"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_7"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;include(WOJOLITE&nbsp;.&nbsp;"includes/left_plugins.php");</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">aside</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"maincontent"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_17"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"box"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>displayPage();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Switch&nbsp;Starts&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Html&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Inner&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Left&nbsp;Sidebar&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Left&nbsp;Sidebar&nbsp;File&nbsp;Included&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Left&nbsp;Sidebar&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Main&nbsp;Content&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;17&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Main&nbsp;Content&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Function&nbsp;That&nbsp;Renders&nbsp;Main&nbsp;Content&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Content&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Inner&nbsp;Wrapper&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Switch&nbsp;Break&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">Right sidebar layout explained.  See demo page <a href="<?php echo SITEURL;?>/What-is-CMS-pro.html">here</a></div>
        <div class="row top10">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;case&nbsp;$totalright&nbsp;</span><span class="tag">&gt;</span><span>=&nbsp;1:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"content-right"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"grid_24"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"clearfix"</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"page"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"maincontent"</span><span>&nbsp;&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_17"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"box"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>displayPage();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">aside</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"sidebar"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"col&nbsp;grid_7"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;include(WOJOLITE&nbsp;.&nbsp;"includes/right_plugins.php");</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">aside</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Switch&nbsp;Starts&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Html&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Inner&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Main&nbsp;Content&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;17&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Main&nbsp;Content&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Function&nbsp;That&nbsp;Renders&nbsp;Main&nbsp;Content&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Content&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Right&nbsp;Sidebar&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Right&nbsp;Sidebar&nbsp;File&nbsp;Included&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Right&nbsp;Sidebar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Inner&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Switch&nbsp;Break&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">Full layout explained.  See demo page <a href="<?php echo SITEURL;?>/Demo-Gallery-Page.html">here</a></div>
        <div class="row top10">
          <div class="col grid_12">
            <div class="dp-highlighter">
              <div class="bar"></div>
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;default:&nbsp;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">id</span><span>=</span><span class="attribute-value">"page"</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"row&nbsp;grid_24"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;</span><span class="tag-name">div</span><span>&nbsp;</span><span class="attribute">class</span><span>=</span><span class="attribute-value">"box"</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;$content-</span><span class="tag">&gt;</span><span>displayPage();</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span>&nbsp;&nbsp;<span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class=""><span><span class="tag">&lt;/</span><span class="tag-name">div</span><span class="tag">&gt;</span><span>&nbsp;&nbsp;</span></span></li>
                <li class="alt"><span><span class="tag">&lt;?</span><span class="tag-name">php</span><span>&nbsp;break;</span><span class="tag">?&gt;</span><span>&nbsp;&nbsp;</span></span></li>
              </ol>
            </div>
          </div>
          <div class="col grid_12">
            <div class="dp-highlighter">
              <ol class="dp-xml" start="1">
                <li class="alt"><span><span>Switch&nbsp;Starts&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></li>
                <li class=""><span>Main&nbsp;Content&nbsp;With&nbsp;Assigned&nbsp;Grid&nbsp;Of&nbsp;24&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Main&nbsp;Content&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Function&nbsp;That&nbsp;Renders&nbsp;Main&nbsp;Content&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Closing&nbsp;Tag&nbsp;For&nbsp;Main&nbsp;Content&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class=""><span>Closing&nbsp;Tag&nbsp;For&nbsp;Html&nbsp;Wrapper&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
                <li class="alt"><span>Switch&nbsp;Break&nbsp;&nbsp;</span></li>
              </ol>
            </div>
          </div>
        </div>
        <div class="top10">Few useful tips:</div>
        <div class="msgOk"><strong>Loading RTL css files</strong><br />
        If you are using RTL language, create a new style sheet and name it "<strong>style_rtl.css</strong>". System will look for this file and load it if exists. Don't forget to set language direction from Language manager in admin panel.</div>
        
        <div class="msgInfo"><strong>Styling individual plugins/modules</strong><br />
        If you want your plugins/modules to match your current theme, create a new theme folder inside each plugin/module directory and name it the same as your main theme. Inside place your style.css.</div>
        
        <div class="msgAlert"><strong>Assigning unique css classes to plugins</strong><br />
        From edit menu under plugin section in admin area, you can assign custom css classes to each of your plugins. Than just declare those rules in your main css file. See examples of different box colors in metro theme</div>
        <!-- Content Ends /--> 
      </div>
    </div>
  </div>
</div>
<?php include(THEMEDIR."/footer.php");?>
