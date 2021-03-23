<?php

require('function.php');

$siteTitle = 'ユーザー登録ページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();



if (!empty($_POST)) {
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $pass_re = $_POST['password_retype'];

  debug('バリデーションを開始します');

  validNotInput($email, 'email');
  validNotInput($pass, 'password');
  validNotInput($pass_re, 'password_retype');

  validMaxLen($email, 'email');
  validMaxLen($pass, 'password');
  validMaxLen($pass_re, 'password_retype');

  validMinLen($email, 'email');
  validMinLen($pass, 'password');
  validMinLen($pass_re, 'password_retype');

  validHalf($pass, 'password');
  validHalf($pass_re, 'password_retype');

  validEmail($email, 'email');

  validEmailDuplicate($email);

  validPassMatch($pass, $pass_re, 'password');

  if (empty($err_msg)) {

    debug('バリデーションOK');

    //例外処理
    try {
      $dbh = dbConnect();
      debug('dbh通過');

      $sql = 'INSERT INTO `users` (email,password,login_time,created_date) VALUES (:email,:password,:login_time,:created_date)';

      debug('SQL通過');

      $data = array(
        ':email' => $email,
        ':password' => password_hash($pass, PASSWORD_DEFAULT),
        ':login_time' => date("Y-m-d H:i:s"),
        ':created_date' => date("Y-m-d H:i:s")
      );

      $stmt = queryPost($dbh, $sql, $data);

      debug(print_r($stmt, true));

      if ($stmt) {
        debug('クエリ成功');
        $sesLimit = 60 * 60;

        $_SESSION['login_date'] = time();
        $_SESSION['login_limit'] = $sesLimit;
        //ユーザーIDを格納
        $_SESSION['user_id'] = $dbh->lastInsertId();

        debug('セッション変数の中身：' . print_r($_SESSION, true));

        header("Location:mypage.php");
      }
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG8;
    }
  }
}

?>

<body>
  <?php
  require('header.php');
  ?>
  <main>
    <div class="inner-wrap">
      <h2 class="page-title">ユーザー登録</h2>
      <form action="" method="post" class="form-width">

        <span class="area-msg <?php if (!empty($err_msg['common'])) echo 'err'; ?>"><?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?></span>



        <label>Eメール<br><span class="area-msg <?php if (!empty($err_msg['email'])) echo 'err'; ?>"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
          <input type="text" name="email">
        </label>


        <label>パスワード<br>
          <span class="area-msg <?php if (!empty($err_msg['password'])) echo 'err'; ?>"><?php if (!empty($err_msg['password'])) echo $err_msg['password']; ?></span>
          <input type="password" name="password">
        </label>


        <label>パスワード（再入力）<br>
          <span class="area-msg <?php if (!empty($err_msg['password_retype'])) echo 'err'; ?>"><?php if (!empty($err_msg['password_retype'])) echo $err_msg['password_retype']; ?></span>
          <input type="password" name="password_retype">
        </label>
        <input type="submit" value="送信">
        <br>
      </form>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>