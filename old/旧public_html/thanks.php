<?php
mb_language("ja");
mb_internal_encoding("UTF-8");
// 初期化
$complete = '';
$error = '';
// POSTメソッド
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //----- フォームに入力された内容 ここから
  $list = $_POST['list']; //お問い合わせ内容
  $name = $_POST['name']; //お名前（漢字）
  $kana = $_POST['kana']; //お名前（カナ）
  $email = $_POST['email']; //メールアドレス
  $tel = $_POST['tel']; //電話番号
  $hope1 = $_POST['hope1']; //面談日時　第1希望
  $hope2 = $_POST['hope2']; //面談日時　第2希望
  $hope3 = $_POST['hope3']; //面談日時　第3希望
  $method = $_POST['method']; //面談方法
  $content = $_POST['content']; //お問い合わせ詳細
  //----- フォームに入力された内容 ここまで

  // 管理者のメールアドレス
  $to = "hopes.syoshi@gmail.com";
  //確認用
  // $to = $email;

  // 件名
  $subject = "【お問い合わせ】{$name}様へ連絡をお願いします。";
  // お問い合わせ日時
  $datetime = date("Y/m/d H:i:s");

  $headers = "From: " . mb_encode_mimeheader("司法書士法人ホープス") . "<info@shihousyoshi-hopes.jp>";

  //---------- 管理者へのメール本文 ここから ----------
  $msg = <<< EOM
  司法書士法人ホープスのホームページに設置したお問い合わせフォームから新しく回答がありました。

--------------------------
お問い合わせ内容：{$list}
お名前（漢字）：{$name}
お名前（カナ）：{$kana}
メールアドレス：{$email}
電話番号：{$tel}
面談日時　第1希望：{$hope1}
面談日時　第2希望：{$hope2}
面談日時　第3希望：{$hope3}
面談方法：{$method}
お問い合わせ詳細：
{$content}
--------------------------
EOM;
  if (mb_send_mail($to, $subject, $msg, $headers, '-f' . 'info@shihousyoshi-hopes.jp')) {
    $complete = "送信が完了しました";
  } else {
    $error =  "送信に失敗しました。<br />お手数ですがはじめから再度やり直して下さい。";
  }
  //---------- 管理者へのメール ここまで ----------
  // 初期化
  $msg = '';
  $subject = '';

  //---------- お客様へのメール ここから ----------
  $subject = '【司法書士法人ホープス】お問い合わせありがとうございました。';
  $msg = <<< EOM
この度は、司法書士法人ホープスにお問い合わせいただきありがとうございました。
下記の内容で、お問い合わせ承りました。

--------------------------
お問い合わせ内容：{$list}
お名前（漢字）：{$name}
お名前（カナ）：{$kana}
メールアドレス：{$email}
電話番号：{$tel}
面談日時　第1希望：{$hope1}
面談日時　第2希望：{$hope2}
面談日時　第3希望：{$hope3}
面談方法：{$method}
お問い合わせ詳細：
{$content}
--------------------------

お問い合わせ内容につきましては、担当者より対応させていただきます。
今後とも、司法書士法人ホープスをよろしくお願い致します。
EOM;

  if (mb_send_mail($email, $subject, $msg, $headers, '-f' . 'info@shihousyoshi-hopes.jp')) {
    $complete = "送信が完了しました";
  } else {
    $error =  "送信に失敗しました。<br />お手数ですがはじめから再度やり直して下さい。";
  }
  //---------- お客様へのメール ここまで ----------
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <title>お問い合わせ｜司法書士法人ホープス</title>
  <meta name="description" content="" />
  <link rel="stylesheet" href="//use.typekit.net/ray5xjz.css" />
  <link rel="preconnect" href="//fonts.googleapis.com" />
  <link rel="preconnect" href="//fonts.gstatic.com" crossorigin />
  <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&family=Noto+Serif+JP:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/swiper8.1.5-bundle.min.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <script src="./assets/js/jquery-3.6.0.min.js"></script>
  <script src="./assets/js/swiper8.1.5-bundle.min.js"></script>
  <script src="./assets/js/smooth-scroll.min.js"></script>
  <script src="./assets/js/picturefill.min.js"></script>
  <script src="./assets/js/fitie.js"></script>
  <script src="./assets/js/bundle.js" defer></script>
