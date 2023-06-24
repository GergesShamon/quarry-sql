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
             <h2>تحديث صفحة في ويكيبيديا العربية عن طريق استعلام إس كيو إل</h2>
<form action="/" method="post">
               <div class="mb-3 mt-3">
                 <label for="email" class="form-label">عنوان الصفحة:</label>
                 <input value="<?php if (!empty($_POST['name'])) { echo $_POST['name'];} ?>" class="form-control" name="name" required />
               </div>
               <div class="mb-3">
                 <label for="pwd" class="form-label">كود استعلام إس كيو إل:</label>
                 <textarea class="form-control" name="code" required><?php if (!empty($_POST['code'])) { echo $_POST['code'];} ?></textarea>
               </div>
               
               <button type="submit" class="btn btn-primary">نشر</button>
             </form>
             
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
$apiUrl = preg_replace( '/index\.php.*/', 'api.php', $oauthUrl );
$conf = new ClientConfig( $oauthUrl );
$conf->setConsumer( new Consumer( $consumerKey, $consumerSecret ) );
$conf->setUserAgent( 'Quarry Sql/2.0' );
$client = new Client( $conf );
session_start();
$accessToken = new Token( $_SESSION['access_key'], $_SESSION['access_secret'] );


try {
$ident = $client->identify( $accessToken );
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (empty($_POST['name'])) {
    echo "Name is empty";
  } else {
    $sqlQuery = ($_POST['code']);

$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file($ts_pw['dir'] . "/replica.my.cnf");
$mysqli = new mysqli('arwiki.analytics.db.svc.wikimedia.cloud', $ts_mycnf['user'], $ts_mycnf['password'], 'arwiki_p');
unset($ts_mycnf, $ts_pw);

$result = $mysqli->query( $sqlQuery);
$dataSend = "<table>

        <tr>";
                        $resultsFound = true;
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                foreach ($row as $column => $value) {
                    $dataSend = $dataSend."<th>" . htmlspecialchars($column) . "</th>";
                }
                $dataSend = $dataSend."</tr>";

                // Rewind the result set pointer back to the beginning
                $result->data_seek(0);

                while ($row = $result->fetch_assoc()) {
                    $dataSend = $dataSend."<tr>";
                    foreach ($row as $value) {
                        $dataSend =$dataSend. "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    $dataSend =$dataSend. "</tr>";
                }
            } else {
                $resultsFound = false;
                            }
$dataSend =$dataSend. "</table>";
 if($resultsFound ){
 $editToken = json_decode( $client->makeOAuthCall(
	$accessToken,
	"$apiUrl?action=query&meta=tokens&format=json"
) )->query->tokens->csrftoken;
$apiParams = [
	'action' => 'edit',
	'title' =>$_POST['name'] ,
	'summary' => 'test 1',
	'text' => $dataSend,
	'token' => $editToken,
	'format' => 'json',
];
$editResult = json_decode( $client->makeOAuthCall(
	$accessToken,
	$apiUrl,
	true,
	$apiParams
) );
if($editResult->edit->result == "Success"){
echo '<div class="alert alert-success" role="alert">
تمت العملية بنجاح
</div>';
}else{
echo '<div class="alert alert-danger" role="alert">
حدث خطأ غير متوقع، تأكد إن صفحة غير محمية.
</div>';
}

}else {
echo '<div class="alert alert-warning" role="alert">
  لم يتم العثور على نتائج، قد يكون كود إس كيو إل خطأ.
</div>';
}

  }
}
}catch(Exception $e) {
if(strpos($e->getMessage(), "access-token-not-found") !== false){

header('Location: /login.php');

}else {
echo $e->getMessage();
}
}
?>
           </div>
         </div>
       </div>
     </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="cdn_modules/bootstrap@5.3.0/js/bootstrap.min.js"></script>
  </body>
</html>

