<?php

require('function.php');

$siteTitle = '退会ページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();

$u_id = $_SESSION['user_id'];

if (!empty($_POST)) {
  //退会
  debug('退会します');

  //投稿した日記のデータを削除します
  debug('投稿した自分の日記のデータを削除します');

  //例外処理
  try{
    //dbh取ってきて
    $dbh = dbConnect();
    //SQL用意
    //user_idがログインユーザーの投稿を論理削除
    $sql1 = 'UPDATE `dairy_data` SET delete_flg = 1 WHERE user_id = :u_id';
    $sql2 = 'UPDATE `users` SET delete_flg = 1 WHERE id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt1 = queryPost($dbh,$sql1,$data);

    if($stmt1){
      debug('1つ目のSQL成功');
    }
    $data = array(':u_id' => $u_id);
    $stmt2 = queryPost($dbh, $sql2, $data);

    if($stmt2){
    debug('2つ目のSQL成功');
    }

    if($stmt1 && $stmt2){
      debug('クエリ成功');

      // session_unset();
      // debug('session_unset後：'.$_SESSION);

      // $_SESSION =array();
      // debug('$_SESSIONに空の配列入れた後：' . $_SESSION);

      session_destroy();
      debug('session_destroy後'.$_SESSION);

      debug('登録ページへ遷移します');


      header("Location:signup.php");
    }else{

    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
    $err_msg['common'] = MSG8;
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
        <input name="withdraw" type="submit" value="退会する">
      </form>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>