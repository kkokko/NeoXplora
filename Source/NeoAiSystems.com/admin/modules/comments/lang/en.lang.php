<?php
  /**
   * Language File
   *
   * @version $Id: language.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  // Front
  define('MOD_CM_COMMENTS', 'Comments');
  define('MOD_CM_NOCOMMENTS', 'No Comments Yet...');
  define('MOD_CM_REPLY', 'Leave a Reply');
  define('MOD_CM_NAME', 'Name');
  define('MOD_CM_COMMENT', 'Your Comment');
  define('MOD_CM_HAS_C', 'This post has ');
  define('MOD_CM_E_NOT_V', 'Not visible');
  define('MOD_CM_WEB', 'Website');
  define('MOD_CM_CAPTCHA_N', 'Captcha Code');
  define('MOD_CM_ADDCOMMENT', 'Add Comment');
  define('MOD_CHAR_REMAIN1', 'You have <strong>');
  define('MOD_CHAR_REMAIN2', '<\/strong> characters remaining.');
  define('MOD_CM_REPLY2', 'Reply');
  define('MOD_CM_MSGOK1', '<span>Thank you!</span> Your post have been submitted');
  define('MOD_CM_MSGOK2', '<span>Thank you!</span> Your post have been submitted, and pending verification.');
  define('MOD_CM_MSGERR3', '<span>Info!</span> Sory only registered and logged in users can post comments. Please sign in or register.');
  
  // Post
  define('MOD_CM_E_NAME', 'Please Enter Valid Name');
  define('MOD_CM_E_CAPTCHA', 'Please Enter Captcha Code');
  define('MOD_CM_E_CAPTCHA2', 'Entered Captcha Code Is Invalid');
  define('MOD_CM_E_EMAIL', 'Please Enter Your Email Address');
  define('MOD_CM_E_EMAIL2', 'Entered Email Address Is Invalid');
  define('MOD_CM_E_WWW', 'Entered Website Is Invalid. Must start with http://');
  define('MOD_CM_E_COMMENT', 'Please Enter Your Comment');
  define('MOD_CM_MSG_ERR', 'Comment could not be sent due to the following error(s):');
  define('MOD_CM_MSG_ADMIN', 'You have a new comment');
  
  // Backend
  define('MOD_CM_TITLE1', 'Manage CMS Modules &rsaquo; Comments Configuration');
  define('MOD_CM_INFO1', 'Here you can update your comments configuration.');
  define('MOD_CM_SUBTITLE1', 'Update Comments Configuration ');
  define('MOD_CM_UNAME_R', 'Username Required');
  define('MOD_CM_UNAME_T', 'Is username required to post comment');
  define('MOD_CM_EMAIL_R', 'Email Required');
  define('MOD_CM_EMAIL_T', 'Is email required to post comment');
  define('MOD_CM_CAPTCHA', 'Show Captcha');
  define('MOD_CM_CAPTCHA_T', 'Show Captcha to prevent spam');
  define('MOD_CM_WWW', 'Show Website');
  define('MOD_CM_WWW_T', 'Show posters website');
  define('MOD_CM_UNAME_S', 'Show Username');
  define('MOD_CM_UNAME_ST', 'Show posters username');
  define('MOD_CM_EMAIL_S', 'Show Email');
  define('MOD_CM_EMAIL_ST', 'Show posters email. Required for using Gravatar Services');
  define('MOD_CM_REG_ONLY', 'Public Access');
  define('MOD_CM_REG_ONLY_T', 'If No, only registered and logged in users can post comments.<br />Otherwize all visitors will be able to post comments freely.');
  define('MOD_CM_SORTING', 'Comments Sorting');
  define('MOD_CM_SORTING_T', '--- Most Recent &rsaquo; Top ---');
  define('MOD_CM_SORTING_B', '--- Most Recent &rsaquo; Bottom ---');
  define('MOD_CM_CHAR', 'Character Limit');
  define('MOD_CM_CHAR_T', 'Limit number of comment characters.');
  define('MOD_CM_PERPAGE', 'Comments Per Page');
  define('MOD_CM_PERPAGE_T', 'How many comments to show for pagination');
  define('MOD_CM_AA', 'Auto Approve');
  define('MOD_CM_AA_T', 'Automatically approve all comments');
  define('MOD_CM_NOTIFY', 'Notifications');
  define('MOD_CM_NOTIFY_T', 'Send email notification on each comment added.<br />Will use default site email address');
  define('MOD_CM_DATE', 'Date Format');
  define('MOD_CM_DATE_T', 'Date format used for showing posting time.');
  define('MOD_CM_DATE_R', 'Plese select Valid Date Format.');
  define('MOD_CM_WORDS', 'Blacklisted Words');
  define('MOD_CM_WORDS_T', 'Enter one word per line. Each word will be replaced with ***');
  define('MOD_CM_UPDATE_B', 'Update Configuration');
  define('MOD_CM_TITLE3', 'Manage CMS Module &rsaquo; Configure Module');
  define('MOD_CM_INFO3', 'Here you can configure your content modules.');
  define('MOD_CM_SUBTITLE3', 'Configure Module &rsaquo; ');
  define('MOD_CM_CONFIG', 'Comment Configuration');
  define('MOD_CM_SHOWFROM', 'Show Items From:');
  define('MOD_CM_SHOWTO', 'Show Items To:');
  define('MOD_CM_FIND', 'Find');
  define('MOD_CM_FILTER', 'Comment Filter:');
  define('MOD_CM_FILTER_R', '-- Reset Comment Filter --');
  define('MOD_CM_UNAME', 'Username');
  define('MOD_CM_EMAIL', 'Email');
  define('MOD_CM_CREATED', 'Created');
  define('MOD_CM_PNAME', 'Page Name');
  define('MOD_CM_VIEW', 'View');
  define('MOD_CM_STATUS', 'Status');
  define('MOD_CM_VIEW_P', 'View Comment');
  define('MOD_CM_APPROVE', 'Approve');
  define('MOD_CM_DISAPPROVE', 'Disapprove');
  define('MOD_CM_NONCOMMENTS', '<span>Info!</span>You don\'t have any comments yet!!');
  define('MOD_CM_UPDATED', '<span>Success!</span>Comments Configuration updated successfully!');
  define('MOD_CM_NA', '<span>Alert!</span>Please select comment(s) to process!');
  define('MOD_CM_APPROVED', '<span>Success!</span>Selected Comments have been approved.');
  define('MOD_CM_DISAPPROVED', '<span>Success!</span>Selected Comments have been Disapproved.');
  define('MOD_CM_DELETED', '<span>Success!</span>Selected Comments have been deleted.');
  define('MOD_CM_COMUPDATED', '<span>Success!</span>Comment updated successfully.');
?>