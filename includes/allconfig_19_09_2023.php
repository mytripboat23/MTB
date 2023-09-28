<?php
define('SITE_NAME','mytripboat.com');
define('SITE_NAME_FULL','www.'.SITE_NAME);
define('SITE_TITLE','My Trip Boat');
define('TPRE','travel_');
//Table Definations
define('TABLE_ADMIN',TPRE.'admin');
define('TABLE_CONTENT',TPRE.'content'); 
define('TABLE_SETTINGS',TPRE.'settings');
define('TABLE_SYS_SETTINGS',TPRE.'sys_settings');
define('TABLE_CONTACTUS',TPRE.'contactus');
define('TABLE_CON_REPLY',TPRE.'contact_reply');
define('TABLE_USER',TPRE.'user');
define('TABLE_USER_LOGIN',TPRE.'user_login');
define('TABLE_COUNTRY',TPRE.'country');
define('TABLE_FAQ',TPRE.'faq');

define('TABLE_TESTIMONIAL',TPRE.'testimonial'); 

define('TABLE_USER_AUTH',TPRE.'user_auth');

define('TABLE_META_TAGS',TPRE.'meta_tags'); 
define('TABLE_ADMIN_PAGES',TPRE.'admin_pages');  


define('TABLE_SEND_ENTERNAL_EMAIL',TPRE.'send_internal_email'); 

define('TABLE_ALL_PAGES',TPRE.'all_pages'); 
define('TABLE_SUB_ADMIN_PAGES',TPRE.'sub_admin_pages'); 
 
define('TABLE_USER_OTP',TPRE.'user_otp');  
define('TABLE_RECOVER_PASS',TPRE.'recover_pass');

define('TABLE_PACKAGE',TPRE.'package');
define('TABLE_PACKAGE_LIKE',TPRE.'pck_like');
define('TABLE_PACKAGE_COMMENT',TPRE.'pck_comm');
define('TABLE_PACKAGE_REVIEW',TPRE.'pck_review');
define('TABLE_PACKAGE_SHARE',TPRE.'pck_share');
define('TABLE_PACKAGE_FOLLOW',TPRE.'pck_follow');

define('TABLE_FACILITY_INC',TPRE.'facility_inc');


define('TABLE_FRIENDS',TPRE.'friends');



define('TABLE_LANGUAGE',TPRE.'language');
define('TABLE_CITIES',TPRE.'cities');
define('TABLE_HOBBIES',TPRE.'hobbies');
define('TABLE_USER_SEARCH',TPRE.'user_search');

define('TABLE_NOTIFICATION',TPRE.'notification');

define('TABLE_BOOKMARKS',TPRE.'bookmarks');





define('TABLE_STORY',TPRE.'story');
define('TABLE_STORY_SUB',TPRE.'story_sub');
define('TABLE_STORY_LIKE',TPRE.'story_like');
define('TABLE_STORY_COMMENT',TPRE.'story_comm');

define('TABLE_ADS',TPRE.'ads');
define('TABLE_CAREERS',TPRE.'careers');
define('TABLE_TAGS',TPRE.'tags');

define('ADMIN_SESSION_NAME',TPRE.'admin');





define('GUSER',''); // GMail username
define('GPWD', ''); // GMail password
define('FROM_EMAIL_2', 'no-reply@mytripboat.com'); 
define('ADMIN_EMAIL_1', 'support@mytripboat.com');
define('ADMIN_EMAIL_2', 'sujib.paul2@gmail.com');
 
define("PAYPAL_PAY_MODE",false);
//Paypal Configuration
 

define('GOOGLE_CLIENT_ID', '72278504548-uja1ef2i9p8fp5d6slpl0cf7dctro58t.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URL', 'https://www.mytripboat.com/google-login'); 


//PATH
define('ADMIN','admin/');
define('INCLUDES','includes/');
define('CLASSES','classes/');
define('JS','includes/js/');
define('CSS','css/');
define('CSSA','css/');
define('IMAGES','images/'); 
define('USER','uploads/user/'); 
define('CBANNER','uploads/cbanner/');