</head>

<body class="contact-page contact-page__02 contact-page__thanks">
  <header id="header" class="header">
    <div class="header__left">
      <div class="logo">
        <a href="./">
          <span class="mincho">司法書士法人ホープス</span><span class="baskerville">Hopes</span>
        </a>
      </div>
    </div>
    <div class="header__right">
      <ul>
        <li>
          <a href="./" id="home" class="">
            ホーム
            <span class="vollkorn">Home</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./about.html" id="about">
            私たちについて
            <span class="vollkorn">About us</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./service.html" id="service">
            サービス内容
            <span class="vollkorn">Service</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./price.html" id="price">
            料金表
            <span class="vollkorn">Price</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./profile.html" id="profile">
            事務所概要
            <span class="vollkorn">Profile</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./contact.html" id="" class="lg">
            お問い合わせ
            <span class="vollkorn">Contact</span>
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li class="privacy">
          <a href="./privacy.html" id="privacy">
            プライバシーポリシー
            <img src="./assets/images/sp-arrow.png" alt="" class="arrow" />
          </a>
        </li>
        <li>
          <a href="./contact.html" class="contact">
            <span>お問い合わせ</span>
            <img src="./assets/images/mail-white.png" alt="メール" class="mail white" />
            <img src="./assets/images/mail-blue.png" alt="メール" class="mail blue" />
            <span class="vollkorn">Contact</span>
          </a>
        </li>
      </ul>
    </div>
    <div id="burger" class="burger">
      <div class="box">
        <div class="box__top">
          <div></div>
        </div>
        <div class="box__bottom">
          <span>MENU</span>
        </div>
      </div>
    </div>
  </header>
  <main>
    <section class="sec sec01">
      <div class="container">
        <div class="inner">
          <div class="main-title">
            <span class="vollkorn">Thank you</span>
            <h2>お問い合わせを受け付けました</h2>
          </div>
          <div class="txt">
            <p>
              ご記入いただいた情報は無事送信されました。<br class="sp">お問い合わせいただきありがとうございました。<br>
              確認後、数日以内に担当者からご連絡します。
            </p>
          </div>
          <div class="btn-area">
            <a href="./" class="btn bgskew">
              <span class="vollkorn">ホーム画面へ</span>
              <img src="./assets/images/arrow-right-white.png" alt="" class="arrow white" />
              <img src="./assets/images/arrow-right-blue.png" alt="" class="arrow blue" />
            </a>
          </div>
        </div>
      </div>
      </div>
    </section>
  </main>
  <footer id="footer" class="footer sec">
    <div class="container">
      <div class="inner">
        <div class="item">
          <div class="item__left">
            <a href="./"> 司法書士法人ホープス </a>
            <p class="copy-right">©︎2022 司法書士法人ホープス.</p>
          </div>
          <div class="item__right">
            <ul>
              <li>
                <a href="./">ホーム</a>
              </li>
              <li>
                <a href="./about.html">私たちについて</a>
              </li>
              <li>
                <a href="./service.html">サービス内容</a>
              </li>
            </ul>
            <ul>
              <li>
                <a href="./price.html">料金表</a>
              </li>
              <li>
                <a href="./profile.html">事務所概要</a>
              </li>
              <li class="sp">
                <a href="./contact.html">お問い合わせ</a>
              </li>
              <li>
                <a href="./privacy.html">プライバシーポリシー</a>
              </li>
            </ul>
            <ul>
              <p class="address">
                〒150-0002<br />
                東京都渋谷区渋谷二丁目2番4号<br />
                青山アルコープ402号室
              </p>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="page-top">
      <a href="#" class="circle">
        <img src="./assets/images/arrow-top-blue.png" alt="" class="blue" />
        <img src="./assets/images/arrow-top-white.png" alt="" class="white" />
      </a>
      <span class="vollkorn">Page top</span>
    </div>
  </footer>
</body>

</html>