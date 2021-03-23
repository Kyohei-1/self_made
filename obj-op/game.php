<?php

require('function.php');

debug('ゲーム画面');

if($_SESSION['todayCount'] === 13 && 
$_SESSION['timeCount'] === 13){
  debug('ゲーム終了画面に遷移します');
  header("Location:result.php");
  exit();

}

if (!empty($_POST)) {

  $saleFlg = (!empty($_POST['sale'])) ? true : false;
  $nextFlg = (!empty($_POST['next'])) ? true : false;
  $restartFlg = (!empty($_POST['restart'])) ? true : false;

  //カブを売る場合
  if(isset($_POST['count']) && $saleFlg){
    //個数を表す
    $count = (int)$_POST['count'];

    debug($count . '個、売りたいです');

    //バリデーション
    //未入力チェック
    validNotInput($count, 'count');
    debug('未入力チェック通過');

    //半角数字チェック
    validHalfNumber($count, 'count');
    debug('半角数字チェック通過');

    //整数値チェック
    validInteger($count, 'count');
    debug('整数値チェック通過');

    //最小数チェック
    validMin($count, 'count');
    debug('最小数チェック通過');

    //最大数チェック
    validMax($count, 'count');
    debug('最大数チェック通過');

    // //現在の所持金で買えるかのチェック
    // isSale($count, 'count');
    // debug('現在の所持金で買えるかのチェック通過');

    //現在の所有カブ数で売れるかのチェック
    isBuy($count,'count');
    debug('現在の所有カブ数で売れるかのチェック通過');

    if (empty($err_msg)) {
      debug('バリデーション通過');

      //売却数を引く
      //購入数分の金額を所持金から引く
      $mametubu->buy($count);

      debug('ゲーム画面へ遷移します');

      header("Location:game.php");
      exit();
    }
  }else{
    //ゲームリスタートの場合
    if($restartFlg){
      $_SESSION = array();
      header("Location:index.php");
    }
    if($nextFlg){
      $time->timeAdvance();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カブ取引 | ゲーム画面</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="main-wrapper">
    <header>
      <ul>
        <li><span class="wood">時刻</span> <?php echo $_SESSION['nowTime']; ?> <?php echo $_SESSION['today']; ?> </li>
        <li><span class="wood">買取金額</span> <?php echo $_SESSION['buyValue'] = $mametubu->getBuyValue(); ?></li>

        <li><span class="wood">所持カブ数</span> <?php echo $_SESSION['myKabu']; ?> </li>
        <li><span class="wood">所持金</span> <?php echo $_SESSION['myMoney']; ?> </li>
      </ul>
    </header>
    <div class="main-container">
      <div class="half-block img">
        <img src="<?php echo $mametubu->getImg(); ?>" alt="" width="80%">
      </div>
      <div class="half-block msg">
        <pre>
          <?php
          echo ($_SESSION['nowTime'] . '<br>');
          echo ($_SESSION['today'] . '<br>');
          echo ($_SESSION['buyValue'] . '<br>');
          echo ($_SESSION['myKabu'] . '<br>');
          echo ($_SESSION['myMoney'] . '<br>');

          ?>
        </pre>
      </div>
    </div>

    <div class="btn-wrapper">

      <form action="" method="post">
        <input class="" type="text" name="count" placeholder="いくつ売りますか？">
        <input name="sale" class="btn btn-submit" type="submit" value="カブを売る">
        <input name="next" class="btn btn-submit" type="submit" value="時間を進める">
        <input name="restart" class="btn btn-submit" type="submit" value="リスタート">
      </form>
    </div>
  </div>
</body>

</html>