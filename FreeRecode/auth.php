<?php 
  if(!empty($_SESSION['login_date'])){
    //SESSIONがある場合
    debug('ログイン済みユーザーです');

    //現在日時が最終ログイン日時＋有効期限を超えていた場合
    if($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
      
      //セッションを削除する
      session_destroy();

      //ログインページへ遷移
      header("Location:login.php");

    }else{
      debug('ログイン有効期限以内です');
      //最終ログイン日時を現在日時に更新
      $_SESSION['login_date'] = time();

      //現在実行されているスクリプトファイルがlogin.phpの場合
      //$_SERVER['PHP_SELF']はドメインからのパスを返すため、
      //FreeRecode/mypage.phpが返ってくる
      //basename関数を使うことでファイル名だけ取り出せる
      //$_SERVER['PHP_SELF'] -> basename -> 'login.php'
      if(basename($_SERVER['PHP_SELF']) === 'login.php'){
        debug('マイページへ遷移します');
        header("Location:mypage.php");
      }


    }
  }else{
    //SESSIONがない場合
    debug('未ログインユーザーです。');

    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
      header("Location:login.php");
    }
  }
?>