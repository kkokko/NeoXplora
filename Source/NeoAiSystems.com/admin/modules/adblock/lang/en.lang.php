<?php
  /**
   * Language File
   *
   * @package CMS Pro
   * @author milestones-it.com
   * @copyright 2012
   * @version $Id: language.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  define('MOD_AB_TITLE1', 'Manage Ad Blocks &rsaquo; Edit AdBlock');
  define('MOD_AB_INFO1', 'Here you can update your project information.');
  define('MOD_AB_SUBTITLE1', 'Editing Ad Block');

  define('MOD_AB_TITLE2', 'Manage Ad Block &rsaquo; Add Ad Block');
  define('MOD_AB_INFO2', 'Here you can add new adblock.');
  define('MOD_AB_SUBTITLE2', 'Adding Ad Block');
  define('MOD_AB_NOADBLOCKS','The are no existing Ad Campaigns yet.');
  
  define('MOD_AB_SUBTITLE3', 'Configure Module &rsaquo; ');
    
  define('MOD_AB_NAME', 'Campaign Name');
  define('MOD_AB_NAME_R', 'Please Enter Ad Block Title');
  
  define('MOD_AB_POS', 'Position');
  define('MOD_AB_POS_SAVE', 'Save Position');
  define('MOD_AB_PUPDATED', '<span>Success!</span>Ad Campaign updated successfully!');
  define('MOD_AB_PADDED', '<span>Success!</span>Ad Campaign added successfully!');
  define('MOD_AB_TITLE4', 'Manage Ad Block');
  define('MOD_AB_INFO4', 'Here you can manage your Ad Campaigns.<br /><strong>Note: Deleting Ad Campaign will also delete all images and plugin assigned under the same adblock.</strong>');

  define('MOD_AB_CREATED', 'Created');
  define('MOD_AB_IS_ONLINE', 'Online/Offline');
  define('MOD_AB_BLOCK_ASSIGNMENT', 'Block Assignment');
  define('MOD_AB_BLOCK_ASSIGNMENT_T','Enter a name in this box which will create a block for your ad campaign. You can then drag this block into your required pages on the Layout editor.');
  define('MOD_AB_BLOCK_ASSIGNMENT_INVALID', 'Block Assignment cannot be blank.');
  define('MOD_AB_BLOCK_ASSIGNMENT_EXISTS', 'Block Assignment with the given name already exists.');
  define('MOD_AB_DATE_S', 'Start Date');
  define('MOD_AB_DATE_S_INVALID', 'Please provide a valid start date.');
  define('MOD_AB_DATE_E', 'End Date');
  define('MOD_AB_DATE_E_INVALID','Please provide a valid end date.');
  define('MOD_AB_DATE_E_INVALID2','End date must not be before start end.');
  define('MOD_AB_DATE_E_NO', 'Don\'t end this campaign on a specific date.');
  define('MOD_AB_DATE_E_YES', 'End this campaign on a specific date.');
  define('MOD_AB_MAX_VIEWS','Total Views Allowed');
  define('MOD_AB_MAX_VIEWS_DESC','The campaign will end when this number of views is reached. Enter 0 for unlimited views.');
  define('MOD_AB_MAX_VIEWS_INVALID','Total Views Allowed must be positive integer.');
  define('MOD_AB_MAX_CLICKS','Total Clicks Allowed');
  define('MOD_AB_MAX_CLICKS_DESC','The campaign will end when this number of views is reached. Enter 0 for unlimited clicks.');
  define('MOD_AB_MAX_CLICKS_INVALID','Total Clicks Allowed must be positive integer.');
  define('MOD_AB_MIN_CTR','Minimum CTR');
  define('MOD_AB_MIN_CTR_DESC','If you specify a minimum CTR (click-through ratio, which is the ration of clicks to views) 
  		and campaign\'s CTR goes below your limit, the campaign will end.<br />If you decide to specify a minimum CTR limit, 
  		you should enter it as percentage of clicks to views (e.g. 0,05%).<br />To create a campaign with no definite end, 
  		don\'t specify limits and your campaign will continue until you choose to end it.');
  define('MOD_AB_MIN_CTR_INVALID','Minimum CTR must be numeric value between 0.0 and 1.0.');
  define('MOD_AB_ULEVEL_T', 'These are the user levels that will see the Ad. You must select as least one level.');
  define('MOD_AB_ULEVEL_INVALID', 'User Level cannot be empty.');
  define('MOD_AB_ADVERTISEMENT_MEDIA','Advertisement Media');
  define('MOD_AB_ADVERTISEMENT_MEDIA_DESC','Upload a banner image from your computer or specify your advertisement HTML code
  		 (e.g. Google AdSense). If you choose to upload an image, it must be a valid GIF, JPG, JPEG or PNG file under 200kB.');
  define('MOD_AB_BANNER_IMAGE_UPL','Upload Banner Image');
  define('MOD_AB_BANNER_IMAGE','Banner Image');
  define('MOD_AB_BANNER_IMAGE_INVALID','Banner Image must be a valid GIF, JPG, JPEG or PNG file under 200kB.');
  define('MOD_AB_BANNER_LINK', 'Banner Link');
  define('MOD_AB_BANNER_LINK_INVALID', 'Banner Link cannot be blank.');
  define('MOD_AB_BANNER_ALT', 'Banner ALT');
  define('MOD_AB_BANNER_ALT_INVALID', 'Banner ALT cannot be blank.');
  define('MOD_AB_BANNER_HTML','Insert Banner HTML');
  define('MOD_AB_BANNER_HTML_INVALID','Banner HTML must not be empty.');
  define('MOD_AB_ADD','Add Campaign');
  define('MOD_AB_EDIT','Edit Campaign');
  define('MOD_AB_ADBLOCK','Ad Campaign');
  define('MOD_AB_ONLINE','Online');
  define('MOD_AB_OFFLINE','Offline');
?>