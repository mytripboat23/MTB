<?php

$googleapi = dirname(dirname(__FILE__)) . '/googleapi/vendor/autoload.php';

require_once($googleapi);
//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('72278504548-uja1ef2i9p8fp5d6slpl0cf7dctro58t.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI

$google_client->setRedirectUri('https://www.mytripboat.com/google-login');

//
$google_client->addScope('email');

$google_client->addScope('profile');

?>