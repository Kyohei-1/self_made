<?php

//ログを取るか

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

//エラーメッセージ用の変数を用意
$err_msg = array();

//エラーメッセージ
define('MSG1', '入力必須です');
define('MSG2', '半角英数字で入力してください');
define('MSG3', 'Emailの形式で入力してください');
define('MSG4', 'ご入力されたメールアドレスは登録済みです');
define('MSG5', '255文字以内で入力してください');
define('MSG6', '6文字以上で入力してください');
define('MSG7', 'パスワードまたはパスワード再入力が違います');
define('MSG8', 'エラーが発生しました。しばらく経った後、再度ご利用下さい');
define('MSG9','2048文字以内で入力してください');
define('MSG10','3桁以内で入力ください');
define('MSG11','新しいパスワードと再入力が違う値です');
define('MSG12', '古いパスワードと同じです');
define('MSG13', '古いパスワードが違います');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '半角数字のみご利用いただけます');
define('SUC1','パスワードを変更しました');
define('SUC2','プロフィールを変更しました');
define('SUC3','メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');

//チェック用関数
function check($str){
  var_dump($str);
  // exit();
}

//=================================================
// SessionとCookie用
//=================================================
//セッションファイルの置き場を変える(var/tmp/以下に置くと30日は削除されない)
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っている物に関してのみ、100分の1の確率で削除）
ini_set('session.gc_maxlifetime',1*60*60*24*30);
//ブラウザを閉じてもCookieが削除されないようにする
ini_set('session.cookie_lifetime',1*60*60*24*30);
//セッションを使う
session_start();
//なりすましのセキュリティ対策として現在のセッションIDを新しいIDと置き換える
session_regenerate_id();


//デバッグログ
function debugLog(){
  debug('SessionID:'.session_id());
  debug('Sessionの中身：'.print_r($_SESSION,true));
  if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
  debug('ログインタイム：'.$_SESSION['login_date']);
  debug('ログイン有効期限：'.$_SESSION['login_limit']);
  }
}

//デバッグ
function debug($str)
{
  $debug_flg = true;
  if ($debug_flg) {
    error_log('デバッグ：' . print_r($str, true));
  }
}

//=================================================
// バリデーション
//=================================================

function validLength($str,$key,$MaxLen = 8){
  if(mb_strlen($str) !== $MaxLen){
    global $err_msg;
    $err_msg[$key] =$MaxLen . MSG14;
  }
}

//新しいパスワードと再入力が同値かどうかチェック
//同値ではない場合→エラー
//validPassMatchとは、エラーメッセージが異なるので新設
function validSameValue($str1,$str2,$key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG11;
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

//半角英数字チェック
function validHalf($str, $key)
{
  if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG2;
  }
}

//Email形式チェック
function validEmail($str, $key)
{
  if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG3;
  }
}

//Email重複チェック
function validEmailDuplicate($email)
{
  global $err_msg;
  //例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);

    debug($data);

    $stmt = queryPost($dbh, $sql, $data);

    debug('$stmtの中身：'.print_r($stmt,true));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    debug('$result'.print_r($result,true));

    if (!empty(($result['count(*)']))) {
      $err_msg['email'] = MSG4;
    }
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
    $err_msg['common'] = MSG8;
  }
}

//年齢のバリデーション
function validAge($str,$key,$max = 3){
  //3桁以内で0を考慮する
  if(!(isset($str) && strlen($str) < $max)){
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}

//最大文字数チェック
function validMaxLen($str, $key, $max = 255)
{
  if (mb_strlen($str) >= $max) {
    global $err_msg;
    $err_msg[$key] = MSG5;
  }
}

//最小文字数チェック
function validMinLen($str, $key, $min = 6)
{
  if (mb_strlen($str) < $min) {
    global $err_msg;
    $err_msg[$key] = MSG6;
  }
}

//パスワード同値チェック
function validPassMatch($str1, $str2, $key)
{
  if ($str1 !== $str2) {
    global $err_msg;
    $err_msg[$key] = MSG7;
  }
}

//2048文字以内
function validSentenceLenMax($str, $key, $max = 2048)
{
  global $err_msg;
  if (strlen($str) < $max) {
    debug('文字数は2048文字以内なのでOKです。');
  } else {
    $err_msg[$key] = MSG9;
  }
}

function validPass($str, $key)
{
  //半角チェック
  validHalf($str, $key);
  //最大文字数チェック
  validMaxLen($str, $key);
  //最小文字数チェック
  validMinLen($str, $key);
}

function validPassUnmatch($str1,$str2,$key){
  if($str1 === $str2){
    global $err_msg;
    $err_msg[$key] = MSG12;
  }
}

//古いパスワードとDBのパスワードをチェック
function validFormAndDBValuesSame($old_pass,$db_pass,$key){
  if($old_pass === password_verify($db_pass,PASSWORD_DEFAULT)){
    global $err_msg;
    $err_msg[$key] = MSG13;
  }
}

//=================================================
// DB関係
//=================================================

//DB接続
function dbConnect()
{
  $dsn = 'mysql:dbname=dairy;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
  );

  return $dbh = new PDO($dsn, $user, $password, $options);
}

//クエリ実行
function queryPost($dbh, $sql, $data)
{
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  if ($stmt) {
    debug('クエリに成功しました');
    debug(print_r($stmt, true));
  } else {
    debug('クエリに失敗しました');
    debug(print_r($stmt->errorInfo(), true));
  }

  return $stmt;
}

