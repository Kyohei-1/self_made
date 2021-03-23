<?php
require('function.php');

$siteTitle = 'マイページ';
require('head.php');

//ログイン認証
require('auth.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle);
debug('「「「「「「「「「「「「「「「「「');
debugLog();

//投稿したデータを取得
$u_id = $_SESSION['user_id'];
$u_data = getConnectData($u_id);

debug($u_data);

if (!empty($_GET)) {
  $s_word = '%' . $_GET['search-word'] . '%';
  //例外処理
  try {
    //DB接続
    $dbh = dbConnect();
    //SQL作成
    $sql = 'SELECT * FROM `dairy_data` WHERE sentence like :s_word AND user_id = :u_id';
    $data = array(
      ':u_id' => $u_id,
      ':s_word' => $s_word,
    );
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    debug('クエリ成功');
    debug($result);
  } catch (Exception $e) {
  }
}

?>




<body>
  <?php
  require('header.php');
  ?>
  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <main>
    <div class="inner-wrap">
      <h2 class="page-title">マイページ</h2>
      <?php
      require('sidebar.php');
      ?>



      <div class="main-contents"><?php $i = 0; ?>
        <?php if (empty($_GET)) {
          foreach ($u_data as $key => $value) : ?>
            <div class="wrap">
              <p class="left-block"><img width="150px" src="<?php
              if (isset($value['pic'])) {
                echo $value['pic'];
              } else {
                echo './img/logo.png';
              } ?>" alt=""></p>
              <p class="right-block">
                <p class="text-block"><?php echo $value['sentence']; ?></p>
                <p class="show-time-block"><?php echo $value['created_date']; ?></p>
              </p>
            </div>
          <?php
            $i++;
          endforeach;
        } else {
          if (!empty($_GET)) foreach ($result as $key => $value) : ?>
            <div class="wrap">
              <p class="left-block"><img width="150px" src="<?php
              if (isset($value['pic'])) {
                echo $value['pic'];
              } else {
                echo './img/logo.png';
              } ?>" alt=""></p>
              <p class="right-block">
                <p class="text-block"><?php echo $value['sentence']; ?></p>
                <p class="show-time-block"><?php echo $value['created_date']; ?></p>
              </p>
            </div>
          <?php
            $i++;
          endforeach;
          ?>
        <?php }
        ?>

      </div>



    </div>
  </main>
  <?php require('footer.php'); ?>