define('BANNER','uploads/banner/');  
define('TESTIMONIAL','uploads/testimonial/');
define('PACKAGE','uploads/package/'); 
define('PACKAGE_CROP','uploads/package/300X300_crop/'); 
define('PACKAGE_OPT','uploads/package/300X300_opt/'); 

define('STORY','uploads/story/');  
define('AVATAR','uploads/avatar/');  
define('ID','uploads/id/'); 
define('AD','uploads/ad/'); 
define('RESUME','uploads/resume/');
define('AVATAR_TMP','uploads/avatar_tmp/');  
 
//File Name
define('NO_USER_PHOTO',AVATAR.'noimage.jpg');
define('NO_BANNER',CBANNER.'no_banner.png');
//Messages

define('MAIL_THANK_YOU','My Trip Boat Support Team');
//Site page titles

define('ADMIN_TITLE','Admin panel '.SITE_NAME);
define('FRONT_TITLE','Welcome to Our Site');

// Mail addresses

//  miscellinous  ///

define("TIME",time());

define("DAY",date('d',TIME));
define("MONTH",date('F',TIME));
define("YEAR",date('Y',TIME));

define('ADMIN_LOGO','<img src="images/logo.gif" alt="Home" border="0">');
define('CURRENCY','Rs.');

define('DATE_FORMAT',"M d,Y");
define('FLD_DATE_FORMAT',"d-m-Y");
define('DB_DATE_FORMAT',"Y-m-d");


define('CURRENT_DATE_TIME',date("Y-m-d H:i:s"));
define('FROMNAME',SITE_NAME);

// eof miscellinous //

// text defines //
define('CAPTION_TEXT','Travel Tour '.YEAR.' Admin Panel');
define('FOOTER_TEXT','Copyright &copy; '.YEAR.' '.SITE_NAME.'. All Rights Reserved.');
define('INVALID_LOGIN','Please input correct user name and password');
define('PASSWORD_MISMATCH','Password you entered does not match');
define('EMAIL_MISMATCH','Email you entered does not match');
define('UPDATE_SUCCESSFULL','Updated successfully');
define('DELETE_SUCCESSFULL','Deleted successfully');
define('ADD_SUCCESSFULL','Successfully added');
define('UPLOAD_SUCCESSFULL','File uploaded successfully');

define('USER_ADDED','New user added');
define('EMAIL_USED','Email address already used');
define('USER_EXIST','Username already taken');
define('REGISTER_SUCCESS',"You have registered successfully.");
define('LOGIN_SUCCESS',"You have logged in successfully");
define('LOGOUT_SUCCESS',"You have logged out successfully");
define('PASSWORD_CHANGED',"Your password has been changed successfully");
define('PASSWORD_SENT',"Your password sent successfully to your email address");

define('NEWSLETTER_SENT',"Newsletter sent successfully.");


//array month
$arrMonth = array(
	"1" => "Jan",
	"2" => "Feb",
	"3" => "Mar",
	"4" => "Apr",
	"5" => "May",
	"6" => "Jun",
	"7" => "Jul",
	"8" => "Aug",
	"9" => "Sep",
	"10" => "Oct",
	"11" => "Nov",
	"12" => "Dec"
);

//paypal


define("PAYPAL_ID","");
define("PAYPAL_SANDBOX",FALSE);

define('PAYPAL_RETURN_URL', FURL."thank-you.php");  
define('PAYPAL_CANCEL_URL', FURL."cancel.php");  
define('PAYPAL_NOTIFY_URL',FURL."paypal_ipn.php"); 
define('PAYPAL_CURRENCY', 'USD'); 

define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");
define("TITLE_BANNER_AD_CAMPAIGN_LEAD","Leaderboard Banner");
define("TITLE_BANNER_AD_CAMPAIGN_SQUARE","Square Banner");
define("TESTING_BANNER_ID1",2);
define("LEADERBORAD_PACK_ID",17);
define("SQUARE_PACK_ID",18);

define("GRCSITEK",'6LfXgQwTAAAAAJB4bSKv2sy7APKY_odE1j1aFef9');
define("GRCSECRETK",'6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS');







// defined arrays	//
$image_extensions= array("jpg","jpeg","gif","bmp","flv","swf");
global $image_extensions;


?>