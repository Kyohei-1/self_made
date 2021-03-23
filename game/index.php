<?php

//ログを取る
ini_set('log_errors', 'on');
//出力先
ini_set('error_log', 'php.log');
//セッションを使う
session_start();

///////////////////////////////////////////////
//クラス
///////////////////////////////////////////////

// モンスターとプレーヤーで共通するプロパティやメソッドをまとめた抽象クラス
abstract class Creature
{
  protected $hp; //体力
  protected $name; //名前
  protected $attackMin; //最小の攻撃力
  protected $attackMax; //最大の攻撃力

  public function __construct($hp, $name, $attackMin, $attackMax)
  {
    //値を入れるためのコンストラクタ

    $this->hp = $hp;
    $this->name = $name;
    $this->attackMin = $attackMin;
    $this->attackMax = $attackMax;
  }

  //攻撃する
  public function attack($targetObj)
  {
    debug('攻撃が選択されました');
    //与えるダメージをランダムにセットする
    $attackPoint = mt_rand($this->attackMin, $this->attackMax);
    if (!mt_rand(0, 9)) {
      //10分の1の確率でクリティカル
      $attackPoint = (int)($attackPoint * 1.5);
    }
    History::set($this->name.'の攻撃！');
    // $monsterにダメージを与える
    $targetObj->setHp($targetObj->getHp() - $attackPoint);
    History::set($attackPoint.'ダメージを与えた！');
    }
  //防御する
  public function defense($targetObj)
  {
    debug('防御を選択しました');
    //与えられるダメージをセットする
    $attackPoint = mt_rand($targetObj->attackMin, $targetObj->attackMax);
    //防御して75%ダメージカット
    $attackPoint = (int) ($attackPoint / 0.75);
    //敵から攻撃を受ける
    $this->setHp($this->getHp() - $attackPoint);
    History::set($this->name.'は'.$attackPoint.'ダメージを受けた');
  }

