<?php
session_start();

// Twitter kütüphanesinden gerekli iki dosyayı(oauth class ve config)
// require_once ile script'e dahil ediyoruz.
require_once('Twitter/twitteroauth/twitteroauth.php');
require_once('Twitter/config.php');

// Burada farkındaysanız 3. ve 4. parametreler verilmemiş.
// Çünkü henüz o parametrelere atananacak olan anahtarlara
// sahip değiliz. Onlara sahip olmak için Twitter'a login
// isteğinde bulunmamız gerek. Yine burda $connection nesnesi
// oluşturuyoruz.
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

// Burada twitter'a login isteğinde bulunmamız için şart
// koşulan request token değerlerini üretiyoruz.
// Parametrede verilen OAUTH_CALLBACK config.pgp dosyamızdan
// ayarlanmalıdır.
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

// Oluşan anahtarları tekrar kullanabilmek için oturuma yazıyoruz.
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

// Eğer connection response kodu 200 ise herşey yolunda ve connect
// işlemini sağlayacak olan url'i isteyebiliriz.
if ( $connection->http_code == 200 ){
  // Giriş için link, buton veya herhangi bir şekilde twitter'a login
  // edeceğimiz bir sayfa gerekecek. Hemen alttaki getAuthorizeURL
  // metodu bizim için twitter uygulamamıza bağlantı arayüzü sunan bir
  // url üretecek ve siz bunu istediğiniz gibi kullanabileceksiniz.
  $url = $connection->getAuthorizeURL($token);
  echo '<a href="' . $url . '">Twitter Connect</a>';
}
else{
  echo "Uygulama ayarlarınızı kontrol edin";
}
?>
