<?php

require('function.php');

debug('ゲーム開始画面');

if (!empty($_POST)) {

  //何曜日かのカウント用
  $_SESSION['todayCount'] = 0;
  //時間のカウント用
  $_SESSION['timeCount'] = 0;

  // $_SESSION['myMoney'] = $player->getMoney();

  // $_SESSION['nowTime'] = $time->getNowTime();
  // $_SESSION['today'] = $time->getToday();
  // $_SESSION['myKabu'] = $player->kabuCount;
  // $_SESSION['myMoney'] = $player->money;
  // $_SESSION['saleValue'] = $uri->getSaleValue();

  // debug($_SESSION['nowTime']);
  // debug($_SESSION['today']);
  // debug($_SESSION['myKabu']);
  // debug($_SESSION['myMoney']);
  // debug($_SESSION['saleValue']);



  //個数を表す
  $quantity = (int)$_POST['quantity'];

  debug($quantity . '個、欲しいです');

  //バリデーション
  //未入力チェック
  validNotInput($quantity, 'quantity');
  debug('未入力チェック通過');

  //半角数字チェック
  validHalfNumber($quantity, 'quantity');
  debug('半角数字チェック通過');

  //整数値チェック
  validInteger($quantity, 'quantity');
  debug('整数値チェック通過');

  //最小数チェック
  validMin($quantity, 'quantity');
  debug('最小数チェック通過');

  //最大数チェック
  validMax($quantity, 'quantity');
  debug('最大数チェック通過');

  //現在の所持金で買えるかのチェック
  isSale($quantity, 'quantity');
  debug('現在の所持金で買えるかのチェック通過');

  if (empty($err_msg)) {
    debug('バリデーション通過');

    //購入数を追加
    //購入数分の金額を所持金から引く
    $uri->sale($quantity);

    debug('ゲーム画面へ遷移します');

    $time->timeAdvance();

    header("Location:game.php");
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カブ取引 | スタート画面</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="main-wrapper">
    <header>
      <ul>
        <li><span class="wood">時刻</span> <?php echo $_SESSION['nowTime'] = $time->getNowTime(); ?> <?php echo $_SESSION['today'] = $time->getToday(); ?> 曜日</li>
        <li><span class="wood">販売金額</span> <?php echo $_SESSION['saleValue'] = $uri->getSaleValue();?></li>
        <li><span class="wood">所持カブ数</span> <?php echo $_SESSION['myKabu'] = $player->getKabuCount(); ?> </li>
        <li><span class="wood">所持金</span> <?php echo $_SESSION['myMoney'] = $player->getMoney(); ?> </li>
      </ul>
    </header>
    <div class="main-container">
      <div class="half-block img">
        <img src="<?php if (!empty($uri)) echo $uri->getImg(); ?>" alt="" width="50%">
      </div>
      <div class="half-block msg">
        <pre>
          これはカブを売買するゲームです。

          プレイヤーは、日曜の午前中にカブを購入し
          好きなタイミングで売却する事が出来ます。

          カブの購入はゲーム開始時のみ可能で、
          10カブ単位から購入でき、最大で9990カブ購入できます。
          
          所持金は10000円のスタートです。

          日曜日を除く6日間の間、0:00と12:00にカブ価は変動します。

          時間を進めるボタンを利用すると、時間が12時間進みます。

          再び日曜日が来ると、残ったカブは腐ってしまい
          ゲーム終了となります。

          <!-- ゲーム終了時、ユーザー登録をすることでデータを引き継げます。 -->
        </pre>

      </div>
    </div>

    <div class="btn-wrapper">
      <form action="" method="post">
        <input type="text" name="quantity" placeholder="いくつ買いますか？">
        <input class="btn btn-submit" type="submit" value="購入する">
      </form>
    </div>
  </div>
</body>

</html>