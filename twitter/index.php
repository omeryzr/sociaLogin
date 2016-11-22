<?php

session_start();

// Twitter kütüphanesinden gerekli iki dosyayı(oauth class ve config)
// require_once ile script'e dahil ediyoruz.
require_once('Twitter/twitteroauth/twitteroauth.php');
require_once('Twitter/config.php');

// callback.php'de oluşacak olan access_token SESSION değerini
// farklı bir değişkene aktarıyoruz.
$sesAccessToken = $_SESSION['access_token'];

// Twitter oturumunun açık olup olmadığı bilgisini tutan
// session değerlerinin varolup olnadığının kontrolünü yapıyoruz.
// Eğer yoksa connect.php'ye yonlendiriyoruz
if (empty($sesAccessToken) || empty($sesAccessToken['oauth_token']) || empty($sesAccessToken['oauth_token_secret'])) {
  header('Location: connect.php');
}

// TwitterOAuth sınıfını gerekli başlangıç değerlerini vererek connection
// nesnesi oluşturuyoruz. Buradaki CONSUMER_KEY ve CONSUMER_SECRET sabit
// değerlerini Twitter/config.php dosyasından ayarlıyoruz.
// Bu değerleri dev.twitter.com adresindeki uygulamalar bölümünde ya uygulama
// açarak ya da mevcut uygulamaların detay bilgileri arasından alabilirsiniz.
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $sesAccessToken['oauth_token'], $sesAccessToken['oauth_token_secret']);

// Ve yaratılan $connection nesnesinin get metodunu Twitter REST Api'ye
// ulaşmak için kullanıyoruz. Burada login olan kullanıcının profil bilgileri
// çekilmektedir.
$content = $connection->get('account/verify_credentials');

?>

<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Coders' Grave | Twitter Authentication Demo</title>
</head>
<body>
Twitter bağlantısı başarıyla sağlandı.<br /><br />
Merhaba <strong><?php echo $content->name ?></strong>
</body>
</html>
