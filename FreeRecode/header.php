<?php if (!isset($_SESSION['login_date']) ||
  basename($_SERVER['PHP_SELF']) === 'signup.php') {
  //未ログイン用のヘッダー
?>
  <header>
    <div class="inner-wrap top-and-bottom-center">
      <h1 class="site-title"><a href="./signup.php"><img src="img/logo.png" alt="" width="175px"></a></h1>
      <nav>
        <ul>
          <li><a href="./login.php">ログイン</a></li>
          <li><a href="./signup.php">ユーザー登録</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <?php } else {
  if (basename($_SERVER['PHP_SELF']) === 'write.php' ||
    basename($_SERVER['PHP_SELF']) === 'profEdit.php' ||
    basename($_SERVER['PHP_SELF']) === 'passEdit.php') {
    //write,profEdit,passEditが表示される時のヘッダー
  ?>
    <header>
      <div class="inner-wrap top-and-bottom-center">
        <h1 class="site-title"><a href="./mypage.php"><img src="img/logo.png" alt="" width="175px"></a></h1>
        <nav>
          <ul>
            <li><a href="./logout.php">ログアウト</a></li>
            <li><a href="./mypage.php">マイページ</a></li>
          </ul>
        </nav>
      </div>
    </header>
  <?php } else {
    //ログイン済みのヘッダー(上で指定しているページ以外のページ用)
  ?>
    <header>
      <div class="inner-wrap top-and-bottom-center">
        <h1 class="site-title"><a href="./mypage.php"><img src="img/logo.png" alt="" width="175px"></a></h1>
        <nav>
          <ul>
            <li><a href="./logout.php">ログアウト</a></li>
            <li><a href="./write.php">書く</a></li>
          </ul>
        </nav>
      </div>
    </header>
<?php
  }
} ?>