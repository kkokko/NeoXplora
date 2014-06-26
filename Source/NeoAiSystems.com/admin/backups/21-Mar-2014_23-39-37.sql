-- --------------------------------------------------------------------------------
-- 
-- @version: db179668_neoaisystems.sql Mar 21, 2014 23:39 gewa
-- @package CMS Pro
-- @author wojoscripts.com.
-- @copyright 2010
-- 
-- --------------------------------------------------------------------------------
-- Host: 127.0.0.1
-- Database: db179668_neoaisystems
-- Time: Mar 21, 2014-23:39
-- MySQL version: 
-- PHP version: 5.3.27
-- --------------------------------------------------------------------------------

#
# Database: `db179668_neoaisystems`
#


-- --------------------------------------------------
# -- Table structure for table `email_templates`
-- --------------------------------------------------
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(200) NOT NULL,
  `subject_en` varchar(255) NOT NULL,
  `help_en` text,
  `body_en` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `email_templates`
-- --------------------------------------------------

INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('1', 'Registration Email', 'Please verify your email', 'This template is used to send Registration Verification Email, when Configuration->Registration Verification is set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;         &lt;hr /&gt;\n            The administrator of this site has requested all new accounts&lt;br /&gt;\n            to be activated by the users who created them thus your account&lt;br /&gt;\n            is currently inactive. To activate your account,&lt;br /&gt;\n            please visit the link below and enter the following:&lt;hr /&gt;\n            Token: &lt;strong&gt;[TOKEN]&lt;/strong&gt;&lt;br /&gt;\n            Email: &lt;strong&gt;[EMAIL]&lt;/strong&gt;         &lt;hr /&gt;\n            &lt;a href=&quot;[LINK]&quot;&gt;Click here to activate tour account&lt;/a&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('2', 'Forgot Password Email', 'Password Reset', 'This template is used for retrieving lost user password', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;New password reset from [SITE_NAME]!&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello, &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            It seems that you or someone requested a new password for you.&lt;br /&gt;\n            We have generated a new password, as requested:&lt;br /&gt;\n            &lt;br /&gt;\n            Your new password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            To use the new password you need to activate it. To do this click the link provided below and login with your new password.&lt;br /&gt;\n            &lt;a href=&quot;[LINK]&quot;&gt;[LINK]&lt;/a&gt;&lt;br /&gt;\n            &lt;br /&gt;\n            You can change your password after you sign in.&lt;hr /&gt;\n            Password requested from IP: [IP]&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('3', 'Welcome Mail From Admin', 'You have been registered', 'This template is used to send welcome email, when user is added by administrator', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! You have been Registered.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('4', 'Default Newsletter', 'Newsletter', 'This is a default newsletter template', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello [NAME]!&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You are receiving this email as a part of your newsletter subscription.         &lt;hr /&gt;\n            Here goes your newsletter content         &lt;hr /&gt;\n            &lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;         &lt;hr /&gt;\n            &lt;span style=&quot;font-size: 11px;&quot;&gt;&lt;em&gt;To stop receiving future newsletters please login into your account         and uncheck newsletter subscription box.&lt;/em&gt;&lt;/span&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('5', 'Transaction Completed', 'Payment Completed', 'This template is used to notify administrator on successful payment transaction', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have received new payment following:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Membership: &lt;strong&gt;[ITEMNAME]&lt;/strong&gt;&lt;br /&gt;\n            Price: &lt;strong&gt;[PRICE]&lt;/strong&gt;&lt;br /&gt;\n            Status: &lt;strong&gt;[STATUS] &lt;/strong&gt;&lt;br /&gt;\r\n            Processor: &lt;strong&gt;[PP] &lt;/strong&gt;&lt;br /&gt;\n            IP: &lt;strong&gt;[IP] &lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;&lt;em&gt;You can view this transaction from your admin panel&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('6', 'Transaction Suspicious', 'Suspicious Transaction', 'This template is used to notify administrator on failed/suspicious payment transaction', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color:#ccc&quot;&gt;Hello, Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align:left&quot;&gt;The following transaction has been disabled due to suspicious activity:&lt;br /&gt;\n            &lt;br /&gt;\n            Buyer: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Item: &lt;strong&gt;[ITEM]&lt;/strong&gt;&lt;br /&gt;\n            Price: &lt;strong&gt;[PRICE]&lt;/strong&gt;&lt;br /&gt;\n            Status: &lt;strong&gt;[STATUS]&lt;/strong&gt;&lt;/td&gt;\r\n            Processor: &lt;strong&gt;[PP] &lt;/strong&gt;&lt;br /&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align:left&quot;&gt;&lt;em&gt;Please verify this transaction is correct. If it is, please activate it in the transaction section of your site&#039;s &lt;br /&gt;\n            administration control panel. If not, it appears that someone tried to fraudulently obtain products from your site.&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('7', 'Welcome Email', 'Welcome', 'This template is used to welcome newly registered user when Configuration->Registration Verification and Configuration->Auto Registration are both set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('8', 'Membership Expire 7 days', 'Your membership will expire in 7 days', 'This template is used to remind user that membership will expire in 7 days', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, [NAME]&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;\n            &lt;h2 style=&quot;color: rgb(255, 0, 0);&quot;&gt;Your current membership will expire in 7 days&lt;/h2&gt;\n            Please login to your user panel to extend or upgrade your membership.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('9', 'Membership expired today', 'Your membership has expired', 'This template is used to remind user that membership had expired', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello, [NAME]&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;\n            &lt;h2 style=&quot;color: rgb(255, 0, 0);&quot;&gt;Your current membership has expired!&lt;/h2&gt;\n            Please login to your user panel to extend or upgrade your membership.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('10', 'Contact Request', 'Contact Inquiry', 'This template is used to send default Contact Request Form', '\n&lt;div align=&quot;center&quot;&gt;\n\t&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n\t\t&lt;tbody&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello Admin&lt;/th&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have a new contact request: &lt;hr /&gt;\n\t\t\t\t\t [MESSAGE] &lt;hr /&gt;\n\t\t\t\t\t From: &lt;span style=&quot;font-weight: bold;&quot;&gt;[SENDER] - [NAME]&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t\tTelephone: &lt;span style=&quot;font-weight: bold;&quot;&gt;[PHONE]&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t\tSubject: &lt;span style=&quot;font-weight: bold;&quot;&gt;[MAILSUBJECT]&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t\tSenders IP: &lt;span style=&quot;font-weight: bold;&quot;&gt;[IP]&lt;/span&gt;&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t&lt;/tbody&gt;\n\t&lt;/table&gt;&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('11', 'New Comment', 'New Comment Added', 'This template is used to notify admin when new comment has been added', '&lt;div align=&quot;center&quot;&gt;\n&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have a new comment post. You can login into your admin panel to view details:         &lt;hr /&gt;\n            [MESSAGE]         &lt;hr /&gt;\n            From: &lt;strong&gt;[SENDER] - [NAME]&lt;/strong&gt;&lt;br /&gt;\n            www: &lt;strong&gt;[WWW]&lt;/strong&gt;&lt;br /&gt;\n            Page Url: &lt;strong&gt;&lt;a href=&quot;[PAGEURL]&quot;&gt;[PAGEURL]&lt;/a&gt;&lt;/strong&gt;&lt;br /&gt;\n            Senders IP: &lt;strong&gt;[IP]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('12', 'Single Email', 'Single User Email', 'This template is used to email single user', '&lt;div align=&quot;center&quot;&gt;\n  &lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n      &lt;tr&gt;\n        &lt;th style=&quot;background-color:#ccc&quot;&gt;Hello [NAME]&lt;/th&gt;\n      &lt;/tr&gt;\n      &lt;tr&gt;\n        &lt;td valign=&quot;top&quot; style=&quot;text-align:left&quot;&gt;Your message goes here...&lt;/td&gt;\n      &lt;/tr&gt;\n      &lt;tr&gt;\n        &lt;td style=&quot;text-align:left&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n          [SITE_NAME] Team&lt;br /&gt;\n          &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n      &lt;/tr&gt;\n    &lt;/tbody&gt;\n  &lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('13', 'Notify Admin', 'New User Registration', 'This template is used to notify admin of new registration when Configuration->Registration Notification is set to YES', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Hello Admin&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have a new user registration. You can login into your admin panel to view details:&lt;hr /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Name: &lt;strong&gt;[NAME]&lt;/strong&gt;&lt;br /&gt;\n            IP: &lt;strong&gt;[IP]&lt;/strong&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('14', 'Registration Pending', 'Registration Verification Pending', 'This template is used to send Registration Verification Email, when Configuration->Auto Registration is set to NO', '&lt;div align=&quot;center&quot;&gt;\n&lt;table cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; border=&quot;0&quot; width=&quot;600&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 1px solid rgb(102, 102, 102);&quot;&gt;\n    &lt;tbody&gt;\n        &lt;tr&gt;\n            &lt;th style=&quot;background-color: rgb(204, 204, 204);&quot;&gt;Welcome [NAME]! Thanks for registering.&lt;/th&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Hello,&lt;br /&gt;\n            &lt;br /&gt;\n            You&#039;re now a member of [SITE_NAME].&lt;br /&gt;\n            &lt;br /&gt;\n            Here are your login details. Please keep them in a safe place:&lt;br /&gt;\n            &lt;br /&gt;\n            Username: &lt;strong&gt;[USERNAME]&lt;/strong&gt;&lt;br /&gt;\n            Password: &lt;strong&gt;[PASSWORD]&lt;/strong&gt;         &lt;hr /&gt;\n            The administrator of this site has requested all new accounts&lt;br /&gt;\n            to be activated by the users who created them thus your account&lt;br /&gt;\n            is currently pending verification process.&lt;/td&gt;\n        &lt;/tr&gt;\n        &lt;tr&gt;\n            &lt;td style=&quot;text-align: left;&quot;&gt;&lt;em&gt;Thanks,&lt;br /&gt;\n            [SITE_NAME] Team&lt;br /&gt;\n            &lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/em&gt;&lt;/td&gt;\n        &lt;/tr&gt;\n    &lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`) VALUES ('15', 'Offline Payment', 'Offline Notification', 'This template is used to send notification to a user when offline payment method is being used', '\n&lt;div align=&quot;center&quot; style=&quot;font-family: Arial,Helvetica,sans-serif; font-size: 13px; margin: 20px;&quot;&gt;\n\t&lt;table width=&quot;600&quot; cellspacing=&quot;5&quot; cellpadding=&quot;10&quot; border=&quot;0&quot; style=&quot;background: none repeat scroll 0% 0% rgb(244, 244, 244); border: 2px solid rgb(187, 187, 187);&quot;&gt;\n\t\t&lt;tbody&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;th style=&quot;background-color: rgb(204, 204, 204); font-size: 16px; padding: 5px; border-bottom: 2px solid rgb(255, 255, 255);&quot;&gt;Hello [NAME]&lt;/th&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;You have purchased the following:&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;[ITEMS]&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;Please send your payment to:&lt;br /&gt;\n\t\t\t\t\t&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left;&quot;&gt;[INFO]&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t\t&lt;tr&gt;\n\t\t\t\t&lt;td valign=&quot;top&quot; style=&quot;text-align: left; background-color: rgb(255, 255, 255); border-top: 2px solid rgb(204, 204, 204);&quot;&gt;&lt;span style=&quot;font-style: italic;&quot;&gt;Thanks,&lt;br /&gt;\n\t\t\t\t\t\t[SITENAME] Team&lt;br /&gt;\n\t\t\t\t\t\t&lt;a href=&quot;[URL]&quot;&gt;[URL]&lt;/a&gt;&lt;/span&gt;&lt;/td&gt;\n\t\t\t&lt;/tr&gt;\n\t\t&lt;/tbody&gt;\n\t&lt;/table&gt;&lt;/div&gt;');


-- --------------------------------------------------
# -- Table structure for table `gateways`
-- --------------------------------------------------
DROP TABLE IF EXISTS `gateways`;
CREATE TABLE `gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `displayname` varchar(255) NOT NULL,
  `dir` varchar(255) NOT NULL,
  `demo` tinyint(1) NOT NULL DEFAULT '1',
  `extra_txt` varchar(255) NOT NULL,
  `extra_txt2` varchar(255) NOT NULL,
  `extra_txt3` varchar(255) DEFAULT NULL,
  `extra` varchar(255) NOT NULL,
  `extra2` varchar(255) NOT NULL,
  `extra3` text,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `gateways`
-- --------------------------------------------------

INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('1', 'paypal', 'PayPal', 'paypal', '0', 'Email Address', 'Currency Code', 'Not in Use', 'paypal@address.com', 'CAD', '', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('2', 'moneybookers', 'MoneyBookers', 'moneybookers', '1', 'Email Address', 'Currency Code', 'Secret Passphrase', 'moneybookers@address.com', 'EUR', 'mypassphrase', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `demo`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('3', 'offline', 'Offline Payment', 'offline', '0', 'Not in Use', 'Not in Use', 'Instructions', '', '', 'Please submit all payments to:\nBank Name:\nBank Account:\netc...', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `language`
-- --------------------------------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `flag` varchar(2) DEFAULT NULL,
  `langdir` enum('ltr','rtl') DEFAULT 'ltr',
  `author` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `language`
-- --------------------------------------------------

INSERT INTO `language` (`id`, `name`, `flag`, `langdir`, `author`) VALUES ('1', 'English', 'en', 'ltr', 'http://www.wojoscripts.com');


-- --------------------------------------------------
# -- Table structure for table `layout`
-- --------------------------------------------------
DROP TABLE IF EXISTS `layout`;
CREATE TABLE `layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plug_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL DEFAULT '0',
  `modalias` varchar(30) DEFAULT NULL,
  `page_slug` varchar(50) DEFAULT NULL,
  `place` varchar(20) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_layout_id` (`page_id`),
  KEY `idx_plugin_id` (`plug_id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `layout`
-- --------------------------------------------------

INSERT INTO `layout` (`id`, `plug_id`, `page_id`, `mod_id`, `modalias`, `page_slug`, `place`, `position`) VALUES ('134', '27', '1', '0', '', 'Home', 'bottom', '26');
INSERT INTO `layout` (`id`, `plug_id`, `page_id`, `mod_id`, `modalias`, `page_slug`, `place`, `position`) VALUES ('31', '20', '13', '0', '', 'Contact', 'top', '19');


-- --------------------------------------------------
# -- Table structure for table `log`
-- --------------------------------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `failed` tinyint(5) NOT NULL,
  `failed_last` int(11) NOT NULL,
  `type` enum('system','admin','user') NOT NULL,
  `message` text NOT NULL,
  `info_icon` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `importance` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `log`
-- --------------------------------------------------

INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('1', 'admin', '127.0.0.1', '2014-03-06 12:13:10', '0', '0', 'user', 'User admin has successfully logged in.', 'user', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('2', 'admin', '127.0.0.1', '2014-03-06 12:36:54', '0', '0', 'system', '<span>Success!</span>System Configuration updated successfully!', 'config', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('3', 'admin', '127.0.0.1', '2014-03-06 12:44:47', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('4', 'admin', '127.0.0.1', '2014-03-06 12:45:22', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('5', 'admin', '127.0.0.1', '2014-03-06 12:46:07', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('6', 'admin', '127.0.0.1', '2014-03-06 12:57:52', '0', '0', 'system', 'Page <strong>All Modules</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('7', 'admin', '127.0.0.1', '2014-03-06 12:58:00', '0', '0', 'system', 'Page <strong>Demo Gallery Page</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('8', 'admin', '127.0.0.1', '2014-03-06 12:58:04', '0', '0', 'system', 'Page <strong>Event Calendar Demo</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('9', 'admin', '127.0.0.1', '2014-03-06 12:58:09', '0', '0', 'system', 'Page <strong>More Pages</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('10', 'admin', '127.0.0.1', '2014-03-06 12:58:18', '0', '0', 'system', 'Page <strong>Our Contact Info</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('11', 'admin', '127.0.0.1', '2014-03-06 12:58:22', '0', '0', 'system', 'Page <strong>Slideout Slider</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('12', 'admin', '127.0.0.1', '2014-03-06 12:58:29', '0', '0', 'system', 'Page <strong>Three Column Page</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('13', 'admin', '127.0.0.1', '2014-03-06 12:58:31', '0', '0', 'system', 'Page <strong>What is CMS pro!</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('14', 'admin', '127.0.0.1', '2014-03-06 12:58:34', '0', '0', 'system', 'Page <strong>Video Slider</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('15', 'admin', '127.0.0.1', '2014-03-06 13:02:28', '0', '0', 'system', 'Menu <strong>All Modules</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('16', 'admin', '127.0.0.1', '2014-03-06 13:02:41', '0', '0', 'system', 'Menu <strong>More Pages</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('17', 'admin', '127.0.0.1', '2014-03-06 13:02:44', '0', '0', 'system', 'Menu <strong>Full Page</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('18', 'admin', '127.0.0.1', '2014-03-06 13:02:46', '0', '0', 'system', 'Menu <strong>External Link</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('19', 'admin', '127.0.0.1', '2014-03-06 13:02:49', '0', '0', 'system', 'Menu <strong>About Us</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('20', 'admin', '127.0.0.1', '2014-03-06 13:04:34', '0', '0', 'system', '<span>Success!</span>Menu added successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('21', 'admin', '127.0.0.1', '2014-03-06 13:05:23', '0', '0', 'system', '<span>Success!</span>Menu added successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('22', 'admin', '127.0.0.1', '2014-03-06 13:06:47', '0', '0', 'system', '<span>Success!</span>Menu added successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('23', 'admin', '127.0.0.1', '2014-03-06 13:06:58', '0', '0', 'system', 'Menu <strong>Demo apps</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('24', 'admin', '127.0.0.1', '2014-03-06 13:07:16', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('25', 'admin', '127.0.0.1', '2014-03-06 13:08:32', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('26', 'admin', '127.0.0.1', '2014-03-06 13:10:57', '0', '0', 'system', 'Menu <strong>Three Columns</strong> deleted successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('27', 'admin', '127.0.0.1', '2014-03-06 14:31:49', '0', '0', 'system', '<span>Success!</span>System Configuration updated successfully!', 'config', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('28', 'admin', '127.0.0.1', '2014-03-06 15:16:34', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('29', 'admin', '127.0.0.1', '2014-03-06 15:39:28', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('30', 'admin', '127.0.0.1', '2014-03-06 15:39:46', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('31', 'admin', '127.0.0.1', '2014-03-06 15:41:09', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('32', 'admin', '127.0.0.1', '2014-03-06 15:41:32', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('33', 'admin', '127.0.0.1', '2014-03-06 15:43:34', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('34', 'admin', '127.0.0.1', '2014-03-06 15:45:17', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('35', 'admin', '127.0.0.1', '2014-03-06 15:50:02', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('36', 'admin', '127.0.0.1', '2014-03-06 15:51:09', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('37', 'admin', '127.0.0.1', '2014-03-06 15:52:26', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('38', 'admin', '127.0.0.1', '2014-03-06 15:52:39', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('39', 'admin', '127.0.0.1', '2014-03-06 15:58:48', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('40', 'admin', '127.0.0.1', '2014-03-06 15:59:13', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('41', 'admin', '127.0.0.1', '2014-03-06 16:03:13', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('42', 'admin', '127.0.0.1', '2014-03-06 16:03:23', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('43', 'admin', '127.0.0.1', '2014-03-06 16:03:38', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('44', 'admin', '127.0.0.1', '2014-03-06 16:03:54', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('45', 'admin', '127.0.0.1', '2014-03-06 16:04:07', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('46', 'admin', '127.0.0.1', '2014-03-06 16:04:20', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('47', 'admin', '127.0.0.1', '2014-03-06 16:04:35', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('48', 'admin', '127.0.0.1', '2014-03-06 16:04:46', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('49', 'admin', '127.0.0.1', '2014-03-06 16:04:56', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('50', 'admin', '127.0.0.1', '2014-03-06 16:05:10', '0', '0', 'system', '<span>Success!</span>Plugin updated successfully!', 'plugin', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('51', 'admin', '127.0.0.1', '2014-03-06 16:35:25', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('52', 'admin', '127.0.0.1', '2014-03-06 17:00:43', '0', '0', 'system', '<span>Success!</span>System Configuration updated successfully!', 'config', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('53', 'admin', '127.0.0.1', '2014-03-06 17:02:47', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('54', 'admin', '127.0.0.1', '2014-03-06 17:03:12', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('55', 'admin', '127.0.0.1', '2014-03-06 17:04:30', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('56', 'admin', '127.0.0.1', '2014-03-06 17:05:23', '0', '0', 'system', '<span>Success!</span>Menu updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('57', 'admin', '127.0.0.1', '2014-03-06 17:06:43', '0', '0', 'system', '<span>Success!</span>Content Page updated successfully!', 'content', 'no');
INSERT INTO `log` (`id`, `user_id`, `ip`, `created`, `failed`, `failed_last`, `type`, `message`, `info_icon`, `importance`) VALUES ('58', 'admin', '127.0.0.1', '2014-03-06 17:07:30', '0', '0', 'system', '<span>Success!</span>System Configuration updated successfully!', 'config', 'no');


-- --------------------------------------------------
# -- Table structure for table `memberships`
-- --------------------------------------------------
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `days` int(5) NOT NULL DEFAULT '0',
  `period` varchar(1) NOT NULL DEFAULT 'D',
  `trial` tinyint(1) NOT NULL DEFAULT '0',
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `memberships`
-- --------------------------------------------------

INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('1', 'Trial 7', 'This is 7 days trial membership...', '0.00', '7', 'D', '1', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('2', 'Basic 30', 'This is 30 days basic membership', '2.99', '1', 'M', '0', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('3', 'Basic 90', 'This is 90 days basic membership', '6.99', '90', 'D', '0', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('4', 'Platinum Subscription', 'Platinum Monthly Subscription.', '49.99', '1', 'Y', '0', '1', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('5', 'Weekly Access', 'This is 7 days basic membership', '1.99', '1', 'W', '0', '0', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `menus`
-- --------------------------------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `page_slug` varchar(50) NOT NULL,
  `mod_id` int(6) NOT NULL DEFAULT '0',
  `name_en` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `target` enum('_self','_blank') NOT NULL DEFAULT '_blank',
  `icon` varchar(50) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `home_page` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `content_id` (`active`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `menus`
-- --------------------------------------------------

INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('1', '0', '13', 'Contact', '0', 'Contact', 'Contact', 'page', '', '', '', '6', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('2', '0', '1', 'Home', '0', 'Home', 'Home', 'page', '', '', '', '1', '1', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('33', '0', '15', 'Team', '0', 'Team', 'Team', 'page', '', '', '', '5', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('34', '0', '0', '', '0', 'Login', 'Login', 'web', 'http://neoaisystems.com/login.php', '', '', '7', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('10', '18', '3', 'Our-Contact-Info', '0', 'New Submenu 3', 'New-Submenu-3', 'page', '', '', '', '21', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('30', '0', '9', 'Future', '0', 'Future', 'Future', 'page', '', '', '', '4', '0', '0');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('31', '0', '10', 'Products', '0', 'Products', 'Products', 'page', '', '', '', '2', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug`, `mod_id`, `name_en`, `slug`, `content_type`, `link`, `target`, `icon`, `position`, `home_page`, `active`) VALUES ('37', '0', '0', '', '0', 'NAS1000', 'NAS1000', 'web', 'http://neoaisystems.com/story.php', '', '', '3', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_adblock`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_adblock`;
CREATE TABLE `mod_adblock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_views_allowed` int(11) NOT NULL,
  `total_clicks_allowed` int(11) NOT NULL,
  `minimum_ctr` decimal(10,2) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `banner_image_link` varchar(255) NOT NULL,
  `banner_image_alt` varchar(255) NOT NULL,
  `banner_html` text NOT NULL,
  `block_assignment` varchar(255) NOT NULL,
  `total_views` int(11) NOT NULL,
  `total_clicks` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_adblock`
-- --------------------------------------------------

INSERT INTO `mod_adblock` (`id`, `title_en`, `created`, `start_date`, `end_date`, `total_views_allowed`, `total_clicks_allowed`, `minimum_ctr`, `banner_image`, `banner_image_link`, `banner_image_alt`, `banner_html`, `block_assignment`, `total_views`, `total_clicks`) VALUES ('1', 'My Campaign', '2013-01-12 06:02:21', '2013-01-04', '0000-00-00', '0', '0', '0.00', 'default.png', 'wojoscripts.com', 'Wojoscripts', '', 'adblock/Advert-Wojoscripts', '246', '4');


-- --------------------------------------------------
# -- Table structure for table `mod_adblock_memberlevels`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_adblock_memberlevels`;
CREATE TABLE `mod_adblock_memberlevels` (
  `adblock_id` int(11) NOT NULL,
  `memberlevel_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`adblock_id`,`memberlevel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_adblock_memberlevels`
-- --------------------------------------------------

INSERT INTO `mod_adblock_memberlevels` (`adblock_id`, `memberlevel_id`) VALUES ('1', '0');
INSERT INTO `mod_adblock_memberlevels` (`adblock_id`, `memberlevel_id`) VALUES ('1', '1');
INSERT INTO `mod_adblock_memberlevels` (`adblock_id`, `memberlevel_id`) VALUES ('1', '8');
INSERT INTO `mod_adblock_memberlevels` (`adblock_id`, `memberlevel_id`) VALUES ('1', '9');


-- --------------------------------------------------
# -- Table structure for table `mod_comments`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_comments`;
CREATE TABLE `mod_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `www` varchar(220) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(16) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_comments`
-- --------------------------------------------------

INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('1', '0', '2', 'Webmaster', '0', 'webmaster@wojoscripts.com', 'First comment is on me.', 'http://www.wojoscripts.com', '2011-01-30 16:34:55', '127.0.0.1', '1');
INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('2', '3', '2', 'Admin', '1', 'admin@mail.com', '<pre>Cum sociis natoque penatibus et <strong>magnis dis parturient</strong> montes, </pre>nascetur ridiculus mus. Nam nec odio nulla. Cras ullamcorper suscipit justo, at mattis odio auctor quis.', '', '2011-01-31 08:40:42', '127.0.0.1', '1');
INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('3', '5', '2', 'User1', '0', 'user1@mail.com', 'Ut dictum, eros eu blandit pellentesque, nisi nisl dapibus mauris, sed feugiat enim urna sit amet nibh. Suspendisse sed tortor nisi. Nulla facilisi. In sed risus in est cursus ornare....', '', '2011-01-31 08:45:54', '127.0.0.1', '1');
INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('4', '0', '2', 'User2', '0', 'user2@mail.com', 'Etiam non lacus ac velit <em>lobortis rutrum sed</em> id turpis. <code>Ut dictum, eros eu blandit pellentesque, nisi nisl dapibus mauris,</code>sed feugiat enim urna sit amet nibh. Suspendisse sed tortor nisi. Nulla facilisi. In sed risus in est cursus ornare. Fusce tempor hendrerit commodo.', '', '2011-01-31 08:48:26', '127.0.0.1', '1');
INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('5', '0', '2', 'User3', '0', 'user3@mail.com', 'In hac habit***e platea dictumst.ivamus leo diam, dignissim eu convallis in, posuere quis magna. Curabitur mollis, lectus sit amet bibendum faucibus, nisi ligula ultricies purus', '', '2011-01-31 08:51:25', '127.0.0.1', '1');
INSERT INTO `mod_comments` (`id`, `parent_id`, `page_id`, `username`, `user_id`, `email`, `body`, `www`, `created`, `ip`, `active`) VALUES ('6', '0', '2', 'User4', '0', 'user4@mail.com', 'Morbi sodales accumsan arcu sed venenatis. Vivamus leo diam, dignissim eu convallis in, posuere quis magna. Curabitur mollis, lectus sit amet bibendum faucibus, nisi ligula ultricies purus, in malesuada arcu sem ut mauris. Proin lobortis rutrum ultrices.', '', '2011-01-31 08:53:51', '127.0.0.1', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_comments_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_comments_config`;
CREATE TABLE `mod_comments_config` (
  `username_req` tinyint(1) NOT NULL DEFAULT '0',
  `email_req` tinyint(1) NOT NULL DEFAULT '0',
  `show_captcha` tinyint(1) NOT NULL DEFAULT '1',
  `show_www` tinyint(1) NOT NULL DEFAULT '0',
  `show_username` tinyint(1) DEFAULT '1',
  `show_email` tinyint(1) DEFAULT '1',
  `auto_approve` tinyint(1) NOT NULL DEFAULT '0',
  `notify_new` tinyint(1) NOT NULL DEFAULT '0',
  `public_access` tinyint(1) NOT NULL DEFAULT '0',
  `sorting` varchar(4) NOT NULL DEFAULT 'DESC',
  `blacklist_words` text,
  `char_limit` varchar(6) NOT NULL DEFAULT '400',
  `perpage` varchar(3) NOT NULL DEFAULT '10',
  `dateformat` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_comments_config`
-- --------------------------------------------------

INSERT INTO `mod_comments_config` (`username_req`, `email_req`, `show_captcha`, `show_www`, `show_username`, `show_email`, `auto_approve`, `notify_new`, `public_access`, `sorting`, `blacklist_words`, `char_limit`, `perpage`, `dateformat`) VALUES ('1', '1', '1', '1', '1', '0', '0', '0', '1', 'DESC', 'arse\narses\nass\nasses\nbollocks\ncrap', '400', '5', '%d %B %Y %H:%M');
INSERT INTO `mod_comments_config` (`username_req`, `email_req`, `show_captcha`, `show_www`, `show_username`, `show_email`, `auto_approve`, `notify_new`, `public_access`, `sorting`, `blacklist_words`, `char_limit`, `perpage`, `dateformat`) VALUES ('1', '1', '1', '1', '1', '0', '0', '0', '1', 'DESC', 'arse\narses\nass\nasses\nbollocks\ncrap', '400', '5', '%d %B %Y %H:%M');
INSERT INTO `mod_comments_config` (`username_req`, `email_req`, `show_captcha`, `show_www`, `show_username`, `show_email`, `auto_approve`, `notify_new`, `public_access`, `sorting`, `blacklist_words`, `char_limit`, `perpage`, `dateformat`) VALUES ('1', '1', '1', '1', '1', '0', '0', '0', '1', 'DESC', 'arse\narses\nass\nasses\nbollocks\ncrap', '400', '5', '%d %B %Y %H:%M');


-- --------------------------------------------------
# -- Table structure for table `mod_events`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_events`;
CREATE TABLE `mod_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `title_en` varchar(150) NOT NULL,
  `venue_en` varchar(150) DEFAULT NULL,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date DEFAULT '0000-00-00',
  `time_start` time DEFAULT '00:00:00',
  `time_end` time DEFAULT '00:00:00',
  `body_en` text,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_email` varchar(80) DEFAULT NULL,
  `contact_phone` varchar(16) DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_events`
-- --------------------------------------------------

INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('1', '1', 'Free Coffee for Each Monday', 'Office Rental Showroom', '2012-12-17', '2011-12-30', '11:18:00', '21:00:00', 'Vestibulum dictum elit eu risus porta egestas. Sed quis enim neque, sed  fringilla erat. Nunc feugiat tortor eu sem consequat aliquam. Cras non  nibh at lorem auctor interdum. Donec ut lacinia massa.', 'John Doe', 'john@gmail.com', '555-555-5555', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('2', '1', 'Lucky Draw', 'Office Rental Showroom', '2013-02-20', '2013-02-20', '13:30:00', '19:30:00', '\n&lt;p&gt;&lt;img src=&quot;uploads/images/pages/thumbs/thumb_demo_1.jpg&quot; alt=&quot;thumb_demo_1.jpg&quot; class=&quot;img-left&quot; /&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla posuere nibh auctor urna tincidunt fringilla. &lt;br /&gt;\n\tDonec imperdiet, orci quis aliquet laoreet, magna purus semper ligula, sit amet aliquam sapien enim in orci. Pellentesque at iaculis nibh.&lt;/p&gt; ', 'John Doe', 'john@gmail.com', '555-555-5555', '008000', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('3', '1', 'E-Commerce Seminar', 'Office Rental Showroom', '2011-12-19', '2011-12-26', '09:30:00', '13:30:00', 'Proin nec nisl est, id ornare lacus. Etiam mauris neque, scelerisque ut  ultrices vel, blandit et nisi. Nam commodo fermentum lectus vulputate  auctor. Maecenas hendrerit sapien sit amet erat mollis venenatis nec sit', 'John Doe', 'john@gmail.com', '555-555-5555', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('4', '1', 'E-Commerce Seminar II', 'Office Rental Showroom', '2011-12-19', '2011-12-22', '17:00:00', '19:00:00', 'Aliquam auctor molestie ipsum ultricies tincidunt. Suspendisse potenti.  Nulla volutpat urna et mi consectetur placerat iaculis lacus lacinia.  Integer a nisi id diam tempus commodo eget a tellus. In consequat augue  nec tortor bibendum vel semper metus sodales. Donec ut dui nisi, id  posuere augue.', 'John Doe', 'john@gmail.com', '555-555-5555', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('5', '1', 'New Year', 'New Year&#039;s Day in Canada', '2012-01-01', '2012-01-01', '00:00:00', '00:00:00', 'According to the Gregorian calendar, used in Canada and many other countries, January 1 is the first day of a new year. This date is commonly known as New Year&#039;s Day and is a statutory holiday in all Canadian provinces and territories.', '', '', '', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('6', '1', 'Epiphany', 'Epiphany in Canada', '2012-01-06', '2012-01-06', '00:00:00', '00:00:00', 'Epiphany is celebrated in Canada on January 6 each year. It remembers the three wise menÃ¢â‚¬â„¢s visit to baby Jesus and his baptism, according to events in the Christian Bible. Mummers or naluyuks may visit homes in Newfoundland and Labrador at this time of the year.', '', '', '', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('7', '1', 'Groundhog Day', 'Groundhog Day in Canada', '2012-12-27', '2012-09-26', '13:30:00', '00:00:00', 'Many Canadians take the time to observe Groundhog Day on February 2 each year, which is also Candlemas. Groundhog Day in Canada focuses on the concept of a groundhog coming out of its home in mid-winter to &quot;predictÃ¢â‚¬Â if spring is on its way in the northern hemisphere.', '', '', '', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('8', '1', 'Valentine&#039;s Day', 'Valentine&#039;s Day in Canada', '2012-02-14', '2012-02-14', '00:00:00', '00:00:00', 'Valentine&#039;s Day is an opportunity for people in Canada to tell somebody that they love them in a romantic way. It falls on February 14, the name day of two saints, St Valentine of Rome and St Valentine of Terni. In pre-Christian times, the middle of February was a time of pagan fertility festivals in Europe and allegedly the time when birds chose a mate.', '', '', '', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('9', '1', 'Recurring Event 2', 'Recurring Demo Event 2', '2012-04-16', '2012-04-16', '00:00:00', '00:00:00', 'Family Day is observed in the Canadian provinces of Alberta, Ontario and Saskatchewan on the third Monday of February. This holiday celebrates the importance of families and family life to people and their communities.', '', '', '', '', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('10', '1', 'Recurring Event', 'Recurring Demo Event', '2012-04-17', '2012-05-02', '11:00:00', '16:00:00', 'This event shows recurring feature in event manager&lt;br /&gt;\n', '', '', '', '', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_events_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_events_data`;
CREATE TABLE `mod_events_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `color` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_events_data`
-- --------------------------------------------------

INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('20', '10', '2012-06-26', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('19', '10', '2012-06-25', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('18', '10', '2012-06-24', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('17', '10', '2012-06-23', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('16', '10', '2012-06-22', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('15', '10', '2012-06-21', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('14', '10', '2012-06-20', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('13', '9', '2012-12-27', '09F');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('12', '9', '2012-06-24', '09F');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('11', '10', '2012-04-17', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('21', '10', '2012-04-27', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('22', '10', '2012-04-28', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('23', '10', '2012-04-29', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('24', '10', '2012-04-30', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('25', '10', '2012-05-01', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('26', '10', '2012-05-02', 'F00');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('27', '9', '2012-04-18', '09F');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('48', '7', '2011-12-27', 'ff9900');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('50', '2', '2013-02-20', 'ffffff');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('43', '7', '2012-12-27', 'ff9900');
INSERT INTO `mod_events_data` (`id`, `event_id`, `event_date`, `color`) VALUES ('42', '1', '2012-12-17', 'ff9900');


-- --------------------------------------------------
# -- Table structure for table `mod_gallery_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_gallery_config`;
CREATE TABLE `mod_gallery_config` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) DEFAULT NULL,
  `folder` varchar(30) DEFAULT NULL,
  `rows` int(4) NOT NULL DEFAULT '0',
  `thumb_w` int(4) NOT NULL DEFAULT '0',
  `thumb_h` int(4) NOT NULL DEFAULT '0',
  `image_w` int(4) NOT NULL DEFAULT '0',
  `image_h` int(4) NOT NULL DEFAULT '0',
  `watermark` tinyint(1) NOT NULL DEFAULT '0',
  `method` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_gallery_config`
-- --------------------------------------------------

INSERT INTO `mod_gallery_config` (`id`, `title_en`, `folder`, `rows`, `thumb_w`, `thumb_h`, `image_w`, `image_h`, `watermark`, `method`, `created`) VALUES ('1', 'Demo Gallery', 'demo', '5', '150', '120', '500', '300', '1', '1', '2010-12-10 12:10:10');


-- --------------------------------------------------
# -- Table structure for table `mod_gallery_images`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_gallery_images`;
CREATE TABLE `mod_gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(6) NOT NULL DEFAULT '0',
  `title_en` varchar(100) DEFAULT NULL,
  `description_en` varchar(250) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `width` varchar(4) NOT NULL DEFAULT '100',
  `height` varchar(4) NOT NULL DEFAULT '100',
  `sorting` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_gallery_images`
-- --------------------------------------------------

INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('1', '1', 'Demo Flower 1', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_318C0B-0F1A63-7096C7-45B182-87004D-FDF0AE.jpg', '100', '100', '4');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('2', '1', 'Demo Flower 2', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_D45A84-11B3CB-E2E617-8CE590-EB95CB-4C40CF.jpg', '100', '100', '2');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('4', '1', 'Demo Flower 4', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_2822AC-941D16-C5ECEB-4C2787-015575-77FEE8.jpg', '100', '100', '5');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('5', '1', 'Demo Flower 5', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_260FA3-1C8BE1-890AFD-8F20ED-47EB05-EBDFF7.jpg', '100', '100', '7');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('6', '1', 'Demo Flower 6', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_755459-EC4B6C-58E134-2907AA-36BFEC-2604A5.jpg', '100', '100', '11');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('7', '1', 'Demo Flower 7', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_7810C6-0B129B-B97C0D-902867-748A5F-854706.jpg', '100', '100', '8');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('8', '1', 'Demo Flower 8', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_901142-405DB2-4B327C-6418D7-B92E53-CC1FA7.jpg', '100', '100', '14');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('9', '1', 'Demo Flower 9', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_F87715-1EAFB8-D4E516-77E233-215B0A-507EBB.jpg', '100', '100', '16');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('10', '1', 'Demo Flower 10', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_0D08C0-3FFF26-A5D741-BA76C6-F3C61F-D67093.jpg', '100', '100', '12');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('11', '1', 'Demo Flower 11', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_807CA0-B0AB7C-FF9BB6-E4E678-B9A38A-7A81FB.jpg', '100', '100', '17');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('12', '1', 'Demo Flower 12', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_7CF0A7-55F94C-0B0AE0-A4BF0C-476BF7-82CCE0.jpg', '100', '100', '18');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('13', '1', 'Demo Flower 13', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_E1A872-9BDEED-5CA577-3CA6F1-E2545B-DBCF15.jpg', '100', '100', '19');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('14', '1', 'Demo Flower 14', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_2D4A9D-9D3E9E-047D5A-49CC85-4B02A6-1F3BB6.jpg', '100', '100', '20');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('15', '1', 'Demo Flower 15', 'Fusce hendrerit vulputate rutrum. Phasellus in quam a mi fringilla ultrices.', 'IMG_886FAF-5199A3-9758FB-406A40-59CDF0-C5C3C9.jpg', '100', '100', '10');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('43', '1', 'Img Title', 'Img Description', 'IMG_3DF477-E7126F-D2F18C-828279-F705F6-0BCADD.jpg', '100', '100', '1');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('44', '1', 'Img Title', 'Img Description', 'IMG_F777C1-C44526-82610C-6CC77A-201577-1506C3.jpg', '100', '100', '6');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('49', '0', '-/-', '-/-', 'IMG_9904CA-C51FE1-EA15CF-2501CF-481509-138002.jpg', '100', '100', '0');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('50', '0', '-/-', '-/-', 'IMG_D953B0-4F2CF6-F02471-506FD1-75C54B-083D9F.jpg', '100', '100', '0');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('51', '0', '-/-', '-/-', 'IMG_84AE2E-6A2767-08943C-856310-C5ADD7-4530F2.jpg', '100', '100', '0');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('42', '1', 'Img Title', 'Img Description', 'IMG_B088B3-7D5A42-EA4B0C-A1456E-D3A607-90FEF4.jpg', '100', '100', '15');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('46', '1', 'Img Title', 'Img Description', 'IMG_0BE73A-3E63CF-F5D32D-E63080-79F417-8485BE.jpg', '100', '100', '3');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('47', '1', 'Img Title', 'Img Description', 'IMG_150E14-D776CC-77F93E-FD3449-C3059F-EBAE57.jpg', '100', '100', '13');
INSERT INTO `mod_gallery_images` (`id`, `gallery_id`, `title_en`, `description_en`, `thumb`, `width`, `height`, `sorting`) VALUES ('48', '1', 'Img Title', 'Img Description', 'IMG_C0241E-1D9D71-050CE5-BEAD8F-EFAD10-482202.jpg', '100', '100', '9');


-- --------------------------------------------------
# -- Table structure for table `modules`
-- --------------------------------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(120) NOT NULL,
  `show_title` tinyint(1) NOT NULL DEFAULT '0',
  `info_en` text,
  `modalias` varchar(50) NOT NULL,
  `hasconfig` tinyint(1) NOT NULL DEFAULT '0',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey_en` varchar(200) DEFAULT NULL,
  `metadesc_en` text,
  `theme` varchar(50) DEFAULT NULL,
  `ver` varchar(4) DEFAULT '1.00',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `modules`
-- --------------------------------------------------

INSERT INTO `modules` (`id`, `title_en`, `show_title`, `info_en`, `modalias`, `hasconfig`, `system`, `created`, `metakey_en`, `metadesc_en`, `theme`, `ver`, `active`) VALUES ('1', 'Comments', '0', 'Encourage your readers to join in the discussion and leave comments and respond promptly to the comments left by your readers to make them feel valued', 'comments', '1', '0', '2011-01-10 14:10:24', '', '', '', '1.00', '1');
INSERT INTO `modules` (`id`, `title_en`, `show_title`, `info_en`, `modalias`, `hasconfig`, `system`, `created`, `metakey_en`, `metadesc_en`, `theme`, `ver`, `active`) VALUES ('2', 'Gallery', '0', 'Fully featured gallery module', 'gallery', '1', '0', '2011-04-28 06:19:32', '', '', '', '1.00', '1');
INSERT INTO `modules` (`id`, `title_en`, `show_title`, `info_en`, `modalias`, `hasconfig`, `system`, `created`, `metakey_en`, `metadesc_en`, `theme`, `ver`, `active`) VALUES ('3', 'Event Manager', '0', 'Easily publish and manage your company events.', 'events', '1', '0', '2011-11-22 14:18:10', '', '', '', '1.05', '1');
INSERT INTO `modules` (`id`, `title_en`, `show_title`, `info_en`, `modalias`, `hasconfig`, `system`, `created`, `metakey_en`, `metadesc_en`, `theme`, `ver`, `active`) VALUES ('4', 'AdBlock', '0', 'Manage Ad Campaigns', 'adblock', '1', '0', '2012-12-24 22:22:22', '', '', '', '1.00', '1');


-- --------------------------------------------------
# -- Table structure for table `pages`
-- --------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `contact_form` tinyint(1) NOT NULL DEFAULT '0',
  `membership_id` varchar(20) NOT NULL DEFAULT '0',
  `module_id` int(4) NOT NULL DEFAULT '0',
  `module_data` varchar(100) NOT NULL DEFAULT '0',
  `module_name` varchar(50) DEFAULT NULL,
  `access` enum('Public','Registered','Membership') NOT NULL DEFAULT 'Public',
  `keywords_en` text NOT NULL,
  `description_en` text NOT NULL,
  `body_en` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `pages`
-- --------------------------------------------------

INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('1', 'Home', 'Home', '0', '0', '0', '0', '', 'Public', '', '', '&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\tAt Melbourne-based Neo AI Systems P/L (NAS) we&#039;re building the first general purpose AI. That means software that actually understands (and generates) everyday language and can learn about the world.&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;\n\t\t\t\t\t\t\t\t\t&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-weight: bold; font-size: 12pt; color: rgb(0, 40, 146);&quot;&gt;AI that understands us&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Our human-like AI system, NAS9000, under development, would solve the Natural Language Understanding (NLU) problem to vastly improve internet search, enable advanced human-machine interfaces for business, automate software development, power automatic call centres and usher in an age of personalized education. And that&#039;s just the tip of the iceberg.&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;NLU is inherently difficult and yet even minor breakthroughs in NLU will prove hugely beneficial.&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Today pattern matching &#039;machine learning&#039; AI techniques are transforming business analytics. But such systems merely act as highly advanced classifiers. Free-form NLU itself has so far eluded machine&amp;nbsp;learning&amp;nbsp;approaches because the input space is of&amp;nbsp;extreme&amp;nbsp;variability (even a low vocabulary quickly leads to an astronomical number of potential inputs), high ambiguity (many phrases carry multiple interpretations) and high redundancy (&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;many constructions convey the same meaning&lt;/span&gt;) but&amp;nbsp;also because the output space is extremely rich: a quick answer, an extended&amp;nbsp;monologue, a question, an internal world-view, an opinion, a summary or a report for example. &lt;/span&gt;&lt;br /&gt;\n\t&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-weight: bold; font-size: 12pt;&quot;&gt;&lt;span style=&quot;color: rgb(0, 40, 146);&quot;&gt;Neuroscience inspired&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Our approach is consistent with the partly&amp;nbsp;controversial&amp;nbsp;writings of AI luminaries including Jeff Hawkins (Palm &amp;amp; Grok) and Ray Kurzweil (Nuance &amp;amp; Google) that neuroscience has already shown us the&amp;nbsp;way, the brain is comprehensible and the underlying &#039;program&#039; is almost the&amp;nbsp;same&amp;nbsp;throughout most of the brain.&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;The brain is doing almost the same thing everywhere!&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;That is very&amp;nbsp;promising&amp;nbsp;for AI research.&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;At NAS we&#039;re carefully applying our NASX proprietary&amp;nbsp;algorithm to different levels of abstraction in language and solving the input/output variability, redundancy, ambiguity&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;and richness&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;&amp;nbsp;problems inherent in NLU by discovering the hierarchies of learning required and the associated feedback and feed-forward connections.&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-weight: bold; font-size: 12pt; color: rgb(0, 40, 146);&quot;&gt;&lt;span style=&quot;color: rgb(0, 40, 146); font-size: 16px; font-weight: bold; background-color: rgb(255, 255, 255);&quot;&gt;Product pipeline&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;/blockquote&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;So far we&#039;ve released the very simple NAS1000 system based on the NASX technology in March 2014 that processes simple sentences and discourses at several levels of complexity. The&amp;nbsp;&lt;span style=&quot;color: rgb(129, 129, 129); font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;&lt;a href=&quot;http://neoaisystems.com/story.php&quot;&gt;NAS1000&lt;/a&gt;&amp;nbsp;beta is currently online waiting for your test run. It makes some headway into the understanding and answering of questions about Grade 3 English comprehension exercises.&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\tWe are rapidly progressing towards NAS2000, a mid-point release in approximately September 2014.&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/span&gt;\n&lt;div&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;We hope to have the world&#039;s first general purpose AI, NAS9000 ready for release sometime in 2015.&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/div&gt;', '2010-07-22 20:11:55', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('9', 'Future', 'Future', '0', '0', '0', '0', '', 'Public', '', '', '&lt;div class=&quot;post-body&quot;&gt;\n\t&lt;p&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;We are already building applications&amp;nbsp;for the NASX000 series AI engines. Below are applications&amp;nbsp;we plan to release beginning&amp;nbsp;September&amp;nbsp; 2014, before we have&amp;nbsp;perfected&amp;nbsp;NAS9000.&amp;nbsp;These&amp;nbsp;applications&amp;nbsp;will, in many real-life cases, benefit from less-than-perfect accuracy natural language understanding.&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\tBelow are SIMULATIONS of what some of these applications could achieve very soon!&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt; font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Web search CONCEPT APP&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;Check out the &lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;a href=&quot;/Web-search-CONCEPT-APP.html&quot;&gt;NAS Web Search&lt;/a&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt; concept app now! See what web&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;search&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;might&amp;nbsp;be like in late 2014 with the help of the NAS2000 engine.&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255); font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Automated call centre CONCEPT APP&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Coming soon.&lt;/span&gt;&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255); font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Web search CONCEPT APP&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Coming soon.&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t&lt;/span&gt;&lt;br /&gt;\n\t\t\t&lt;span style=&quot;font-size: 12pt; font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;NAS9000 CONCEPT APPS&lt;/span&gt;&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;We intend to&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;foresee&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;what NAS9000 will be capable of once we have delivered successful NAS2000 applications. &lt;span style=&quot;font-size: medium; background-color: rgb(255, 255, 255);&quot;&gt;We&amp;nbsp;anticipate&amp;nbsp;that the NAS9000 engine will&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 16px;&quot;&gt;vastly improve internet search, enable advanced human-machine interfaces for business, &lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;automate software development,&amp;nbsp;&lt;/span&gt;power automatic call centres and usher in an age of personalized education.&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t&lt;br /&gt;\n\t\t\t\tIn any case, don&#039;t take your eyes of those payload bay doors . .&lt;br /&gt;\n\t\t\t\t&lt;br /&gt;\n\t\t\t\t&lt;br /&gt;\n\t\t\t\t&lt;/span&gt;&lt;/span&gt;&lt;img src=&quot;../../../../../../../../../uploads/HAL.jpeg&quot; alt=&quot;&quot; border=&quot;0&quot; style=&quot;margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;&quot; /&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t\t\t&amp;nbsp;&lt;/span&gt;&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\t&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;/p&gt;&lt;/div&gt;', '2011-05-19 15:28:29', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('10', 'Products', 'Products', '0', '0', '0', '0', '', 'Public', '', '&lt;br /&gt;', '&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\tOur pipeline of products is comprised of the NASX technology-based series culminating in NAS9000, a general purpose AI engine and associated interfaces capable of learning, understanding and generating&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;natural&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;language and world-view building.&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt; font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;NAS1000 Proof-of-principle (RELEASED)&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;NAS1000, released in March 2014 as an online&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;demonstration&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;app, is a proof-of-principle trainable and query-able online AI app capable of understanding and generating simple&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;natural&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;language&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;text, &lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;&amp;nbsp;and world-view &lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;building&lt;/span&gt;,&lt;/span&gt;&amp;nbsp;with 70% accuracy (measured).&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Try out &lt;a href=&quot;http://neoaisystems.com/story.php&quot;&gt;NAS1000&lt;/a&gt; online right now!&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255); font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;NAS2000 Beta (September 2014)&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;NAS2000, &lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;a half-way point to NAS9000&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;to be released in September 2014, is a trainable and query-able AI engine and set of interfaces capable of understanding intermediate natural language texts&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;and world-view building,&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;with 80% projected accuracy.&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255); font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;NAS9000 (2015)&lt;/span&gt;&lt;br style=&quot;font-size: 16px;&quot; /&gt;\n\t\t&lt;br style=&quot;font-size: 16px;&quot; /&gt;\n\t\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255);&quot;&gt;NAS9000, to be released in 2015, is a general purpose AI engine with associated interfaces&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;capable of understanding and&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;generating&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;arbitrary&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;natural&lt;/span&gt;&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255);&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;language&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;text&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;and world-view building,&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;with over 95% projected accuracy. &lt;br /&gt;\n\t\t\t\t&lt;br /&gt;\n\t\t\t\tWe&amp;nbsp;anticipate&amp;nbsp;that the NAS9000 engine will &lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;vastly improve internet search, enable advanced human-machine interfaces for business, &lt;span style=&quot;font-size: 16px; background-color: rgb(255, 255, 255);&quot;&gt;automate software development,&amp;nbsp;&lt;/span&gt;power automatic call centres and usher in an age of personalized education.&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;\n\t\t\t&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/p&gt;', '2011-05-19 15:28:48', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('13', 'Contact', 'Contact', '0', '0', '0', '0', '', 'Public', '', '', '&lt;span style=&quot;font-size: medium;&quot;&gt;&lt;br /&gt;\n\tPlease don&#039;t hesitate to contact us.&lt;br /&gt;\n\t&lt;br /&gt;\n\tPaul Pallaghy, PhD&lt;br /&gt;\n\t&lt;span style=&quot;font-style: italic; font-size: 12pt;&quot;&gt;CEO/CTO/Founder&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;font-weight: bold; font-size: 12pt;&quot;&gt;Neo AI Systems P/L&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;E &amp;nbsp; &amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;a href=&quot;paul.k.pallaghy@gmail.com&quot; target=&quot;_blank&quot; title=&quot;paul.k.pallaghy@gmail.com&quot;&gt;paul.k.pallaghy@gmail.com&lt;/a&gt;&lt;br /&gt;\n\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;M &amp;nbsp; &amp;nbsp;0411 091 999&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\tLevel 4, 863 High Street&lt;br /&gt;\n\tArmadale 3143&lt;br /&gt;\n\tVictoria, Australia&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;ACN 165 825 347&lt;/span&gt;&lt;br /&gt;\n\t&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;', '2012-01-01 22:08:53', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('15', 'Team', 'Team', '0', '0', '0', '0', '', 'Public', '', '', '&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;NEO AI Systems P/L is a Melbourne-based AI startup company constituting an executive, administrative and technical team, an Advisory Board, a prototype, product pipeline and&amp;nbsp;associated&amp;nbsp;IP.&lt;/span&gt;&lt;br /&gt;\n\t&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;&lt;span style=&quot;font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Dr. Paul Pallaghy, BSc(Hons) PhD&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;color: rgb(73, 73, 73); font-style: italic;&quot;&gt;Founder, CEO &amp;amp; CTO, Neo AI Systems P/L&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t\n\t\t&lt;div&gt;Dr. Pallaghy is a PhD computational physicist, data scientist and systems engineer with a track record in multiple technology startups, CSIRO and academia.&amp;nbsp;&lt;br /&gt;\n\t\t\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t\t\tInventor of multiple patentable and/or peer-reviewed technologies in AI and informatics. Achievements include development of the NASX AI intellectual property (1998-2014), the first consumer instant desktop search software (2004) and $1M-$10M industrial plant designs, contract negotiations, production managements and installations across 3 Australian states and China (2010-2012). Published peer-reviewed papers covering informatics, computational physics and biophysics, including multiple works with over 200 citations. Winner multiple academic awards and grants and repeat invited speaker at workshops and conferences. Snr Systems Engineer, CSIRO Project Leader, University of Melbourne Group Leader, Bioinformatics &amp;amp; Genomics lecturer, NH&amp;amp;MRC Research Fellow, Treasurer of the Australian Society for Biophysics.&lt;/span&gt;&lt;/div&gt;&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;View Dr. Pallaghy&#039;s &lt;a href=&quot;http://scholar.google.com.au/citations?user=atu6WaUAAAAJ&quot; target=&quot;_blank&quot;&gt;Google Scholar&lt;/a&gt; profile and &lt;a href=&quot;http://www.linkedin.com/pub/paul-pallaghy/51/a41/632&quot; target=&quot;_blank&quot;&gt;LinkedIn&lt;/a&gt; profile.&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt; background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Justine Pallaghy, BComm CPA&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;color: rgb(73, 73, 73); font-size: 16px; font-style: italic; background-color: rgb(255, 255, 255);&quot;&gt;CFO, Neo AI Systems P/L&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;Justine Pallaghy is a CPA accountant with very significant experience in accounting public practise and business&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;administration&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;including oversight of R&amp;amp;D grants.&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255); font-weight: bold;&quot;&gt;&lt;span style=&quot;color: rgb(0, 40, 146);&quot;&gt;Dr. Chris Belyea, BSc (Hons) PhD&lt;/span&gt;&lt;br /&gt;\n\t\t\t\t&lt;/span&gt;&lt;/span&gt;&lt;i style=&quot;color: rgb(73, 73, 73);&quot;&gt;Advisory Board member,&amp;nbsp;&lt;/i&gt;&lt;/span&gt;&lt;/span&gt;&lt;i style=&quot;font-size: 12pt;&quot;&gt;\n\t&lt;div style=&quot;display: inline !important;&quot;&gt;&lt;span style=&quot;color: rgb(73, 73, 73);&quot;&gt;Principal, Belyea IP&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;/div&gt;&lt;/i&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255);&quot;&gt;&lt;span style=&quot;background-color: rgb(255, 255, 255); font-size: 12pt;&quot;&gt;\n\t\t&lt;div&gt;Dr. Belyea is a non-executive director of an ASX listed technology company and practises as a Registered Patent Attorney. He spent several years working in industry in computer control software and artificial intelligence applied to energy management, mining and the steel industry, and has been responsible for assisting, managing and listing on the stock exchange several technology companies.&lt;/div&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;font-weight: bold; color: rgb(0, 40, 146);&quot;&gt;Arjang Assadi&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;span style=&quot;color: rgb(73, 73, 73); background-color: rgb(255, 255, 255); font-style: italic;&quot;&gt;Advisory Board member&lt;/span&gt;&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;br /&gt;\n\t&lt;span style=&quot;font-size: 12pt;&quot;&gt;Arjang Assadi, an experienced commercial software developer, advises us on&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;software&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-size: 16px;&quot;&gt;development, database and hosting&lt;/span&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&amp;nbsp;strategies.&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;br /&gt;\n\t\t&lt;/span&gt;&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/span&gt;', '2014-03-06 17:19:24', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('16', 'NAS1000', 'NAS1000', '0', '0', '0', '0', '', 'Public', '', '', '', '2014-03-10 18:49:07', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('17', 'Web search CONCEPT APP', 'Web-search-CONCEPT-APP', '0', '0', '0', '0', '', 'Public', '', '', '', '2014-03-10 18:49:24', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('18', 'Automatic call centre CONCEPT APP', 'Automatic-call-centre-CONCEPT-APP', '0', '0', '0', '0', '', 'Public', '', '', '', '2014-03-10 18:49:58', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('19', 'Personalized education CONCEPT APP', 'Personalized-education-CONCEPT-APP', '0', '0', '0', '0', '', 'Public', '', '', '', '2014-03-10 18:50:30', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('20', 'test', 'test', '0', '0', '0', '0', '', 'Public', '', '', 'Test', '2014-03-17 04:26:02', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('21', 'test', 'test', '0', '0', '0', '0', '', 'Public', '', '', 'Test', '2014-03-17 04:26:03', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug`, `contact_form`, `membership_id`, `module_id`, `module_data`, `module_name`, `access`, `keywords_en`, `description_en`, `body_en`, `created`, `active`) VALUES ('22', 'testr', 'tester', '0', '0', '0', '0', '', 'Public', ',mn', 'mn,', 'Hi Test&lt;br /&gt;', '2014-03-17 05:58:33', '1');


-- --------------------------------------------------
# -- Table structure for table `payments`
-- --------------------------------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(100) DEFAULT NULL,
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate_amount` varchar(255) NOT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `date` datetime NOT NULL,
  `pp` enum('PayPal','MoneyBookers') DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `payments`
-- --------------------------------------------------

INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('1', '', '2', '1', '5.00', '', '2013-01-09 14:12:32', 'PayPal', '', '1');
INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('2', '', '2', '2', '5.00', '', '2013-01-03 14:12:32', 'PayPal', '', '1');
INSERT INTO `payments` (`id`, `txn_id`, `membership_id`, `user_id`, `rate_amount`, `currency`, `date`, `pp`, `ip`, `status`) VALUES ('3', '', '3', '3', '10.00', '', '2013-01-04 16:47:36', 'MoneyBookers', '', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_content_slider`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_content_slider`;
CREATE TABLE `plug_content_slider` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) DEFAULT NULL,
  `description_en` text,
  `filename` varchar(50) DEFAULT NULL,
  `align` tinyint(1) NOT NULL DEFAULT '0',
  `position` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_content_slider`
-- --------------------------------------------------

INSERT INTO `plug_content_slider` (`id`, `title_en`, `description_en`, `filename`, `align`, `position`) VALUES ('1', 'Just a Background Image', 'Erat. Pellentesque erat. Mauris vehicula vestibulum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pulvinar est. Integer urna. Pellentesque pulvinar dui a magna. Nulla facilisi. Proin imperdiet. Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. &lt;br /&gt;\r\n&lt;br /&gt;\r\nVestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur...&lt;br /&gt; Nea fi pasko s\'joro definitive. Ina pobo nevo vo. Fore predikativo ba iel, tiel pleja mikrometro er kie. Ali neniaÄµo anstataÅ­e ac, sen dato tele he, ene u unuj onklo esceptinte. ', 'FILE_D63F4A-0AF465-166E89-3AB392-A3DDBF-A77698.jpg', '0', '1');
INSERT INTO `plug_content_slider` (`id`, `title_en`, `description_en`, `filename`, `align`, `position`) VALUES ('2', 'Content with linked button', 'Pellentesque erat. Mauris vehicula vestibulum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pulvinar est. Integer urna. Pellentesque pulvinar dui a magna. Nulla facilisi. Proin imperdiet. Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. &lt;br /&gt;\r\n&lt;br /&gt;\r\nVestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;a class=&quot;button&quot; href=&quot;#&quot;&gt;Read More...&lt;/a&gt;&lt;br /&gt; ', 'FILE_C6D5B5-050CF5-28C7B4-9591DF-B88E06-8CF47C.jpg', '0', '2');
INSERT INTO `plug_content_slider` (`id`, `title_en`, `description_en`, `filename`, `align`, `position`) VALUES ('3', 'Content with an additional image', '&lt;img width=&quot;290&quot; height=&quot;119&quot; align=&quot;left&quot; class=&quot;image&quot; src=&quot;uploads/images/pages/demo_1.jpg&quot; title=&quot;&quot; alt=&quot;CMS pro!&quot; style=&quot;margin-bottom: 15px; margin-right: 15px;&quot; /&gt;Pellentesque erat. Mauris vehicula vestibulum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pulvinar est. Integer urna. Pellentesque pulvinar dui a magna. Nulla facilisi. Proin imperdiet. Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. Vestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur.&lt;br /&gt;\r\n', 'FILE_C4B436-1D7C31-7F2B49-CCB423-9E14CC-DFC38B.jpg', '0', '3');
INSERT INTO `plug_content_slider` (`id`, `title_en`, `description_en`, `filename`, `align`, `position`) VALUES ('4', 'Content aligned to the right', 'Pellentesque erat. Mauris vehicula vestibulum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pulvinar est. Integer urna. Pellentesque pulvinar dui a magna. Nulla facilisi. Proin imperdiet. Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. Vestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur&lt;br /&gt;\r\n&lt;br /&gt;\r\nVestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;a href=&quot;#&quot; class=&quot;button&quot;&gt;Read More...&lt;/a&gt;', 'FILE_860000-15C6EA-3AE42A-503B48-F2C4CD-536821.jpg', '1', '4');


-- --------------------------------------------------
# -- Table structure for table `plug_donate`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_donate`;
CREATE TABLE `plug_donate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_donate`
-- --------------------------------------------------

INSERT INTO `plug_donate` (`id`, `name`, `email`, `amount`, `created`) VALUES ('1', 'Web Master', 'webmaster@domain.com', '125.00', '2012-06-28 10:41:55');
INSERT INTO `plug_donate` (`id`, `name`, `email`, `amount`, `created`) VALUES ('2', 'Web Master', 'webmaster@domain.com', '15.00', '2012-06-28 10:53:56');


-- --------------------------------------------------
# -- Table structure for table `plug_donate_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_donate_config`;
CREATE TABLE `plug_donate_config` (
  `atarget` decimal(13,2) NOT NULL,
  `paypal` varchar(80) NOT NULL,
  `thankyou` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_donate_config`
-- --------------------------------------------------

INSERT INTO `plug_donate_config` (`atarget`, `paypal`, `thankyou`) VALUES ('750.00', 'webmaster@paypal.com', 'Tree-Column-Page');
INSERT INTO `plug_donate_config` (`atarget`, `paypal`, `thankyou`) VALUES ('750.00', 'webmaster@paypal.com', 'Tree-Column-Page');
INSERT INTO `plug_donate_config` (`atarget`, `paypal`, `thankyou`) VALUES ('750.00', 'webmaster@paypal.com', 'Tree-Column-Page');


-- --------------------------------------------------
# -- Table structure for table `plug_elastic`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_elastic`;
CREATE TABLE `plug_elastic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description_en` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `filename` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `position` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='contains slider data';

-- --------------------------------------------------
# Dumping data for table `plug_elastic`
-- --------------------------------------------------

INSERT INTO `plug_elastic` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('1', 'Piko', 'Verbatium', 'FILE_472CBE-2F37A5-2C6705-23F422-FACC3A-70B2CB.jpg', '1');
INSERT INTO `plug_elastic` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('2', 'Simil', 'Homonimo', 'FILE_F83BB2-0550CB-F31AA6-8F6201-F8EA03-D86934.jpg', '2');
INSERT INTO `plug_elastic` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('3', 'Kioma', 'Volitivo', 'FILE_4D7E14-4A7FEA-52F99E-5C4464-843D7F-706B1E.jpg', '3');
INSERT INTO `plug_elastic` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('4', 'Memmortigo', 'Multiplikite', 'FILE_D5D36F-768049-E68040-DFF5A5-398C34-BF91BD.jpg', '4');
INSERT INTO `plug_elastic` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('5', 'Eligi', 'Verbatium', 'FILE_75D54A-9A2D05-04AC78-06ED6D-2F2748-883FD9.jpg', '5');


-- --------------------------------------------------
# -- Table structure for table `plug_elastic_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_elastic_config`;
CREATE TABLE `plug_elastic_config` (
  `animation` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'center',
  `autoplay` tinyint(1) NOT NULL DEFAULT '1',
  `interval` smallint(4) NOT NULL DEFAULT '3000',
  `speed` smallint(3) NOT NULL DEFAULT '800',
  `titlespeed` smallint(3) NOT NULL DEFAULT '800',
  `thumbMaxWidth` smallint(2) NOT NULL DEFAULT '150',
  `height` smallint(2) NOT NULL DEFAULT '350'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='contains slider configuration data';

-- --------------------------------------------------
# Dumping data for table `plug_elastic_config`
-- --------------------------------------------------

INSERT INTO `plug_elastic_config` (`animation`, `autoplay`, `interval`, `speed`, `titlespeed`, `thumbMaxWidth`, `height`) VALUES ('center', '1', '3000', '800', '800', '250', '375');
INSERT INTO `plug_elastic_config` (`animation`, `autoplay`, `interval`, `speed`, `titlespeed`, `thumbMaxWidth`, `height`) VALUES ('center', '1', '3000', '800', '800', '250', '375');
INSERT INTO `plug_elastic_config` (`animation`, `autoplay`, `interval`, `speed`, `titlespeed`, `thumbMaxWidth`, `height`) VALUES ('center', '1', '3000', '800', '800', '250', '375');


-- --------------------------------------------------
# -- Table structure for table `plug_newsslider`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_newsslider`;
CREATE TABLE `plug_newsslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(150) DEFAULT NULL,
  `body_en` text,
  `show_title` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `show_created` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_newsslider`
-- --------------------------------------------------

INSERT INTO `plug_newsslider` (`id`, `title_en`, `body_en`, `show_title`, `created`, `show_created`, `position`, `active`) VALUES ('1', 'Etiam non lacus', 'Morbi sodales accumsan arcu sed venenatis. Vivamus leo diam, dignissim  eu convallis in, posuere quis magna. Curabitur mollis, lectus sit amet  bibendum faucibus, nisi ligula ultricies purus', '1', '2010-10-28 04:14:11', '1', '1', '1');
INSERT INTO `plug_newsslider` (`id`, `title_en`, `body_en`, `show_title`, `created`, `show_created`, `position`, `active`) VALUES ('2', 'Cras ullamcorper', 'Etiam non lacus ac velit lobortis rutrum sed id turpis. Ut dictum, eros  eu blandit pellentesque, nisi nisl dapibus mauris, sed feugiat enim urna  sit amet nibh. Suspendisse sed tortor nisi. Nulla facilisi. In sed  risus in est cursus ornare.', '1', '2010-10-28 04:14:33', '1', '2', '1');
INSERT INTO `plug_newsslider` (`id`, `title_en`, `body_en`, `show_title`, `created`, `show_created`, `position`, `active`) VALUES ('3', 'Vivamus vitae', 'Lusce pulvinar velit sit amet ligula ornare tempus vulputate ipsum  semper. Praesent non lorem odio. Fusce sed dui massa, eu viverra erat.  Proin posuere nulla in lectus malesuada volutpat. Cras tristique blandit  tellus, eu consequat ante', '1', '2010-10-28 04:21:34', '1', '3', '1');
INSERT INTO `plug_newsslider` (`id`, `title_en`, `body_en`, `show_title`, `created`, `show_created`, `position`, `active`) VALUES ('4', 'Another News', 'Vivamus vitae augue sed lacus placerat sollicitudin quis vel arcu. Vestibulum auctor, magna sit amet pulvinar tristique, nunc felis viverra tortor, venenatis convallis leo mauris eu massa. Intege', '1', '2010-10-28 04:43:36', '1', '4', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_poll_options`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_options`;
CREATE TABLE `plug_poll_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `value_en` varchar(250) NOT NULL,
  `position` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_options`
-- --------------------------------------------------

INSERT INTO `plug_poll_options` (`id`, `question_id`, `value_en`, `position`) VALUES ('5', '1', 'Very Hard', '5');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value_en`, `position`) VALUES ('4', '1', 'Hard', '4');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value_en`, `position`) VALUES ('3', '1', 'Easy', '3');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value_en`, `position`) VALUES ('2', '1', 'Very Easy', '2');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value_en`, `position`) VALUES ('1', '1', 'Piece of cake', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_poll_questions`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_questions`;
CREATE TABLE `plug_poll_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_en` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_questions`
-- --------------------------------------------------

INSERT INTO `plug_poll_questions` (`id`, `question_en`, `created`, `status`) VALUES ('1', 'How do you find CMS pro! Installation?', '2010-10-13 07:42:18', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_poll_votes`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_votes`;
CREATE TABLE `plug_poll_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `voted_on` datetime NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_votes`
-- --------------------------------------------------

INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('1', '2', '2010-10-14 14:00:55', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('2', '1', '2010-10-14 14:01:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('3', '1', '2010-10-14 14:02:04', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('4', '1', '2010-10-14 14:02:13', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('5', '3', '2010-10-14 14:02:16', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('6', '4', '2010-10-14 14:02:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('7', '3', '2010-10-14 14:02:24', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('8', '1', '2010-10-14 14:02:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('9', '2', '2010-10-14 14:02:31', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('10', '5', '2010-10-14 14:02:35', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('11', '1', '2010-10-14 14:02:38', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('12', '2', '2010-10-14 14:02:43', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('13', '1', '2010-10-14 14:02:46', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('14', '1', '2010-10-14 14:02:50', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('15', '1', '2010-10-14 14:05:26', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('16', '1', '2010-10-14 14:05:29', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('17', '4', '2010-10-14 14:05:33', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('18', '2', '2010-10-14 14:05:36', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('19', '1', '2010-10-14 14:05:40', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('20', '3', '2010-10-14 14:05:46', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('21', '2', '2010-10-14 14:05:49', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('22', '2', '2010-10-14 14:21:37', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('23', '1', '2010-10-14 14:21:53', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('24', '5', '2010-10-14 14:21:59', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('25', '1', '2010-10-14 14:35:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('26', '1', '2010-10-15 00:42:05', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('27', '3', '2010-10-15 00:49:42', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('28', '2', '2010-10-15 01:22:00', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('29', '2', '2010-10-15 01:24:51', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('30', '1', '2010-10-15 01:37:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('31', '1', '2010-10-15 01:38:48', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('32', '1', '2010-10-15 01:41:30', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('33', '1', '2010-10-15 01:42:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('34', '1', '2010-10-15 04:53:42', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('35', '3', '2010-10-15 05:09:14', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('36', '3', '2010-11-24 21:00:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('37', '3', '2010-11-28 00:56:07', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('38', '3', '2012-12-22 21:57:05', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('39', '1', '2012-12-22 22:46:26', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('40', '5', '2012-12-24 15:20:53', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('41', '1', '2012-12-26 20:20:01', '127.0.0.1');


-- --------------------------------------------------
# -- Table structure for table `plug_rss_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_rss_config`;
CREATE TABLE `plug_rss_config` (
  `url` varchar(200) DEFAULT NULL,
  `title_trim` varchar(3) DEFAULT NULL,
  `show_body` tinyint(1) NOT NULL DEFAULT '0',
  `body_trim` varchar(3) DEFAULT NULL,
  `show_date` tinyint(1) NOT NULL DEFAULT '1',
  `dateformat` varchar(30) DEFAULT NULL,
  `perpage` varchar(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_rss_config`
-- --------------------------------------------------

INSERT INTO `plug_rss_config` (`url`, `title_trim`, `show_body`, `body_trim`, `show_date`, `dateformat`, `perpage`) VALUES ('http://codecanyon.net/feeds/users/gewa13', '0', '0', '100', '0', '%b %d %Y', '5');
INSERT INTO `plug_rss_config` (`url`, `title_trim`, `show_body`, `body_trim`, `show_date`, `dateformat`, `perpage`) VALUES ('http://codecanyon.net/feeds/users/gewa13', '0', '0', '100', '0', '%b %d %Y', '5');
INSERT INTO `plug_rss_config` (`url`, `title_trim`, `show_body`, `body_trim`, `show_date`, `dateformat`, `perpage`) VALUES ('http://codecanyon.net/feeds/users/gewa13', '0', '0', '100', '0', '%b %d %Y', '5');


-- --------------------------------------------------
# -- Table structure for table `plug_slideout`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_slideout`;
CREATE TABLE `plug_slideout` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) DEFAULT NULL,
  `description_en` text,
  `filename` varchar(100) DEFAULT NULL,
  `position` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_slideout`
-- --------------------------------------------------

INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('1', 'We believe in', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.', 'FILE_3691C4-E1C5E4-8BE3FC-B8B5E1-E1FB2B-48A0BC.jpg', '1');
INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('2', 'making the web', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.', 'FILE_5B2FB7-17F69F-028F93-1B0BE9-07AE9C-A7F763.jpg', '2');
INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('3', 'a better place', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.', 'FILE_DB6414-743863-9DDDAD-4B27FF-1093E4-FB162F.jpg', '3');
INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('4', 'through innovation', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.', 'FILE_B1F6F8-CD645E-9DDE38-81222B-C9D77C-51F736.jpg', '4');
INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('5', 'and technology', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.', 'FILE_2718CE-E249BB-C0F3CE-84D187-6F1A8F-0D5F4E.jpg', '5');
INSERT INTO `plug_slideout` (`id`, `title_en`, `description_en`, `filename`, `position`) VALUES ('6', 'with CMS pro!', 'Tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam.\r\n', 'FILE_4E1B30-8EFA5E-E12025-3AA1BA-9F3728-4B42D2.jpg', '6');


-- --------------------------------------------------
# -- Table structure for table `plug_slider`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_slider`;
CREATE TABLE `plug_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(150) NOT NULL DEFAULT '',
  `description_en` text,
  `filename` varchar(150) NOT NULL DEFAULT '',
  `url` varchar(150) NOT NULL DEFAULT '',
  `page_id` int(6) DEFAULT '0',
  `urltype` enum('int','ext') DEFAULT NULL,
  `position` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_slider`
-- --------------------------------------------------

INSERT INTO `plug_slider` (`id`, `title_en`, `description_en`, `filename`, `url`, `page_id`, `urltype`, `position`) VALUES ('1', 'Via o basate nomina proposito', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', 'FILE_482821-DD455E-AEBC7B-39F656-8D1C99-EE88B2.jpg', '#', '0', 'ext', '1');
INSERT INTO `plug_slider` (`id`, `title_en`, `description_en`, `filename`, `url`, `page_id`, `urltype`, `position`) VALUES ('2', 'Infra latino appellate le sia', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', 'FILE_B15EBC-1398BD-530537-234CE4-1EE3A4-7B83E0.jpg', '#', '0', 'ext', '2');
INSERT INTO `plug_slider` (`id`, `title_en`, `description_en`, `filename`, `url`, `page_id`, `urltype`, `position`) VALUES ('3', 'Il via unic populos', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', 'FILE_D8D942-5C8092-D5757A-B57AEB-EDED1D-D5E165.jpg', '#', '0', 'ext', '3');
INSERT INTO `plug_slider` (`id`, `title_en`, `description_en`, `filename`, `url`, `page_id`, `urltype`, `position`) VALUES ('4', 'In anque svedese abstracte del', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', 'FILE_DE99C5-257B9B-E1580E-8D3070-70ABC3-732634.jpg', '#', '0', 'ext', '4');
INSERT INTO `plug_slider` (`id`, `title_en`, `description_en`, `filename`, `url`, `page_id`, `urltype`, `position`) VALUES ('5', 'Tu auxiliar intention sia', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', 'FILE_117D04-707F90-E1BAC7-876BEC-BF121F-9BB97E.jpg', '#', '0', 'ext', '5');


-- --------------------------------------------------
# -- Table structure for table `plug_slider_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_slider_config`;
CREATE TABLE `plug_slider_config` (
  `animation` varchar(30) NOT NULL,
  `anispeed` varchar(6) NOT NULL DEFAULT '0',
  `anitime` varchar(10) NOT NULL DEFAULT '0',
  `shownav` tinyint(1) NOT NULL DEFAULT '0',
  `shownavhide` tinyint(1) NOT NULL DEFAULT '0',
  `controllnav` tinyint(1) NOT NULL DEFAULT '0',
  `hoverpause` tinyint(1) NOT NULL DEFAULT '0',
  `showcaption` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_slider_config`
-- --------------------------------------------------

INSERT INTO `plug_slider_config` (`animation`, `anispeed`, `anitime`, `shownav`, `shownavhide`, `controllnav`, `hoverpause`, `showcaption`) VALUES ('fade', '7000', '600', '1', '1', '1', '1', '1');
INSERT INTO `plug_slider_config` (`animation`, `anispeed`, `anitime`, `shownav`, `shownavhide`, `controllnav`, `hoverpause`, `showcaption`) VALUES ('fade', '7000', '600', '1', '1', '1', '1', '1');
INSERT INTO `plug_slider_config` (`animation`, `anispeed`, `anitime`, `shownav`, `shownavhide`, `controllnav`, `hoverpause`, `showcaption`) VALUES ('fade', '7000', '600', '1', '1', '1', '1', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_tabs`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_tabs`;
CREATE TABLE `plug_tabs` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(50) NOT NULL DEFAULT '',
  `body_en` text,
  `position` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_tabs`
-- --------------------------------------------------

INSERT INTO `plug_tabs` (`id`, `title_en`, `body_en`, `position`, `active`) VALUES ('1', 'Website Design', '&lt;img width=&quot;305&quot; height=&quot;220&quot; style=&quot;margin-left: 15px; float: right;&quot; alt=&quot;webdesign.png&quot; src=&quot;uploads/images/pages/webdesign.png&quot; /&gt;\n&lt;h2&gt;Website Design&lt;/h2&gt;\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis facilisis dapibus tincidunt. Aliquam non mauris ac urna pretium malesuada. Mauris viverra fringilla lectus, nec congue neque adipiscing ultrices.&lt;/p&gt;\n&lt;p&gt;Nulla vel magna in leo mattis congue in eget quam. Proin dignissim nunc vitae nunc euismod sollicitudin. Nullam pretium placerat eleifend. Aliquam erat volutpat. Nunc et massa nisl, lacinia pharetra eros. In sit amet augue a ante tincidunt viverra.&lt;/p&gt;&lt;br class=&quot;clear&quot; /&gt;\n', '1', '1');
INSERT INTO `plug_tabs` (`id`, `title_en`, `body_en`, `position`, `active`) VALUES ('2', 'Content Management', '&lt;img width=&quot;305&quot; height=&quot;220&quot; style=&quot;margin-left: 15px; float: right;&quot; alt=&quot;cms.png&quot; src=&quot;uploads/images/pages/cms.png&quot; /&gt;\n&lt;h2&gt;Content Management&lt;/h2&gt;\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis facilisis dapibus tincidunt. Aliquam non mauris ac urna pretium malesuada. Mauris viverra fringilla lectus, nec congue neque adipiscing ultrices.&lt;/p&gt;\n&lt;p&gt;Nulla vel magna in leo mattis congue in eget quam. Proin dignissim nunc vitae nunc euismod sollicitudin. Nullam pretium placerat eleifend. Aliquam erat volutpat. Nunc et massa nisl, lacinia pharetra eros. In sit amet augue a ante tincidunt viverra.&lt;/p&gt;&lt;br class=&quot;clear&quot; /&gt;\n', '2', '1');
INSERT INTO `plug_tabs` (`id`, `title_en`, `body_en`, `position`, `active`) VALUES ('3', 'E-Commerce', '&lt;img width=&quot;305&quot; height=&quot;220&quot; src=&quot;uploads/images/pages/ecommerce.png&quot; alt=&quot;ecommerce.png&quot; style=&quot;margin-left: 15px; float: right;&quot; /&gt;\n&lt;h2&gt;E-Commerce&lt;/h2&gt;\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis  facilisis dapibus tincidunt. Aliquam non mauris ac urna pretium  malesuada. Mauris viverra fringilla lectus, nec congue neque adipiscing  ultrices.&lt;/p&gt;\n&lt;p&gt;Nulla vel magna in leo mattis congue in eget quam. Proin  dignissim nunc vitae nunc euismod sollicitudin. Nullam pretium placerat  eleifend. Aliquam erat volutpat. Nunc et massa nisl, lacinia pharetra  eros. In sit amet augue a ante tincidunt viverra.&lt;/p&gt;&lt;br class=&quot;clear&quot; /&gt;\n', '4', '1');
INSERT INTO `plug_tabs` (`id`, `title_en`, `body_en`, `position`, `active`) VALUES ('4', 'Search Engines', '&lt;img width=&quot;305&quot; height=&quot;220&quot; src=&quot;uploads/images/pages/seo.png&quot; alt=&quot;seo.png&quot; style=&quot;margin-left: 15px; float: right;&quot; /&gt;\n&lt;h2&gt;Search Engines&lt;/h2&gt;\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis  facilisis dapibus tincidunt. Aliquam non mauris ac urna pretium  malesuada. Mauris viverra fringilla lectus, nec congue neque adipiscing  ultrices.&lt;/p&gt;\n&lt;p&gt;Nulla vel magna in leo mattis congue in eget quam. Proin  dignissim nunc vitae nunc euismod sollicitudin. Nullam pretium placerat  eleifend. Aliquam erat volutpat. Nunc et massa nisl, lacinia pharetra  eros. In sit amet augue a ante tincidunt viverra.&lt;/p&gt;&lt;br /&gt;\n\n&lt;p&gt;&lt;a href=&quot;#&quot; class=&quot;button shadow&quot;&gt;Read More&lt;/a&gt;&lt;/p&gt;&lt;br class=&quot;clear&quot; /&gt;\n', '3', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_twitter_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_twitter_config`;
CREATE TABLE `plug_twitter_config` (
  `username` varchar(150) DEFAULT NULL,
  `counter` int(1) NOT NULL DEFAULT '5',
  `speed` varchar(6) NOT NULL,
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `timeout` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_twitter_config`
-- --------------------------------------------------

INSERT INTO `plug_twitter_config` (`username`, `counter`, `speed`, `show_image`, `timeout`) VALUES ('cms_pro', '5', '300', '1', '10000');
INSERT INTO `plug_twitter_config` (`username`, `counter`, `speed`, `show_image`, `timeout`) VALUES ('cms_pro', '5', '300', '1', '10000');
INSERT INTO `plug_twitter_config` (`username`, `counter`, `speed`, `show_image`, `timeout`) VALUES ('cms_pro', '5', '300', '1', '10000');


-- --------------------------------------------------
# -- Table structure for table `plug_upevent_config`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_upevent_config`;
CREATE TABLE `plug_upevent_config` (
  `event_id` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_upevent_config`
-- --------------------------------------------------

INSERT INTO `plug_upevent_config` (`event_id`) VALUES ('2');
INSERT INTO `plug_upevent_config` (`event_id`) VALUES ('2');
INSERT INTO `plug_upevent_config` (`event_id`) VALUES ('2');


-- --------------------------------------------------
# -- Table structure for table `plug_videoslider`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_videoslider`;
CREATE TABLE `plug_videoslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(150) NOT NULL DEFAULT '',
  `description_en` varchar(200) DEFAULT NULL,
  `vidurl` varchar(150) DEFAULT NULL,
  `position` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_videoslider`
-- --------------------------------------------------

INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('1', 'Megamind', 'Megamind hits theaters on November 5th, 2010', 'CzzPbEA6vVU', '1');
INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('2', 'Ice Age 4', 'Ice Age: Continental Drift, also known as Ice Age 4', 'hzixp8s4pyg', '2');
INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('3', 'Toy Story 3', 'Trailer for upcoming Disney Pixar movie Toy Story 3', 'roADdYWAv4A', '3');
INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('4', 'Big Buck Bunny animation', 'An animated short film about a Big Buck Bunny in HD', 'XSGBVzeBUbk', '4');
INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('5', 'Married Life - Carl &amp; Ellie', 'This is the best part of the movie UP when Carl met Ellie', 'GroDErHIM_0', '5');
INSERT INTO `plug_videoslider` (`id`, `title_en`, `description_en`, `vidurl`, `position`) VALUES ('6', 'Pixar For the birds', 'A high definition 1080p animation about a big bird.', 'zqmrEa5DLig', '6');


-- --------------------------------------------------
# -- Table structure for table `plugins`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(120) NOT NULL,
  `body_en` text,
  `jscode` text,
  `show_title` tinyint(1) NOT NULL DEFAULT '0',
  `alt_class` varchar(100) NOT NULL DEFAULT '',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `info_en` text,
  `plugalias` varchar(50) NOT NULL,
  `hasconfig` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ver` varchar(4) NOT NULL DEFAULT '1.00',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plugins`
-- --------------------------------------------------

INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('2', 'News Slider', '&lt;br /&gt;\n', '', '1', 'light', '1', 'Displays latest news items', 'newsslider', '1', '2010-07-20 14:10:15', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('6', 'jQuery Slider', '&lt;br /&gt;\n', '', '0', '', '1', 'jQuery Slider is one great way to display portfolio pieces, eCommerce product images, or even as an image gallery.', 'jqueryslider', '1', '2010-07-20 14:10:15', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('10', 'Latest Twitts', '&lt;br /&gt;\n', '', '1', 'green', '1', 'Shows your latest twitts', 'twitts', '1', '2010-07-22 11:42:08', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('13', 'Ajax Poll', '&lt;br /&gt;\n', '', '1', 'noclass', '1', 'jQuery Ajax poll module.', 'poll', '1', '2010-10-25 14:12:20', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('7', 'jQuery Tabs', '&lt;br /&gt;\n', '', '0', '', '1', 'jQuery Dynamic Tabs', 'jtabs', '1', '2010-12-20 12:12:20', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('12', 'Event Manager', '', '', '1', '', '1', 'Easily publish and manage your company events.', 'events', '0', '2010-12-28 10:12:14', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('14', 'Vertical Navigation', '', '', '1', '', '1', 'Vertical flyout menu module', 'vmenu', '0', '2010-12-27 08:12:14', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('16', 'Rss Parser', '&lt;br /&gt;\n', '', '1', '', '1', 'Show rss feeds (RSS 0.9 / RSS 1.0). Also RSS 2.0, and Atom a with few exceptions.', 'rss', '1', '2011-04-16 08:11:55', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('18', 'User Login', '', '', '1', 'red', '1', 'Shows login form.', 'login', '0', '2011-05-10 02:12:14', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('19', 'Slide Out', '&lt;br /&gt;\n', '', '0', 'hide-phone nowrap', '1', 'Slide out smooth slider', 'slideout', '1', '2011-12-11 07:28:19', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('20', 'Smooth Content Slider', '&lt;br /&gt;\n', '', '0', 'hide-phone nowrap', '1', 'Any type of content slider', 'contentslider', '1', '2011-12-12 14:18:58', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('21', 'Youtube Video Slider', '', '', '0', '', '1', '', 'videoslider', '1', '2011-12-15 18:14:51', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('22', 'Upcoming Event', '&lt;br /&gt;\n', '', '1', 'red', '1', '', 'upevent', '1', '2012-06-27 16:47:10', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('23', 'You&#039;ve Helped Raise…', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit Nam pulvinar. ', '', '1', 'light', '1', '', 'donate', '1', '2012-06-28 00:21:32', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('24', 'Elastic Slider', '&lt;br /&gt;\n', '', '0', 'nowrap', '1', 'Elastic image slideshow', 'elastic', '1', '2013-03-12 00:21:32', '1.00', '0');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('1', 'Testimonials', '&lt;p class=&quot;testimonial&quot;&gt;&lt;em&gt;Etiam non lacus ac velit lobortis rutrum sed id turpis. Ut dictum, eros eu blandit pellentesque, nisi nisl dapibus mauris, sed feugiat enim urna sit amet nibh. Suspendisse sed tortor nisi.&lt;/em&gt;&lt;/p&gt;\r\n&lt;em&gt;John Smith&lt;/em&gt;, &lt;strong&gt;www.somesite.com&lt;/strong&gt;', '', '1', 'dark green', '0', '', '', '0', '2010-07-20 14:10:15', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('8', 'More Pages', '&lt;ul class=&quot;lists&quot;&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Page Types&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Templates&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;About Us&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Services &lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Projects&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Blog&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Contact Us&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;', '', '1', '', '0', '', '', '0', '2010-07-22 11:38:51', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('3', 'An unordered list', 'This plugin contains a dummy list of items\n&lt;ul&gt;    \n\t&lt;li&gt;List item 1&lt;/li&gt;    \n\t&lt;li&gt;List item 2&lt;/li&gt;    \n\t&lt;li&gt;List item 3&lt;/li&gt;    \n\t&lt;li&gt;List item 4&lt;/li&gt;\n&lt;/ul&gt;', '', '1', 'dark', '0', '', '', '0', '2010-07-20 14:10:15', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('4', 'Info Point', '&lt;ul id=&quot;infopoint-list&quot; class=&quot;clearfix&quot;&gt;\r\n\t&lt;li class=&quot;row box&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/iphone.png&quot; class=&quot;img-left&quot; /&gt; Cum sociis natoque penatibus et magnis dis parturient montes&lt;/li&gt;\r\n\t&lt;li class=&quot;row box whitebox top5&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/green.png&quot; class=&quot;img-left&quot; /&gt; Curabitur mollis, lectus sit amet bibendum faucibus ligula&lt;/li&gt;\r\n\t&lt;li class=&quot;row box bluebox top5&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/installer_box.png&quot; class=&quot;img-left&quot; /&gt; Morbi sodales accumsan arcu sed venenatis. Vivamus leo&lt;/li&gt;\r\n\t&lt;li class=&quot;row box greenbox top5&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/headphone.png&quot; class=&quot;img-left&quot; /&gt; Cras ullamcorper suscipit justo, at mattis odio auctor quis alteno&lt;/li&gt;\r\n\t&lt;li class=&quot;row box redbox top5&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/coins.png&quot; class=&quot;img-left&quot; /&gt; Vestibulum auctor, magna sit amet pulvinar tristique, nunc felis&lt;/li&gt;\r\n\t&lt;li class=&quot;row box top5&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;uploads/icons/color_wheel.png&quot; class=&quot;img-left&quot; /&gt; Integer aliquet libero sed lorem consequat ut tempus faucibus&lt;/li&gt;\r\n&lt;/ul&gt;', '', '1', '', '0', '', '', '0', '2010-07-20 14:10:15', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('5', 'Our Contact Numbers', '&lt;strong&gt;Office&lt;/strong&gt; +1-416-123456789&lt;br /&gt;\r\n&lt;strong&gt;helpdesk&lt;/strong&gt; +1-416-123456789&lt;br /&gt;', '', '1', '', '0', '', '', '0', '2010-07-20 14:10:15', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('11', 'Contact Information', '&lt;ul&gt;\r\n    &lt;li&gt;&lt;b&gt;E-mail:&lt;/b&gt; &lt;a href=&quot;mailto:info@mywebsite.com&quot;&gt;info@mywebsite.com&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;b&gt;Telephone:&lt;/b&gt; 0080 000 000&lt;/li&gt;\r\n    &lt;li&gt;&lt;b&gt;Fax:&lt;/b&gt; 0080 000 000&lt;/li&gt;\r\n    &lt;li&gt;&lt;b&gt;Address:&lt;/b&gt;     1600 Amphitheatre Parkway                 Toronto, ON M2K 1Z7&lt;/li&gt;\r\n&lt;/ul&gt;', '', '1', 'red', '0', '', '', '0', '2010-07-22 11:44:15', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('9', 'Even More Pages', '&lt;ul class=&quot;lists&quot;&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Updates&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;News&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Press Releases&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;New Offers&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Our Staff &lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Policy&lt;/a&gt;&lt;/li&gt;\r\n    &lt;li&gt;&lt;a href=&quot;#&quot;&gt;Events&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;', '', '1', 'light', '0', '', '', '0', '2010-07-22 11:39:22', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `info_en`, `plugalias`, `hasconfig`, `created`, `ver`, `active`) VALUES ('29', 'Advert Space', '\n&lt;div style=&quot;text-align:center;margin-bottom:10px&quot;&gt;Image banners and adSense suported&lt;/div&gt;', '', '1', '', '1', '', 'adblock/Advert-Wojoscripts', '0', '2013-01-04 18:30:51', '1.00', '1');


-- --------------------------------------------------
# -- Table structure for table `posts`
-- --------------------------------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `page_slug` varchar(50) NOT NULL,
  `title_en` varchar(200) NOT NULL,
  `show_title` tinyint(1) NOT NULL DEFAULT '1',
  `body_en` text NOT NULL,
  `jscode` text,
  `position` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `posts`
-- --------------------------------------------------

INSERT INTO `posts` (`id`, `page_id`, `page_slug`, `title_en`, `show_title`, `body_en`, `jscode`, `position`, `active`) VALUES ('1', '1', 'Home', 'Welcome to CMS Pro!', '1', 'Welcome to CMS Pro!. The Lightweight fully responsive CMS.&lt;br /&gt;\n&lt;br /&gt;\nCongratulation !! your installation of Cms Pro was successful.&lt;br /&gt;\n&lt;br /&gt;\nThis is the home page of your default installation of CMS Pro!.&lt;br /&gt;\nYou can edit or add content to your Web site from the administration panel of CMS Pro! by clicking the link below.&lt;br /&gt;\n&lt;a href=&quot;admin/index.php&quot; style=&quot;font-weight: bold;&quot;&gt;Administration panel&lt;/a&gt;\n&lt;div class=&quot;hr2&quot;&gt;&amp;nbsp;&lt;/div&gt;\n&lt;div class=&quot;row grid_24&quot;&gt;\n\t&lt;div class=&quot;col grid_8 top10&quot;&gt;\n\t\t&lt;h3&gt;Clean &amp;amp; Modern Design&lt;/h3&gt;\n\t\t&lt;figure class=&quot;flright thumb&quot;&gt;&lt;img title=&quot;Clean &amp;amp; Modern Design?&quot; style=&quot;&quot; src=&quot;uploads/icons/Color-Management.png&quot; alt=&quot;Clean &amp;amp; Modern Design&quot; /&gt;&lt;/figure&gt;\n\t\t&lt;p&gt;Nulla sollicitudin nulla mauris. Donec congue facilisis lorem, ornare tincidunt orci ullamcorper nec.&lt;/p&gt;\n\t\t&lt;p&gt;Nam pellentesque auctor turpis nec &lt;span style=&quot;font-weight: bold;&quot;&gt;dapibus&lt;/span&gt;. Vivamus interdum dignissim tincidunt. Vestibulum dapibus laoreet arcu, et pharetra augue ultricies quis.&lt;/p&gt;\n\t\t&lt;p&gt;Sed luctus condimentum mollis. Etiam lacus turpis, hendrerit vitae &lt;span style=&quot;font-style: italic;&quot;&gt;feugiat&lt;/span&gt; sit amet, cursus ac quam. Curabitur metus mi.&lt;/p&gt;&lt;/div&gt;\n\t&lt;div class=&quot;col grid_8 top10&quot;&gt;\n\t\t&lt;h3&gt;Easy Customization&lt;/h3&gt;\n\t\t&lt;figure class=&quot;thumb flright&quot;&gt;&lt;img style=&quot;&quot; title=&quot;What we do?&quot; src=&quot;uploads/icons/Gadgets.png&quot; alt=&quot;What we do?&quot; /&gt;&lt;/figure&gt;\n\t\t&lt;p&gt;Nulla sollicitudin nulla mauris. Donec congue facilisis lorem, ornare tincidunt orci ullamcorper nec.&lt;/p&gt;\n\t\t&lt;p&gt;Nam pellentesque auctor turpis nec &lt;span style=&quot;font-weight: bold;&quot;&gt;dapibus&lt;/span&gt;. Vivamus interdum dignissim tincidunt. Vestibulum dapibus laoreet arcu, et pharetra augue ultricies quis.&lt;/p&gt;\n\t\t&lt;p&gt;Sed luctus condimentum mollis. Etiam lacus turpis, hendrerit vitae &lt;span style=&quot;font-style: italic;&quot;&gt;feugiat&lt;/span&gt; sit amet, cursus ac quam. Curabitur metus mi.&lt;/p&gt;&lt;/div&gt;\n\t&lt;div class=&quot;col grid_8 top10&quot;&gt;\n\t\t&lt;h3&gt;Responsive Design&lt;/h3&gt;\n\t\t&lt;figure class=&quot;thumb flrighth&quot;&gt;&lt;img style=&quot;&quot; title=&quot;What Is This?&quot; src=&quot;uploads/icons/Windows-Easy-Transfer.png&quot; alt=&quot;What Is This?&quot; /&gt;&lt;/figure&gt;\n\t\t&lt;p&gt;Nulla sollicitudin nulla mauris. Donec congue facilisis lorem, ornare tincidunt orci ullamcorper nec.&lt;/p&gt;\n\t\t&lt;p&gt;Nam pellentesque auctor turpis nec &lt;span style=&quot;font-weight: bold;&quot;&gt;dapibus&lt;/span&gt;. Vivamus interdum dignissim tincidunt. Vestibulum dapibus laoreet arcu, et pharetra augue ultricies quis.&lt;/p&gt;\n\t\t&lt;p&gt;Sed luctus condimentum mollis. Etiam lacus turpis, hendrerit vitae &lt;span style=&quot;font-style: italic;&quot;&gt;feugiat&lt;/span&gt; sit amet, cursus ac quam. Curabitur metus mi.&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;\n&lt;div class=&quot;hr2&quot;&gt;&amp;nbsp;&lt;/div&gt;\n&lt;div class=&quot;row&quot;&gt;\n\t&lt;div class=&quot;col grid_16&quot;&gt;\n\t\t&lt;h3 class=&quot;colgreen&quot;&gt;&lt;span&gt;Our Services&lt;/span&gt;&lt;/h3&gt;\n\t\t&lt;div class=&quot;carousel&quot;&gt;\n\t\t\t&lt;ul class=&quot;slides&quot;&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;Websites&lt;/h4&gt;&lt;img style=&quot;&quot; class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/Bookmarks.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris.&lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit. &lt;/li&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;PSD 2 html&lt;/h4&gt;&lt;img class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/Tasks.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris. &lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/li&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;Corporate Identity&lt;/h4&gt;&lt;img class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/Internet-Options.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris. &lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/li&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;Brand Strategy&lt;/h4&gt;&lt;img class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/Brightness.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris. &lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/li&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;Social&lt;/h4&gt;&lt;img class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/Troubleshooting.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris. &lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/li&gt;\n\t\t\t\t&lt;li&gt;\n\t\t\t\t\t&lt;h4&gt;Web design&lt;/h4&gt;&lt;img class=&quot;thumb&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/icons/System.png&quot; /&gt; Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Aenean nisl orci, condimentum ultrices cons equat eu, vehicula ac mauris. &lt;br /&gt;\n\t\t\t\t\t&lt;br /&gt;\n\t\t\t\t\tAenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nisl orci, condimentum ultrices consequat eu, vehicula ac mauris. Ut adipiscing, leo nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit.&lt;/li&gt;\n\t\t\t&lt;/ul&gt;&lt;/div&gt;&lt;/div&gt;\n\t&lt;div class=&quot;col grid_8&quot;&gt;\n\t\t&lt;h3 class=&quot;colblue&quot;&gt;&lt;span&gt;Our Clients&lt;/span&gt;&lt;/h3&gt;\n\t\t&lt;div class=&quot;carousel whitebox&quot;&gt;\n\t\t\t&lt;ul class=&quot;slides&quot;&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client1.png&quot; /&gt;&lt;/li&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client2.png&quot; /&gt;&lt;/li&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client3.png&quot; /&gt;&lt;/li&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client4.png&quot; /&gt;&lt;/li&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client5.png&quot; /&gt;&lt;/li&gt;\n\t\t\t\t&lt;li&gt;&lt;img style=&quot;&quot; alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/client6.png&quot; /&gt;&lt;/li&gt;\n\t\t\t&lt;/ul&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;', '', '1', '1');
INSERT INTO `posts` (`id`, `page_id`, `page_slug`, `title_en`, `show_title`, `body_en`, `jscode`, `position`, `active`) VALUES ('10', '9', 'Future', 'Registered Users Only', '1', '&lt;span style=&quot;font-weight: bold; font-style: italic;&quot;&gt;This page is for Registered users only&lt;/span&gt;&lt;br /&gt;\n', '', '0', '1');
INSERT INTO `posts` (`id`, `page_id`, `page_slug`, `title_en`, `show_title`, `body_en`, `jscode`, `position`, `active`) VALUES ('11', '10', 'Products', 'Membership Access', '1', '&lt;span style=&quot;font-weight: bold; font-style: italic;&quot;&gt;This page can be accessed with valid membership only!&lt;/span&gt;&lt;br /&gt;\n', '', '0', '1');
INSERT INTO `posts` (`id`, `page_id`, `page_slug`, `title_en`, `show_title`, `body_en`, `jscode`, `position`, `active`) VALUES ('13', '13', 'Contact', 'Content Slider Demo', '1', '\n&lt;div style=&quot;font-style: italic;&quot;&gt;Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur &lt;span class=&quot;highlight&quot;&gt;This plugin is included in CMS pro! v3.0&lt;/span&gt; aut perferendis doloribus asperiores repellat accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque.&lt;br /&gt;\n\t&lt;br /&gt;\n\t&lt;/div&gt;&lt;img alt=&quot;&quot; title=&quot;&quot; src=&quot;uploads/images/pages/demo_3.jpg&quot; class=&quot;img-right&quot; /&gt;In erat. Pellentesque erat. Mauris vehicula vestibulum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pulvinar est. Integer urna. Pellentesque pulvinar dui a magna. Nulla facilisi. Proin imperdiet. Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. Vestibulum dapibus. Nulla facilisi. Nulla iaculis, leo sit amet mollis luctus, sapien eros consectetur dolor, eu faucibus elit nibh eu nibh. Maecenas lacus pede, lobortis non, rhoncus id, tristique a, mi. Cras auctor libero vitae sem vestibulum euismod. Nunc fermentum. \n&lt;div&gt;\n\t&lt;p&gt; Aliquam ornare, metus vitae gravida dignissim, nisi nisl ultricies felis, ac tristique enim pede eget elit. Integer non erat nec turpis sollicitudin malesuada. Vestibulum dapibus&lt;/p&gt;&lt;/div&gt;\n&lt;p&gt;Integer fermentum elit in tellus. Integer ligula ipsum, gravida aliquet, fringilla non, interdum eget, ipsum. Praesent id dolor non erat viverra volutpat. Fusce tellus libero, luctus adipiscing, tincidunt vel, egestas vitae, eros. Vestibulum mollis, est id rhoncus volutpat, dolor velit tincidunt neque, vitae pellentesque ante sem eu nisl. eget convallis mauris ante quis magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean et libero. Nam aliquam. Quisque vitae tortor id neque dignissim laoreet. Duis eu ante. Integer at sapien. Praesent sed nisl tempor est pulvinar tristique. Maecenas non lorem quis mi laoreet adipiscing. Sed ac arcu. Sed tincidunt libero eu dolor. Cras pharetra posuere eros. Donec ac eros id diam tempor faucibus. Fusce feugiat consequat nulla. Vestibulum tincidunt vulputate ipsum. &lt;/p&gt;', '', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `settings`
-- --------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `site_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `site_url` varchar(150) NOT NULL,
  `site_email` varchar(50) NOT NULL,
  `theme` varchar(32) NOT NULL,
  `theme_var` varchar(32) DEFAULT NULL,
  `seo` tinyint(1) NOT NULL DEFAULT '0',
  `perpage` tinyint(4) NOT NULL DEFAULT '10',
  `backup` varchar(64) NOT NULL,
  `thumb_w` varchar(5) NOT NULL,
  `thumb_h` varchar(5) NOT NULL,
  `img_w` varchar(5) NOT NULL,
  `img_h` varchar(5) NOT NULL,
  `avatar_w` varchar(3) DEFAULT '80',
  `avatar_h` varchar(3) DEFAULT '80',
  `short_date` varchar(50) NOT NULL,
  `long_date` varchar(50) NOT NULL,
  `dtz` varchar(120) DEFAULT NULL,
  `weekstart` tinyint(1) NOT NULL DEFAULT '1',
  `lang` varchar(2) NOT NULL DEFAULT 'en',
  `show_lang` tinyint(1) NOT NULL DEFAULT '0',
  `langdir` varchar(3) NOT NULL DEFAULT 'ltr',
  `eucookie` tinyint(1) NOT NULL DEFAULT '0',
  `offline` tinyint(1) NOT NULL DEFAULT '0',
  `offline_msg` text,
  `offline_data` varchar(20) DEFAULT '0000:00:00 00:00:00',
  `logo` varchar(100) DEFAULT NULL,
  `showlogin` tinyint(1) NOT NULL DEFAULT '1',
  `showsearch` tinyint(1) NOT NULL DEFAULT '1',
  `bgimg` varchar(60) DEFAULT NULL,
  `repbg` tinyint(1) DEFAULT '0',
  `bgalign` enum('left','right','center') DEFAULT 'left',
  `bgfixed` tinyint(1) DEFAULT '0',
  `bgcolor` varchar(7) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `cur_symbol` varchar(2) DEFAULT NULL,
  `reg_verify` tinyint(1) NOT NULL DEFAULT '1',
  `auto_verify` tinyint(1) NOT NULL DEFAULT '1',
  `reg_allowed` tinyint(1) NOT NULL DEFAULT '1',
  `notify_admin` tinyint(1) NOT NULL DEFAULT '0',
  `user_limit` varchar(6) DEFAULT NULL,
  `flood` varchar(6) DEFAULT NULL,
  `attempt` varchar(2) DEFAULT NULL,
  `logging` tinyint(1) NOT NULL DEFAULT '0',
  `enablefb` tinyint(1) NOT NULL DEFAULT '0',
  `fbapi` varchar(50) DEFAULT NULL,
  `fbsecret` varchar(120) DEFAULT NULL,
  `metakeys` text,
  `metadesc` text,
  `analytics` text,
  `mailer` enum('PHP','SMTP','SMAIL') DEFAULT NULL,
  `sendmail` varchar(60) DEFAULT NULL,
  `smtp_host` varchar(150) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(3) DEFAULT NULL,
  `is_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `version` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `settings`
-- --------------------------------------------------

INSERT INTO `settings` (`site_name`, `company`, `site_url`, `site_email`, `theme`, `theme_var`, `seo`, `perpage`, `backup`, `thumb_w`, `thumb_h`, `img_w`, `img_h`, `avatar_w`, `avatar_h`, `short_date`, `long_date`, `dtz`, `weekstart`, `lang`, `show_lang`, `langdir`, `eucookie`, `offline`, `offline_msg`, `offline_data`, `logo`, `showlogin`, `showsearch`, `bgimg`, `repbg`, `bgalign`, `bgfixed`, `bgcolor`, `currency`, `cur_symbol`, `reg_verify`, `auto_verify`, `reg_allowed`, `notify_admin`, `user_limit`, `flood`, `attempt`, `logging`, `enablefb`, `fbapi`, `fbsecret`, `metakeys`, `metadesc`, `analytics`, `mailer`, `sendmail`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `is_ssl`, `version`) VALUES ('Neo Al Systems  P/L', 'Neo Al Systems P/L', 'http://neoaisystems.com', 'site@mail.com', 'ergo', '', '1', '10', '09-Jan-2013_16-08-10.sql', '150', '150', '800', '800', '80', '80', '%b %d %Y', '%d %B %Y %H:%M', 'America/Toronto', '1', 'en', '0', 'ltr', '0', '0', 'We are currently working on improving our site. Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.\r\n\r\nPraesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.', '2013:01:08 23:00:00', 'bgimg.png', '1', '0', '', '0', 'left', '0', 'false', 'CAD', '$', '1', '1', '0', '1', '0', '1800', '3', '0', '0', '340635415947196', '06eaa18fa7e6ce708d3f61422e46cac3', 'metakeys, separated,by coma', 'Your website description goes here', '', 'PHP', '/usr/sbin/sendmail -t -i', 'mail.hostname.com', 'yourusername', 'yourpass', '25', '0', '3.61');
INSERT INTO `settings` (`site_name`, `company`, `site_url`, `site_email`, `theme`, `theme_var`, `seo`, `perpage`, `backup`, `thumb_w`, `thumb_h`, `img_w`, `img_h`, `avatar_w`, `avatar_h`, `short_date`, `long_date`, `dtz`, `weekstart`, `lang`, `show_lang`, `langdir`, `eucookie`, `offline`, `offline_msg`, `offline_data`, `logo`, `showlogin`, `showsearch`, `bgimg`, `repbg`, `bgalign`, `bgfixed`, `bgcolor`, `currency`, `cur_symbol`, `reg_verify`, `auto_verify`, `reg_allowed`, `notify_admin`, `user_limit`, `flood`, `attempt`, `logging`, `enablefb`, `fbapi`, `fbsecret`, `metakeys`, `metadesc`, `analytics`, `mailer`, `sendmail`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `is_ssl`, `version`) VALUES ('Neo Al Systems  P/L', 'Neo Al Systems P/L', 'http://neoaisystems.com', 'site@mail.com', 'ergo', '', '1', '10', '09-Jan-2013_16-08-10.sql', '150', '150', '800', '800', '80', '80', '%b %d %Y', '%d %B %Y %H:%M', 'America/Toronto', '1', 'en', '0', 'ltr', '0', '0', 'We are currently working on improving our site. Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.\r\n\r\nPraesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.', '2013:01:08 23:00:00', 'bgimg.png', '1', '0', '', '0', 'left', '0', 'false', 'CAD', '$', '1', '1', '0', '1', '0', '1800', '3', '0', '0', '340635415947196', '06eaa18fa7e6ce708d3f61422e46cac3', 'metakeys, separated,by coma', 'Your website description goes here', '', 'PHP', '/usr/sbin/sendmail -t -i', 'mail.hostname.com', 'yourusername', 'yourpass', '25', '0', '3.61');
INSERT INTO `settings` (`site_name`, `company`, `site_url`, `site_email`, `theme`, `theme_var`, `seo`, `perpage`, `backup`, `thumb_w`, `thumb_h`, `img_w`, `img_h`, `avatar_w`, `avatar_h`, `short_date`, `long_date`, `dtz`, `weekstart`, `lang`, `show_lang`, `langdir`, `eucookie`, `offline`, `offline_msg`, `offline_data`, `logo`, `showlogin`, `showsearch`, `bgimg`, `repbg`, `bgalign`, `bgfixed`, `bgcolor`, `currency`, `cur_symbol`, `reg_verify`, `auto_verify`, `reg_allowed`, `notify_admin`, `user_limit`, `flood`, `attempt`, `logging`, `enablefb`, `fbapi`, `fbsecret`, `metakeys`, `metadesc`, `analytics`, `mailer`, `sendmail`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `is_ssl`, `version`) VALUES ('Neo Al Systems  P/L', 'Neo Al Systems P/L', 'http://neoaisystems.com', 'site@mail.com', 'ergo', '', '1', '10', '09-Jan-2013_16-08-10.sql', '150', '150', '800', '800', '80', '80', '%b %d %Y', '%d %B %Y %H:%M', 'America/Toronto', '1', 'en', '0', 'ltr', '0', '0', 'We are currently working on improving our site. Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.\r\n\r\nPraesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.', '2013:01:08 23:00:00', 'bgimg.png', '1', '0', '', '0', 'left', '0', 'false', 'CAD', '$', '1', '1', '0', '1', '0', '1800', '3', '0', '0', '340635415947196', '06eaa18fa7e6ce708d3f61422e46cac3', 'metakeys, separated,by coma', 'Your website description goes here', '', 'PHP', '/usr/sbin/sendmail -t -i', 'mail.hostname.com', 'yourusername', 'yourpass', '25', '0', '3.61');


-- --------------------------------------------------
# -- Table structure for table `stats`
-- --------------------------------------------------
DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL DEFAULT '0000-00-00',
  `pageviews` int(10) NOT NULL DEFAULT '0',
  `uniquevisitors` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `stats`
-- --------------------------------------------------

INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('1', '2014-03-06', '479', '15');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('2', '2014-03-07', '38', '4');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('3', '2014-03-08', '5', '1');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('4', '2014-03-09', '10', '2');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('5', '2014-03-10', '531', '24');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('6', '2014-03-11', '671', '28');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('7', '2014-03-12', '298', '17');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('8', '2014-03-13', '98', '12');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('9', '2014-03-14', '95', '9');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('10', '2014-03-15', '65', '9');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('11', '2014-03-16', '48', '9');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('12', '2014-03-17', '108', '13');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('13', '2014-03-18', '155', '16');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('14', '2014-03-19', '50', '8');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('15', '2014-03-20', '77', '9');
INSERT INTO `stats` (`id`, `day`, `pageviews`, `uniquevisitors`) VALUES ('16', '2014-03-21', '34', '7');


-- --------------------------------------------------
# -- Table structure for table `users`
-- --------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` int(20) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `membership_id` tinyint(3) NOT NULL DEFAULT '0',
  `mem_expire` datetime DEFAULT '0000-00-00 00:00:00',
  `trial_used` tinyint(1) NOT NULL DEFAULT '0',
  `memused` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `token` varchar(40) NOT NULL DEFAULT '0',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `userlevel` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `lastlogin` datetime DEFAULT '0000-00-00 00:00:00',
  `lastip` varchar(16) DEFAULT '0',
  `avatar` varchar(50) DEFAULT NULL,
  `access` text,
  `active` enum('y','n','t','b') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `users`
-- --------------------------------------------------

INSERT INTO `users` (`id`, `fbid`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `memused`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `access`, `active`) VALUES ('1', '0', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '0', '0000-00-00 00:00:00', '0', '0', 'paul.k.pallaghy@gmail.com', 'Paul', 'Pallaghy', '0', '0', '9', '2014-03-06 12:02:52', '2014-03-21 19:23:10', '116.202.27.33', '', '', 'y');
INSERT INTO `users` (`id`, `fbid`, `username`, `password`, `membership_id`, `mem_expire`, `trial_used`, `memused`, `email`, `fname`, `lname`, `token`, `newsletter`, `userlevel`, `created`, `lastlogin`, `lastip`, `avatar`, `access`, `active`) VALUES ('2', '0', 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '0', '0000-00-00 00:00:00', '0', '0', 'test@test.com', 'Test', 'test', '0', '0', '1', '2014-03-16 09:44:08', '2014-03-21 08:44:28', '117.96.60.111', '', '', 'y');


