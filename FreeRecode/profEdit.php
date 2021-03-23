<?php

//1.DBからユーザー情報を取得
//2.POSTされているかチェック
// (フォームにはDBのデータを表示しているので、
// DBのデータのまま変更していなくてもPOSTされる)
//3.DBの情報とPOSTされた情報を比べて違いがあれば、バリデーションチェック
//4.DB接続
//5.レコード更新
//6.マイページへ遷移

//編集画面を最初開いたときはDBのデータを表示する
//POSTしてエラーになった場合、POSTの情報を表示する


require('function.php');

$siteTitle = 'プロフィール編集ページ';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();

//ログイン認証
require('auth.php');

$u_id = $_SESSION['user_id'];

//ユーザー情報を取得
$u_data = getUser($u_id);
debug('ユーザーデータ：' . print_r($u_data, true));
// debug($u_data[0]['email']);


if ($_POST) {
  debug('POST情報：' . print_r($_POST, true));

  $email = $_POST['email'];
  $name = $_POST['name'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];

  if (isset($_POST['gender'])) {
    debug('ラジオボタンは正常に作動しています');
  }

  debug('バリデーションを開始します');

  // var_dump($u_data);

  //DBの登録内容とPOSTされた値が違っていればバリデーションする
  if ($u_data[0]['email'] !== $email) {
    validNotInput($email, 'email');
    validEmail($email, 'email');
    validMinLen($email, 'email');
    validMaxLen($email, 'email');
    validEmailDuplicate($email);
  }

  if ($u_data[0]['username'] !== $name) {
    validNotInput($name, 'name');
    // validMinLen($name, 'name');
    validMaxLen($name, 'name');
  }

  if ($u_data[0]['age'] !== $age) {
    validNotInput($age, 'age');
    validMaxLen($age, 'age');
    validHalf($age, 'age');
  }

  if ($u_data[0]['gender'] !== $gender) {
    validNotInput($gender, 'gender');
  }

  if (empty($err_msg)) {

    debug('バリデーションOK');

    //例外処理
    try {
      $dbh = dbConnect();
      $sql = 'UPDATE users SET email = :email, username = :username, age = :age, gender = :gender WHERE id = :u_id';
      $data = array(
        ':email' => $email,
        ':username' => $name,
        ':age' => $age,
        ':gender' => $gender,
        ':u_id' => $u_id
      );

      $stmt = queryPost($dbh, $sql, $data);

      if ($stmt) {
        debug('クエリ成功');
        header("Location:mypage.php");
      } else {
        debug('クエリに失敗しました');
        $err_msg['common'] = MSG8;
      }
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG8;
    }
  }
}

?>

<body>
  <?php
  require('header.php');
  ?>
  <main>
    <div class="inner-wrap">
      <h2 class="page-title">プロフィール編集画面</h2>
      <form action="" method="post" class="form-width">

        <span class="area-msg <?php if (!empty($err_msg['common'])) echo 'err'; ?>"><?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?></span>



        <label>Eメール<br><span class="area-msg <?php if (!empty($err_msg['email'])) echo 'err'; ?>"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
          <input type="text" name="email" value="<?php if (!empty($u_data[0]['email'])) echo $u_data[0]['email']; ?>">
        </label>



        <label>ユーザー名<br><span class="area-msg <?php if (!empty($err_msg['name'])) echo 'err'; ?>"><?php if (!empty($err_msg['name'])) echo $err_msg['name']; ?></span>
          <input type="text" name="name" value="<?php echo getFormData('username'); ?>">
        </label>

        <label>年齢<br><span class="area-msg <?php if (!empty($err_msg['age'])) echo 'err'; ?>"><?php if (!empty($err_msg['age'])) echo $err_msg['age']; ?></span>
          <input type="text" name="age" value="<?php echo getFormData('age'); ?>">
        </label>

        <label>性別<br><span class="area-msg <?php if (!empty($err_msg['gender'])) echo 'err'; ?>"><?php if (!empty($err_msg['gender'])) echo $err_msg['gender']; ?></span>
          <input type="radio" name="gender" value="male" <?php if ($u_data[0]['gender'] === 'male') echo 'checked'; ?>>男性
          <input type="radio" name="gender" value="female" <?php if ($u_data[0]['gender'] === 'female') echo 'checked'; ?>>女性
          <input type="radio" name="gender" value="other" <?php if ($u_data[0]['gender'] === 'other') echo 'checked'; ?>>その他

        </label>

        <input type="submit" value="送信">
        <br>
      </form>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>