  //セッター
  public function setHp($hp)
  {
    $this->hp = filter_var($hp, FILTER_VALIDATE_INT);
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function setAttackMin($attackMin)
  {
    //int型かどうか（整数かどうか判定）
    $this->attackMin = filter_var($attackMin, FILTER_VALIDATE_INT);
  }
  public function setAttackMax($attackMax)
  {
    $this->attackMax = filter_var($attackMax, FILTER_VALIDATE_INT);
  }

  // ゲッター
  public function getHp()
  {
    return $this->hp;
  }
  public function getName()
  {
    return $this->name;
  }
} //abstract class Creature

//モンスタークラス
class Monster extends Creature
{
  //画像用のプロパティを追加（継承予定なのでprotected）
  protected $img;
  public function __construct($hp, $name, $img, $attackMin, $attackMax)
  {
    parent::__construct($hp, $name, $attackMin, $attackMax);
    $this->img = $img;
  }
  public function getImg()
  {
    return $this->img;
  }
}

class DragonMonster extends Monster
{
  public function __construct($hp, $name, $img, $attackMin, $attackMax)
  {
    parent::__construct($hp, $name, $img, $attackMin, $attackMax);
  }
  public function attack($targetObj)
  {
    //与えるダメージをランダムにセットする
    $attackPoint = mt_rand($this->attackMin, $this->attackMax);
    if (!mt_rand(0, 9)) {
      //10分の1の確率でクリティカル
      $attackPoint = (int) ($attackPoint * 1.5);
    }
    //10分の1でブレス攻撃
    if (!mt_rand(0, 9)) {
      $attackPoint = (int) ($attackPoint * 1.25);
      History::set('ブレス攻撃だ！');
    }
    debug('攻撃が選択されました');
    //与えるダメージをランダムにセットする
    $attackPoint = mt_rand($this->attackMin, $this->attackMax);
    History::set($this->name . 'の攻撃！');
    if (!mt_rand(0, 9)) {
      //10分の1の確率でクリティカル
      $attackPoint = (int) ($attackPoint * 1.5);
    }
    // $monsterにダメージを与える
    $targetObj->setHp($targetObj->getHp() - $attackPoint);
    History::set($attackPoint . 'ダメージを与えた！'); 
  }
}

class Player extends Creature
{
  //回避する
  public function escape()
  {
    //新しいモンスターを生成する
    createMonster();
  }
}

interface HistoryInterface{
  static function set($str);
  static function clear();
}

//履歴管理クラス
class History implements HistoryInterface
{
  static function set($str)
  {
    //セッションhistoryがなければ作る
    if (empty($_SESSION['history'])) $_SESSION['history'] = '';
    //文字列を$_SESSION['history']に入れる
    $_SESSION['history'] .= $str . '<br>';
  }
  static function clear()
  {
    //セッションを空にする
    unset($_SESSION['history']);
  }
}

//モンスターを格納する配列
$monsters = array();

//インスタンス生成
$player = new Player(10000, 'プレイヤー', mt_rand(150, 300), mt_rand(500, 750));

$monsters[] = new Monster(1500, 'デーモン', './img/darkDaemon_1.png', mt_rand(200, 400), mt_rand(500, 750));
$monsters[] = new Monster(800, 'ビースト', './img/beast.png', mt_rand(150, 300), mt_rand(400, 600));
$monsters[] = new Monster(500, 'ゾンビ', './img/zombie_1.png', mt_rand(50, 100), mt_rand(200, 400));
$monsters[] = new Monster(250, 'ネコ', './img/whiteCat.png', mt_rand(1, 5), mt_rand(10, 15));
$monsters[] = new DragonMonster(2000,'炎龍','./img/fireDragon.png',300,750);
$monsters[] = new DragonMonster(1000,'毒龍','./img/poisonDragon.png',200,700);


// $monsters[] = new Monster(1000, 'ファイアードラゴン', './img/fireDragon.png', mt_rand(50, 200), mt_rand(300, 500));


function debug($str)
{
  $debugFlg = true;
  if ($debugFlg) {
    error_log('デバッグ：' . $str);
  }
}

function debugLogStart()
{
  // debug('セッションID：' . session_id());
  // debug('SESSION:' . print_r($_SESSION, true));
  // debug('POST情報:' . print_r($_POST, true));
  $methods = get_class_methods("Player");
  debug('Player' . print_r($methods, true));
  $methods = get_class_methods("Monster");
  debug('Monster' . print_r($methods, true));
}

function createMonster()
{
  global $monsters;
  $monster =  $monsters[mt_rand(0, 5)];
  $_SESSION['monster'] =  $monster;
  debug(print_r($_SESSION['monster'], true));
}

function CreatePlayer()
{
  //globalの$playerを使用
  global $player;
  $_SESSION['player'] = $player;
  debug(print_r($_SESSION['player'],true));
}

function init()
{
  //履歴や倒したモンスターの数を初期化
  $_SESSION['knockDownCount'] = 0;
  createPlayer();
  createMonster();
  History::set('ゲームスタート');

  debug(print_r($_SESSION['history'], true));
  if (is_array($_SESSION['history'])) {
    error_log(count($_SESSION['history']));
  }
}

function GameOver()
{
  History::clear();
  $_SESSION['start'] = '';
  $_SESSION = array();
}

?>


<?php

if ($_POST) {

  debugLogStart();

  //ボタンごとに動作を分ける
  $attackFlg = (!empty($_POST['attack'])) ? true : false;
  $defenseFlg = (!empty($_POST['defense'])) ? true : false;
  $escapeFlg = (!empty($_POST['escape'])) ? true : false;
  if(empty($_SESSION['start'])){
  $_SESSION['start'] = (!empty($_POST['start'])) ? true : false;
  }
  error_log('POSTされました');



  if ($attackFlg) {
    // GameOver();
    //攻撃する
    //自分が攻撃する
    $_SESSION['player']->attack($_SESSION['monster']);
    //相手が自分に攻撃する
    $_SESSION['monster']->attack($_SESSION['player']);
  } elseif ($defenseFlg) {
    //防御する
    $_SESSION['player']->defense($_SESSION['monster']);
  } elseif ($escapeFlg) {
    //逃げる
    $_SESSION['player']->escape();
  }

  $msgCount = mb_substr_count($_SESSION['history'],'<br>');
  debug($msgCount);
  if($msgCount > 20){
    History::clear();
  }

  //プレーヤーの体力が0以下になるとゲームオーバー
  if ($_SESSION['player']->getHp() <= 0) {
    GameOver();
  } elseif ($_SESSION['monster']->getHp() <= 0) {
    createMonster();
    $_SESSION['knockDownCount']++;
  }
  $_POST = array();
}


?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ドラ○エもどき</title>
  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
      <?php if(empty($_SESSION['start']) || empty($_SESSION)){
        init(); 
        ?>
        <div class="main-wrap">
        <h2 style="color:#FFFFFF;">GAME START ?</h2>
        <form method="post">
          <input type="submit" class="startBtn" name="start" value="▶ゲームスタート">
      </form>
        </div>
      <?php }else{ ?>

  <div class="main-wrap wrap">
    <div class="left-block">
      <p>Name <?php if(!empty($_SESSION['monster'])) echo $_SESSION['monster']->getName(); ?></p>
      <p>HP <?php if (!empty($_SESSION['monster'])) echo $_SESSION['monster']->getHp(); ?></p>
      <div class="img-area">
        <img src="<?php echo $_SESSION['monster']->getImg(); ?>" alt="" height="275px" width="100%">
      </div>
      <div class="btn-area">
        <form action="" method="post">
          <input name="attack" class="btn-submit" type="submit" value="▶攻撃する">
          <input name="defense" class="btn-submit" type="submit" value="▶防御する">
          <input name="escape" class="btn-submit" type="submit" value="▶逃げる">
        </form>
      </div>
    </div>
    <div class="right-block">
      <p>Name <?php if(!empty($_SESSION['player'])) echo $_SESSION['player']->getName(); ?></p>
      <p>HP <?php if (!empty($_SESSION['player'])) echo $_SESSION['player']->getHp(); ?></p>
      <p>倒したモンスターの数<?php if (!empty($_SESSION['knockDownCount'])) echo $_SESSION['knockDownCount']; ?></p>
      <div class="log-area">
        <p>
          <?php if (!empty($_SESSION['history'])) echo $_SESSION['history']; ?>
        </p>

      </div>
    </div>
  </div>

        <?php } ?>
</body>



</html>