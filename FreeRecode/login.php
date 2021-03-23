<?php

require('function.php');


$siteTitle = 'ログインページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();


//ログイン認証
require('auth.php');


if (!empty($_POST)) {
  debug('POST送信があります');

  $email = $_POST['email'];
  $pass = $_POST['password'];
  $login_save = (!empty($_POST['login_save'])) ? true : false; //ショートハンド（略記法）という書き方

  debug('バリデーションを開始します');

  //未入力チェック
  validNotInput($email, 'email');
  validNotInput($pass, 'password');

  //半角チェック
  validHalf($pass, 'password');

  //Email形式チェック
  validEmail($email, 'email');

  //最大文字数チェック
  validMaxLen($email, 'email');
  validMaxLen($pass, 'password');

  //最小文字数チェック
  validMinLen($email, 'email');

  if (empty($err_msg)) {
    debug('バリデーションOK');

    //例外処理
    try {
      $dbh = dbConnect();
      $sql = 'SELECT password, id FROM users WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      //クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      debug('クエリ結果の中身：' . print_r($result, true));

      //パスワード照合
      if (!empty($result) && password_verify($pass, array_shift($result))) {
        debug('パスワードがマッチしました');

        //ログイン有効期限（デフォルトを1時間とする）
        $sesLimit = 60 * 60;
        //最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();

        //ログイン保持にチェックがある場合
        if ($login_save) {
          debug('ログイン保持にチェックがあります');
          //ログイン有効期限を30日にしてセット
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        } else {
          //ログイン保持にチェックのない場合
          debug('ログイン保持にチェックがありません');
          //次回からログイン保持はしないので、ログイン有効期限を1時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        //ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];
      }

      header("Location:mypage.php");
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
    }
  }
}

?>

<body>
  <?php
  require('header.php');
  ?>
  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <main>
    <div class="inner-wrap">
      <h2 class="page-title">ログイン</h2>
      <form action="" method="post" class="form-width">

        <span class="area-msg <?php if (!empty($err_msg['common'])) echo 'err'; ?>"><?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?></span>

        <span class="area-msg <?php if (!empty($err_msg['email'])) echo 'err'; ?>"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></span>

        <label>Eメール<br>
          <input type="text" name="email" value="<?php getFormData('email'); ?>">
        </label>

        <span class="area-msg <?php if (!empty($err_msg['password'])) echo 'err'; ?>"><?php if (!empty($err_msg['password'])) echo $err_msg['password']; ?></span>

        <label>パスワード
          <input type="password" name="password">
        </label>
        <div class="login-check"><label>ログイン保持
            <input type="checkbox" name="login_save">
          </label>
        </div>
        <input type="submit" value="送信">
      </form>
      <p style="text-align:center; padding-top: 25px; width:560px; margin:0 auto;">パスワードを忘れた方は<a href="./passRemindSend.php">こちら</a></p>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>