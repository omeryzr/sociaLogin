<?php

session_start();

// Twitter kütüphanesinden gerekli iki dosyayı(oauth class ve config)
// require_once ile script'e dahil ediyoruz.
require_once('Twitter/twitteroauth/twitteroauth.php');
require_once('Twitter/config.php');

// Tw uygulama izin ekranından izin ver butonuna tıklandıktan sonra
// önceden bildirdiğimiz callback sayfasına oauth_verifier ve oauth_token
// parametreleriyle birlikte döner. Yani callback.php?oauth_token=xxx&oauth_verifier=yyy
// bu parametrelerden oauth_token anhatarının olup olmadığı ve yine bu anahtarla
// SESSION globalinizdeki oauth_token anahtarının eşit olup olmadığını
// kontrol eder. Eğer koşul sağlanmıyorsa ise login.php'ye yönlendirir.
if (isset($_GET['oauth_token']) && $_SESSION['oauth_token'] !== $_GET['oauth_token']) {
  header('Location: login.php');
}

// TwitterOAuth sınıfını gerekli başlangıç değerlerini vererek connection
// nesnesi oluşturuyoruz. Buradaki CONSUMER_KEY ve CONSUMER_SECRET sabit
// değerlerini Twitter/config.php dosyasından ayarlıyoruz.
// Bu değerleri dev.twitter.com adresindeki uygulamalar bölümünde ya uygulama
// açarak ya da mevcut uygulamaların detay bilgileri arasından alabilirsiniz.
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// sayfamıza twitter tarafından parametre geçilen oauth_verifier anahtarını
// $access_token isimli bir değişkene aktarıyoruz. Bu bizim twitter
// tarafından şahsımıza verilen tekil bir doğrulama anahtarıdır.
$access_token = $connection->getAccessToken($_GET['oauth_verifier']);

// Bu anahtarı SESSION globaline yazıyoruz. Sebebi ise uygulamaya bağlı olup
// olmadığımızı ve REST Api kullanımında gerekli yerlerde bu anahtarı twitter'ın
// bizden istiyor oluşudur.
$_SESSION['access_token'] = $access_token;

// Bir üst satırda artık elimizde bize ait bir SESSION oluştuğuna göre
// aşağıdakilere ihtiyacımız kalmadı ve bunları temizleyelim.
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

// $connection nesnesinden HTTP RESPONSE eğer 200 dönüyorsa herşey
// yolunda demektir. Artık istediğimiz sayfaya yönlendirebiliriz.
if ($connection->http_code == 200)
  header('Location: index.php');
// Eğer yolunda değilse uygulamanın size verdiği anahtarlarda bir yanlışlık
// olabilir veya izin sonrası dönen oauth_token anahtarının tekrar kullanılmak
// istenmesinden olabilir. oauth_token anahtarı bu arada tek kullanımlıktır.
else
  header('Location: login.php');

?>