//カテゴリー取得
function getCategory()
{
  //例外処理
  try{
  //DB接続
  $dbh = dbConnect();
  $sql = 'SELECT id, name FROM category WHERE delete_flg = 0';
  $data = array();
  $stmt = queryPost($dbh,$sql,$data);
  
  if($stmt){
    return $stmt->fetchAll();
  }

  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
    $err_msg['common'] = MSG8;
  }
}

//カラム数を取得
function getCategoryColumn()
{
  //例外処理
  try {
    //DB接続
    $dbh = dbConnect();
    $sql = 'SELECT id, name FROM category WHERE delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      return $stmt->rowCount();
    }
  } catch (Exception $e) {
    error_log('エラー発生' . $e->getMessage());
    $err_msg['common'] = MSG8;
  }
}

//投稿したデータ取得
function getData($u_id){
  //例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `dairy_data` WHERE  delete_flg = 0 AND user_id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh,$sql,$data);

    return $result = $stmt->fetchAll();
    
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
    $err_msg['common'] = MSG8;
  }
}

//ユーザー情報を取得
function getUser($u_id){
  //例外処理
  try {
    //DB接続
    $dbh = dbConnect();
    //SQL用意
    $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh,$sql,$data);

    if($stmt){
      debug('クエリ成功');
      return $stmt->fetchAll();
    }else{
      debug('クエリ失敗');
      $err_msg['common'] = MSG8;
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
    $err_msg['common'] = MSG8;
  }
}

//フォーム入力保持
function getFormData($str){
  global $u_data;
  global $err_msg;
  //ユーザーデータがある場合
  if(!empty($u_data[0][$str])){
    //フォームのエラーがある場合
    if(!empty($err_msg[$str])){
      //POST送信がある場合
      if(isset($_POST[$str])){
        return $_POST[$str];
      }else{
        //無い場合（フォームにエラーが有る＝POSTされているはずなので、まずありえないが）はDBの情報を表示
        return $u_data[0][$str];
      }
    }else{//フォームのエラーが無い場合
      //POSTにデータが有り、DBの情報と違い場合（このフォームも変更していてエラーはないが、他のフォームで引っかかっている状態）
      if(isset($_POST[$str]) && $_POST[$str] !== $u_data[0][$str]){
        return $_POST[$str];
      }else{//そもそも変更していない
        return $u_data[0][$str];
      }
    }
  }else{//ユーザーデータが無い場合
    if(isset($_POST[$str])){
      return $_POST[$str];
    }
  }
}

//メール送信
function sendMail($from,$to,$subject,$comment){
  if(!empty($to) && !empty($subject) && !empty($comment)){
    //文字化けしないように設定（お決まりのパターン）
    mb_language("Japanese");//現在使っている言語を設定する
    mb_internal_encoding("UTF-8");//内部の日本語をどうエンコーディング（機械がわかる言葉へ変換）するかを設定

    //メールを送信（送信結果はTrueかFalseで返ってくる）
    $result = mb_send_mail($to,$subject,$comment,"From: ".$from);

    if($result){
      debug('メールを送信しました');
    }else{
      debug('【エラー発生】メールの送信に失敗しました');
    }
  }
}

//sessionを1回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

//認証キー生成
function makeRandKey($length = 8){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for ($i=0; $i < $length; $i++) { 
    $str .= $chars[mt_rand(0,61)];
  }
}

//画像処理
function uploadImg($file,$key){
  debug('画像アップロード開始');
  debug('FILE情報：'.print_r($file,true));

  if(isset($file['error']) && is_int($file['error'])){
    try{
      //バリデーション
      //$file['error']の値を確認。配列内にはUPLOAD_ERR_OKなどの定数が入っている
      //UPLOAD_ERR_OKなどの定数はphpでファイルアップロード時に自動的に定義される。定数には値として０や１などの数値が入っている
      switch ($file['error']) {
        case UPLOAD_ERR_OK:
          //OK
          break;
        case UPLOAD_ERR_NO_FILE:
          //ファイル未選択の場合
          throw new RuntimeException('ファイルが選択出来ません');
        case UPLOAD_ERR_INI_SIZE: //php.ini定義の最大サイズを超過した場合
        case UPLOAD_ERR_FORM_SIZE: //フォーム定義の最大サイズを超過した場合
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default:
          //その他の場合
          throw new RuntimeException('その他のエラーが発生しました');
      }

      //$file['mine']の値はブラウザ側で偽造可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」
      //「IMAGETYPE＿JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type,[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG],true)){
        //第三因数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
        throw new RuntimeException('画像形式が未対応です');
      }
      //ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
      //ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性が、
      //DBにパスを保存した場合、どっちの画像のパスなのかわからなくなる
      //image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);

      if(!move_uploaded_file($file['tmp_name'],$path)){
        //ファイル移動する
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      //保存したファイルパスのパーミッション（権限）を変更する
      chmod($path,0644);

      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：'.$path);
      return $path;

    } catch (RuntimeException $e) {

      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }

  }
}

function getConnectData($u_id)
{
  //例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `dairy_data` INNER JOIN `users` ON dairy_data.user_id = users.id WHERE users.id = :u_id AND dairy_data.delete_flg = 0 AND users.delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);

    return $result = $stmt->fetchAll();
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
    $err_msg['common'] = MSG8;
  }
}
?>