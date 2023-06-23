<?php

// Require the library and set up the classes we're going to use in this first part.
require_once __DIR__ . '/vendor/autoload.php';

use MediaWiki\OAuthClient\Client;
use MediaWiki\OAuthClient\ClientConfig;
use MediaWiki\OAuthClient\Consumer;
use MediaWiki\OAuthClient\Token;
// Make sure the config file exists. This is just to make sure the demo makes sense if someone loads
// it in the browser without reading the documentation.
$configFile = __DIR__ . '/config.php';
if ( !file_exists( $configFile ) ) {
	echo "Configuration could not be read. Please create $configFile by copying config.dist.php";
	exit( 1 );
}

// Get the wiki URL and OAuth consumer details from the config file.
require_once $configFile;
// Configure the OAuth client with the URL and consumer details.
$conf = new ClientConfig( $oauthUrl );
$conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );
$conf->setUserAgent( 'Quarry Sql/2.0' );
$client = new Client( $conf );
session_start();
$accessToken = new Token( $_SESSION['access_key'], $_SESSION['access_secret'] );

// Send an HTTP request to the wiki to get the authorization URL and a Request Token.
// These are returned together as two elements in an array (with keys 0 and 1).
list( $authUrl, $token ) = $client->initiate();

// Store the Request Token in the session. We will retrieve it from there when the user is sent back
// from the wiki (see demo/callback.php).

$_SESSION['request_key'] = $token->key;
$_SESSION['request_secret'] = $token->secret;



?>

<!DOCTYPE html>
<html lang="ar">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استعلامات إس كيو إل</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300&display=swap" rel="stylesheet">
    <link href="/cdn_modules/bootstrap@5.3.0/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
    *{font-family: 'Tajawal', sans-serif;}
.navbar{
  padding: 10px;
  direction: rtl;
}
.container{
  padding: 25px;
}
    </style>
  </head>
  
  <body>
     <header>
       <nav class="navbar navbar-expand-md navbar-dark bg-dark">
         <a class="navbar-brand" href="/">استعلامات إس كيو إل</a>
       </nav>
     </header>
     <div class="container">
       <div class="row">
         <div class="col">
           <div dir="rtl">
             <h2>قبل استخدام تحتاج تسجل الدخول</h2>
             <a href="<?php echo $authUrl; ?>" class="btn btn-primary">تسجيل الدخول</a>
           </div>
         </div>
       </div>
     </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="cdn_modules/bootstrap@5.3.0/js/bootstrap.min.js"></script>
  </body>
</html>

