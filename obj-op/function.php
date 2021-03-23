<?php

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

//Sessionの準備と有効期限を伸ばす
//セッションファイルの置き場を変更する(/var/tmp以下に置くと30日は削除されない)
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ100分の1の確率で削除）
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
// ブラウザを閉じても削除されないようにCookie自体の有効期限を伸ばす
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

// define("MY_MONEY",10000);
// define("MY_KABU",0);


$err_msg = array();

//エラーメッセージ
define('MSG1', '入力必須です');
define('MSG2', '半角数字で入力してください');
define('MSG3', '整数値で入力してください');
define('MSG4', '1以上の値を入力してください');
define('MSG5', '9990カブ以上の購入は出来ません');
define('MSG6', '現在の所持金では入力された数のご購入は出来ません');
define('MSG7', '現在の所持カブ数を超過しています');

$debug_flg = true;

function debug($str)
{
  global $debug_flg;
  if ($debug_flg) {
    error_log('デバッグ：' . print_r($str, true));
  }
}

//未入力チェック
function validNotInput($str, $key)
{
  if ($str === '') {
    global $err_msg;
    $err_msg[$key] = MSG1;
  }
}

//半角数字チェック
function validHalfNumber($str, $key)
{
  if (!is_numeric($str)) {
    global $err_msg;
    $err_msg[$key] = MSG2;
  }
}

//整数値チェック
function validInteger($str, $key)
{
  $str = (int) $str;
  if (!is_int($str)) {
    global $err_msg;
    $err_msg[$key] = MSG3;
  }
}

//最小数チェック
function validMin($str, $key)
{
  if ($str < 1) {
    $err_msg[$key] = MSG4;
  }
}

//最大数チェック
function validMax($str, $key)
{
  if ($str >= 9990) {
    $err_msg[$key] = MSG5;
  }
}

function isSale($count, $key)
{
  if ($_SESSION['myMoney'] <= $_SESSION['saleValue'] * (int) $count) {
    global $err_msg;
    $err_msg[$key] = MSG6;
  }
}

function isBuy($count,$key){
  if($_SESSION['myKabu'] < (int)$count){
    global $err_msg;
    $err_msg[$key] = MSG7;
  }
}

//カブ価のレートパターンを入れる配列


//プレイヤークラス
class Player
{
  public $money;
  public $kabuCount;

  public function __construct($money, $kabuCount)
  {
    $this->money = $money;
    $this->kabuCount = $kabuCount;
  }

  public function getKabuCount()
  {
    return $this->kabuCount;
  }

  public function getMoney()
  {
    return $this->money;
  }
}

$player = new Player(10000, 0);

//まめつぶクラス
class MameTubu
{
  public $buyValue;
  public $img;

  public function __construct($buyValue, $img)
  {
    $this->buyValue = $buyValue;
    $this->img = $img;
  }

  //カブを買い取る
  public function buy($count)
  {
    $_SESSION['myKabu'] -= $count;
    $_SESSION['myMoney'] += $count * $_SESSION['buyValue'];
  }

  //セッター
  public function setBuyValue($num)
  {
    $this->buyValue = filter_var($num, FILTER_VALIDATE_INT);
  }

  //ゲッター
  public function getBuyValue()
  {
    return $this->buyValue;
  }

  public function getImg()
  {
    return $this->img;
  }
}

//インスタンス生成
$mametubu = new MameTubu(
  mt_rand(50, 150),
  './img/mametubu.png'
);

// debug('まめつぶクラスの中身'.$mametubu);

//ウリクラス
class Uri
{
  public $saleValue;
  public $img;

  public function __construct($saleValue, $img)
  {
    $this->saleValue = $saleValue;
    $this->img = $img;
  }

  //カブを売る
  public function sale($num)
  {
    $_SESSION['myKabu'] += $num;
    $_SESSION['myMoney'] -= $num * $_SESSION['saleValue'];
  }

  //セッター
  public function setSaleValue($num)
  {
    $this->saleValue = filter_var($num, FILTER_VALIDATE_INT);
  }

  //ゲッター
  public function getSaleValue()
  {
    return $this->saleValue;
  }

  public function getImg()
  {
    return $this->img;
  }
}

$uri = new Uri(mt_rand(75, 125), './img/uri.png');

// debug('うりクラスの中身'.$uri);


//時間クラス
class Time
{
  //現在時刻
  public $nowTime;
  //現在の曜日
  public $today;

  public function __construct($nowTime, $today)
  {
    $this->nowTime = $nowTime;
    $this->today = $today;
  }

  //時間を進める
  public function timeAdvance()
  {
    if(isset($_SESSION['timeCount'])){
      if($_SESSION['timeCount'] % 2 === 0){
        $_SESSION['nowTime'] = '0:00';
      }else{
        $_SESSION['nowTime'] = '12:00';
      }
      $_SESSION['timeCount'] += 1;
    }

    if(isset($_SESSION['todayCount'])){
      switch ($_SESSION['todayCount']) {
        case 0:
          $_SESSION['today'] = '日';
          break;
        case 1:
          $_SESSION['today'] = '日';
          break;
        case 2:
          $_SESSION['today'] = '月';
          break;
        case 3:
          $_SESSION['today'] = '月';
          break;
        case 4:
          $_SESSION['today'] = '火';
          break;
        case 5:
          $_SESSION['today'] = '火';
          break;
        case 6:
          $_SESSION['today'] = '水';
          break;
        case 7:
          $_SESSION['today'] = '水';
          break;
        case 8:
          $_SESSION['today'] = '木';
          break;
        case 9:
          $_SESSION['today'] = '木';
          break;
        case 10:
          $_SESSION['today'] = '金';
          break;
        case 11:
          $_SESSION['today'] = '金';
          break;
        case 12:
          $_SESSION['today'] = '土';
          break;
        case 13:
          $_SESSION['today'] = '土';
          break;
      }
      $_SESSION['todayCount'] += 1;
    }
  }

  //ゲッター
  public function getNowTime()
  {
    return $this->nowTime;
  }

  public function getToday()
  {
    return $this->today;
  }
}

$time = new Time('0:00', '日');

// debug($time);
