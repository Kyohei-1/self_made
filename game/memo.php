<!-- ログとかの設定
大元のCreatureクラス
継承したMonster,Playerクラス -->


<?php

$i = (int)(1079 / 0.75);
print($i);
//先に計算させてint型にすることで小数点以下を消せる


// function CreatePlayer()
// {
//   global $human;
//   $_SESSION['player'] =  $player;
// }


// class Player extends Creature
// {
//   function attack()
//   {
    
//   }
//   function defense()
//   {
//   }
//   function escape()
//   {
//   }
// }



// class DragonMonster extends Monster{
//   public function __construct($hp, $name, $img, $attackMin, $attackMax)
//   {
//     $this->hp = $hp;
//     $this->name = $name;
//     $this->img = $img;
//     $this->attackMin = $attackMin;
//     $this->attackMax = $attackMax;
//   }
//   function attack($targetObj)
//   {

//   }
//   function defense()
//   {
//     $qqq = 1;
//   }
//   function blessAttack()
//   {
//     $aaa = 1;
//   }
// }

// interface HistoryInterface{
//   public function set($str);
//   public function clear();
// }

// class History implements HistoryInterface{
//   public function set($str){
//     //セッションhistoryが作られていなければ作る
//     if(empty($_SESSION['history'])) $_SESSION['history'] = '';
//     //文字列をセッションhistoryへ格納
//     $_SESSION['history'] .= $str . '<br>';
//   }
//   public function clear(){
//     unset($_SESSION['history']);
//   }
// }

// $player = new Player(10000,'プレーヤー',200,800);

// debug(print_r($player, true));

// //モンスター格納用の配列を用意
// $monsters = array();
// //monsterインスタンスを生成
// $monsters[] = new DragonMonster(2500, 'ファイアードラゴン', './img/fireDragon.png', mt_rand(75, 100), mt_rand(200, 300));
// $monsters[] = new DragonMonster(3000, 'ポイズンドラゴン', './img/poisonDragon.png', mt_rand(150, 200), mt_rand(200, 250));
// $monsters[] = new Monster(1000, 'ビースト', './img/beast.png', mt_rand(80, 150), mt_rand(200, 300));
// $monsters[] = new Monster(4000, 'デーモン', './img/darkDemon_1.png', mt_rand(200, 220), mt_rand(300, 500));

// debug(print_r($monsters, true));

// $monster = $monsters[mt_rand(0, 3)];

// debug(print_r($monster, true));

?>