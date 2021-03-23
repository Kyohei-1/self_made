<?php

require('function.php');

$siteTitle = 'ログアウトページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();

debug('ログアウトします');

debug('セッションの中身：'.print_r($_SESSION,true));

//SESSIONを削除（ログアウト）します
session_destroy();

debug('セッションの中身：' . print_r($_SESSION, true));


debug('ログインページへ遷移します');
header("Location:login.php");




?>



