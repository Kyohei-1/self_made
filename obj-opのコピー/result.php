<?php

require('function.php');

debug('結果発表画面');

if($_POST){
  if($_POST['restart']){
    $_SESSION = array();
    debug('最初の画面に遷移します');
    header("Location:index.php");
  }
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カブ取引 | 結果画面</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="main-wrapper">
    <header>
      <ul>
        <li><span class="wood">時刻</span></li>
        <li><span class="wood">買取金額</span></li>
        <li><span class="wood">所持カブ数</span></li>
        <li><span class="wood">所持金</span></li>
      </ul>
    </header>
    <div class="main-container">
      <div class="half-block img">
        <img src="./img/reset.png" alt="" width="100%">
      </div>
      <div class="half-block msg">
        <pre>
          ゲーム終了

          所持金: <?php echo $_SESSION['myMoney']; ?>
        </pre>

      </div>
    </div>

    <div class="btn-wrapper">
      <form action="" method="post">

        <input name="restart" class="btn btn-submit" type="submit" value="リスタート">
      </form>
    </div>
  </div>
</body>

</html>