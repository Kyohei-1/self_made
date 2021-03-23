<?php

//DBからユーザー情報を取得
//POSTされているかチェック
//バリデーションチェック
//DB接続
//レコード更新
//パスワード変更メール送信
//マイページへ遷移

require('function.php');

$siteTitle = 'パスワード変更ページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();

//ログイン認証
require('auth.php');

//ユーザーIDを格納
$u_id = $_SESSION['user_id'];

//ユーザー情報を取得
$u_data = getUser($u_id);

// var_dump($u_data);

if (!empty($_POST)) {
  $old_pass = $_POST['old_pass'];
  $new_pass = $_POST['new_pass'];
  $retype_new_pass = $_POST['retype_new_pass'];

  validNotInput($old_pass, 'old_pass');
  validNotInput($new_pass, 'new_pass');
  validNotInput($retype_new_pass, 'retype_new_pass');

  if (empty($err_msg)) {
    debug('未入力チェックOK');

    debug('バリデーションを開始します');
    //最小、最大、半角英数字、古いパスワードと新しいパスワードが異なっているか、新しいパスワードと、再入力の新しいパスワードの値が同じか

    //最大＆最小文字数、半角チェックをvalidPassで一気に行ってる
    validPass($old_pass, 'old_pass');
    validPass($new_pass, 'new_pass');
    validPass($retype_new_pass, 'retype_new_pass');

    debug('最大、最小、半角チェック通過');

    //新しいパスワードと新しいパスワード（再入力）があっているか
    validPassMatch($new_pass, $retype_new_pass, 'new_pass');
    debug('新しいパスワードは両方とも同値です');

    //古いパスワードとDBのパスワードをチェック
    validFormAndDBValuesSame($old_pass, $u_data[0]['password'], 'old_pass');

    //古いパスワードと新しいパスワードが異なっているか
    //上で新しいパスワードが両方とも同値なのは判明しているので、
    //古いパスワードと、新しいパスワードの片方を比較すればOK
    validPassUnmatch($old_pass, $new_pass, 'old_pass');
    debug('古いパスワードとDBのパスワードを照合:[OK]');




    if (empty($err_msg)) {

      debug('バリデーションOK');

      //例外処理
      try {
        $dbh = dbConnect();
        $sql = 'UPDATE `users` SET password = :pass WHERE id = :u_id';
        $data = array(
          ':pass' => password_hash($new_pass, PASSWORD_DEFAULT),
          ':u_id' => $u_id
        );
        $stmt = queryPost($dbh, $sql, $data);

        //クエリ成功の場合
        if ($stmt) {
          debug('クエリ成功');
          $_SESSION['msg_success'] = SUC1;

          //メールを送信
          $username = ($u_data[0]['username']) ? $u_data[0]['username'] : '名無し';
          $from = 'info@webukatu.com';
          $to = $u_data[0]['email'];
          $subject = 'パスワード変更通知 | Free Record';
          //EOFはEnd To Fileの略。ABCでも何でもいい。
          //先頭の<<<の後の文字列と合わせること。
          //最後のEOTの前後に空白など何も入れてはいけない。
          //EOT内の半角空白も全てそのまま半角空白として扱われるので、
          //インデントはしないこと。
          $comment = <<<EOF
{$username}　さん
パスワードが変更されました。

////////////////////////////////////////
Free Record 運営事務局
URL http://free_record.com/
Email info@free_record.com
////////////////////////////////////////
EOF;
          sendMail($from, $to, $subject, $comment);
          header("Location:mypage.php");
        } else {
          debug('クエリに失敗しました');
          $err_msg['common'] = MSG8;
        }
      } catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG8;
      }
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
      <h2 class="page-title">パスワード変更</h2>
      <form action="" method="post" class="form-width">

        <span class="area-msg <?php if (!empty($err_msg['common'])) echo 'err'; ?>"><?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?></span>

        <span class="area-msg <?php if (!empty($err_msg['old_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['old_pass'])) echo $err_msg['old_pass']; ?></span>


        <label>古いパスワード<br><span class="area-msg <?php if (!empty($err_msg['old_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['old_pass'])) echo $err_msg['old_pass']; ?></span>
          <input type="text" name="old_pass" value="<?php getFormData('old_pass'); ?>">
        </label>

        <span class="area-msg <?php if (!empty($err_msg['new_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['new_pass'])) echo $err_msg['new_pass']; ?></span>

        <label>新しいパスワード<br>
          <span class="area-msg <?php if (!empty($err_msg['new_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['new_pass'])) echo $err_msg['new_pass']; ?></span>
          <input type="password" name="new_pass" value="<?php getFormData('new_pass'); ?>">
        </label>

        <span class="area-msg <?php if (!empty($err_msg['retype_new_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['retype_new_pass'])) echo $err_msg['retype_new_pass']; ?></span>

        <label>新しいパスワード（再入力）<br>
          <span class="area-msg <?php if (!empty($err_msg['retype_new_pass'])) echo 'err'; ?>"><?php if (!empty($err_msg['retype_new_pass'])) echo $err_msg['retype_new_pass']; ?></span>
          <input type="password" name="retype_new_pass" value="<?php getFormData('retype_new_pass'); ?>">
        </label>
        <input type="submit" value="送信">
        <br>
      </form>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>