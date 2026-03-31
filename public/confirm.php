<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: contact.html');
  exit;
}

// CSRF token generation
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Sanitize input
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$inquiry_type  = isset($_POST['inquiry_type'])  ? h($_POST['inquiry_type'])  : '';
$name_kanji    = isset($_POST['name_kanji'])    ? h($_POST['name_kanji'])    : '';
$name_kana     = isset($_POST['name_kana'])     ? h($_POST['name_kana'])     : '';
$email         = isset($_POST['email'])         ? h($_POST['email'])         : '';
$phone         = isset($_POST['phone'])         ? h($_POST['phone'])         : '';
$meeting       = isset($_POST['meeting'])       ? h($_POST['meeting'])       : '';
$preferred_date = isset($_POST['preferred_date']) ? h($_POST['preferred_date']) : '';
$message       = isset($_POST['message'])       ? h($_POST['message'])       : '';

// Server-side validation
$errors = [];
if (empty($inquiry_type))  $errors[] = 'お問い合わせ内容を選択してください。';
if (empty($name_kanji))    $errors[] = 'お名前（漢字）を入力してください。';
if (empty($name_kana))     $errors[] = 'お名前（カナ）を入力してください。';
if (empty($email))         $errors[] = 'メールアドレスを入力してください。';
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = '正しいメールアドレスを入力してください。';
if (empty($message))       $errors[] = 'お問い合わせ詳細を入力してください。';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/favicon.ico">
  <title>入力内容の確認 | 司法書士法人ホープス</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="style.css">
</head>
<body class="contact-page confirm-page">

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
      <h1>入力内容の確認</h1>
      <p>以下の内容でお間違いなければ「送信する」ボタンを押してください。</p>
    </div>
  </div>

  <!-- Confirm -->
  <section class="contact-section">
    <div class="container">

<?php if (!empty($errors)): ?>
      <div class="form-errors-summary">
        <p>入力内容にエラーがあります。</p>
        <ul>
          <?php foreach ($errors as $err): ?>
          <li><?php echo $err; ?></li>
          <?php endforeach; ?>
        </ul>
        <p><a href="javascript:history.back();" class="btn btn-secondary">戻って修正する</a></p>
      </div>
<?php else: ?>
      <div class="confirm-table-wrap">
        <table class="confirm-table">
          <tbody>
            <tr>
              <th>お問い合わせ内容 <span class="badge-required">必須</span></th>
              <td><?php echo $inquiry_type; ?></td>
            </tr>
            <tr>
              <th>お名前（漢字）<span class="badge-required">必須</span></th>
              <td><?php echo $name_kanji; ?></td>
            </tr>
            <tr>
              <th>お名前（カナ）<span class="badge-required">必須</span></th>
              <td><?php echo $name_kana; ?></td>
            </tr>
            <tr>
              <th>メールアドレス <span class="badge-required">必須</span></th>
              <td><?php echo $email; ?></td>
            </tr>
            <tr>
              <th>電話番号 <span class="badge-optional">任意</span></th>
              <td><?php echo $phone ?: '―'; ?></td>
            </tr>
            <tr>
              <th>面談方法 <span class="badge-optional">任意</span></th>
              <td><?php echo $meeting ?: '―'; ?></td>
            </tr>
            <tr>
              <th>面談希望日時 <span class="badge-optional">任意</span></th>
              <td><?php echo $preferred_date ?: '―'; ?></td>
            </tr>
            <tr>
              <th>お問い合わせ詳細 <span class="badge-required">必須</span></th>
              <td class="confirm-message"><?php echo nl2br($message); ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <form action="thanks.php" method="post" class="confirm-actions">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="inquiry_type" value="<?php echo $inquiry_type; ?>">
        <input type="hidden" name="name_kanji" value="<?php echo $name_kanji; ?>">
        <input type="hidden" name="name_kana" value="<?php echo $name_kana; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">
        <input type="hidden" name="meeting" value="<?php echo $meeting; ?>">
        <input type="hidden" name="preferred_date" value="<?php echo $preferred_date; ?>">
        <input type="hidden" name="message" value="<?php echo $message; ?>">
        <div class="confirm-buttons">
          <button type="button" class="btn btn-secondary" onclick="history.back();">戻って修正する</button>
          <button type="submit" class="btn btn-primary">送信する</button>
        </div>
      </form>
<?php endif; ?>

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
