<?php
session_start();
mb_language("ja");
mb_internal_encoding("UTF-8");

// Sanitize helper
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$complete = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // CSRF check
  if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $error = '不正なリクエストです。お手数ですが、はじめからやり直してください。';
  } else {
    // Invalidate token
    unset($_SESSION['csrf_token']);

    // Get form values (already sanitized by confirm.php, but decode for mail body)
    $inquiry_type   = isset($_POST['inquiry_type'])   ? $_POST['inquiry_type']   : '';
    $name_kanji     = isset($_POST['name_kanji'])     ? $_POST['name_kanji']     : '';
    $name_kana      = isset($_POST['name_kana'])      ? $_POST['name_kana']      : '';
    $email          = isset($_POST['email'])          ? $_POST['email']          : '';
    $phone          = isset($_POST['phone'])          ? $_POST['phone']          : '';
    $meeting        = isset($_POST['meeting'])        ? $_POST['meeting']        : '';
    $preferred_date = isset($_POST['preferred_date']) ? $_POST['preferred_date'] : '';
    $message        = isset($_POST['message'])        ? $_POST['message']        : '';

    // Server-side validation
    if (empty($inquiry_type) || empty($name_kanji) || empty($name_kana) || empty($email) || empty($message)) {
      $error = '必須項目が入力されていません。お手数ですが、はじめからやり直してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'メールアドレスが正しくありません。お手数ですが、はじめからやり直してください。';
    } else {

      // Mail header injection prevention
      $email = str_replace(["\r", "\n"], '', $email);

      $admin_to = "hopes.syoshi@gmail.com";
      $from_name = mb_encode_mimeheader("司法書士法人ホープス");
      $from_email = "info@shihousyoshi-hopes.jp";
      $headers = "From: {$from_name} <{$from_email}>";
      $envelope = "-f{$from_email}";
      $datetime = date("Y/m/d H:i:s");

      // ---------- Admin mail ----------
      $admin_subject = "【お問い合わせ】{$name_kanji}様へ連絡をお願いします。";
      $admin_body = <<<EOM
司法書士法人ホープスのホームページに設置したお問い合わせフォームから新しく回答がありました。

お問い合わせ日時：{$datetime}

--------------------------
お問い合わせ内容：{$inquiry_type}
お名前（漢字）：{$name_kanji}
お名前（カナ）：{$name_kana}
メールアドレス：{$email}
電話番号：{$phone}
面談方法：{$meeting}
面談希望日時：{$preferred_date}
お問い合わせ詳細：
{$message}
--------------------------
EOM;

      $admin_sent = mb_send_mail($admin_to, $admin_subject, $admin_body, $headers, $envelope);

      // ---------- User confirmation mail ----------
      $user_subject = '【司法書士法人ホープス】お問い合わせありがとうございました。';
      $user_body = <<<EOM
この度は、司法書士法人ホープスにお問い合わせいただきありがとうございました。
下記の内容で、お問い合わせを承りました。

--------------------------
お問い合わせ内容：{$inquiry_type}
お名前（漢字）：{$name_kanji}
お名前（カナ）：{$name_kana}
メールアドレス：{$email}
電話番号：{$phone}
面談方法：{$meeting}
面談希望日時：{$preferred_date}
お問い合わせ詳細：
{$message}
--------------------------

お問い合わせ内容につきましては、担当者より対応させていただきます。
今後とも、司法書士法人ホープスをよろしくお願い致します。

━━━━━━━━━━━━━━━━━━━━
司法書士法人ホープス
〒150-0002 東京都渋谷区渋谷二丁目2番4号 青山アルコープ402号室
TEL: 03-6450-6794
E-mail: info@shihousyoshi-hopes.jp
━━━━━━━━━━━━━━━━━━━━
EOM;

      $user_sent = mb_send_mail($email, $user_subject, $user_body, $headers, $envelope);

      if ($admin_sent && $user_sent) {
        $complete = "送信が完了しました。";
      } else {
        $error = "送信に失敗しました。お手数ですが、はじめから再度やり直してください。";
      }
    }
  }
} else {
  header('Location: contact.html');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/favicon.ico">
  <title>送信完了 | 司法書士法人ホープス</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="style.css">
</head>
<body class="contact-page thanks-page">

  <!-- Header -->
  <header class="header" id="header">
    <div class="header-inner">
      <a href="index.html" class="logo">
        <span class="logo-main">司法書士法人ホープス</span>
        <span class="logo-divider" aria-hidden="true"></span>
        <span class="logo-en" translate="no">HOPES</span>
      </a>
      <nav>
        <ul class="nav-list" id="navList">
          <li><a href="index.html#service">サービス</a></li>
          <li><a href="index.html#price">料金</a></li>
          <li><a href="index.html#office">事務所概要</a></li>
          <li><a href="privacy.html">個人情報保護方針</a></li>
          <li><a href="contact.html" class="nav-cta">お問い合わせ</a></li>
        </ul>
      </nav>
      <button class="hamburger" id="hamburger" aria-label="メニュー">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-bg"></div>
    <div class="container">
<?php if ($complete): ?>
      <h1>お問い合わせを受け付けました</h1>
      <p>ご記入いただいた情報は無事送信されました。</p>
<?php else: ?>
      <h1>送信エラー</h1>
      <p>送信処理中にエラーが発生しました。</p>
<?php endif; ?>
    </div>
  </div>

  <!-- Thanks / Error -->
  <section class="contact-section">
    <div class="container">
      <div class="thanks-content">
<?php if ($complete): ?>
        <p>お問い合わせいただきありがとうございました。</p>
        <p>確認後、数日以内に担当者からご連絡いたします。</p>
        <p>ご入力いただいたメールアドレスに確認メールをお送りしましたので、ご確認ください。</p>
<?php else: ?>
        <p class="error-message"><?php echo $error; ?></p>
<?php endif; ?>
        <div class="thanks-actions">
          <a href="index.html" class="btn btn-primary">ホームへ戻る</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-row">
        <span class="footer-name">司法書士法人ホープス</span>
        <span class="footer-sep" aria-hidden="true"></span>
        <span class="footer-address">〒150-0002 東京都渋谷区渋谷二丁目2番4号 青山アルコープ402号室</span>
        <span class="footer-sep" aria-hidden="true"></span>
        <a href="contact.html">お問い合わせ</a>
        <span class="footer-sep" aria-hidden="true"></span>
        <a href="privacy.html">個人情報保護方針</a>
      </div>
      <div class="footer-bottom">
        &copy; <span id="copyrightYear"></span> 司法書士法人ホープス All Rights Reserved.
      </div>
    </div>
  </footer>

  <script>
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 40);
    });
    const hamburger = document.getElementById('hamburger');
    const navList = document.getElementById('navList');
    hamburger.addEventListener('click', () => {
      const isOpen = hamburger.classList.toggle('active');
      hamburger.setAttribute('aria-expanded', isOpen);
      navList.classList.toggle('active');
    });
    navList.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navList.classList.remove('active');
      });
    });
    document.getElementById('copyrightYear').textContent = new Date().getFullYear();
  </script>

</body>
</html>
