<?php

require('function.php');


$siteTitle = '書く';
require('head.php');

debug('「「「「「「「「「「「「「「「「「');
debug($siteTitle . 'ページ');
debug('「「「「「「「「「「「「「「「「「');
debugLog();


//ログイン認証
require('auth.php');

$u_id = $_SESSION['user_id'];
$tag = getCategory();
$column = getCategoryColumn();
debug($tag);
debug($column);

$result = getData($u_id);


//POST送信されているか
if (!empty($_POST)) {
  debug('FILE情報'.$_FILES);

  $select = $_POST['tag'];
  $sentence = $_POST['sentence'];
  //画像をアップロードし、パスを格納
  $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : '';
  //画像をPOSTしていない→（登録していないが、DBに既に登録されている場合、DBのパスを入れる（POSTには反映されないので））
  // $pic1 = (empty($pic1) && !empty($result)) ? $result



  //各種バリデーション
  validNotInput($sentence, 'sentence');
  validSentenceLenMax($sentence, 'sentence');

  debug('バリデーションOK');

  if (empty($err_msg)) {

    try {
      //DB接続
      $dbh = dbConnect();
      //SQL文準備
      $sql = 'INSERT INTO `dairy_data` (sentence,user_id,pic,created_date) VALUES ( :sentence, :u_id, :pic, :create_date)';

      $data = array(
        ':sentence' => $sentence,
        ':u_id' => $u_id,
        ':pic' => $pic,
        ':create_date' => date("Y-m-d H:i:s")
      );

      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      if ($stmt) {
        debug('クエリ成功');



        header("Location:mypage.php");
      } else {
      }
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
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
      <h2 class="page-title">書く</h2>
      <form action="" method="post" class="form-width" enctype="multipart/form-data">
        <h3 class="section-title">文章<br>
          <select name="tag">
            <?php
            for ($i = 0; $i < $column; $i++) { ?>
              <option value="<?php if (!empty($tag)) echo $i + 1; ?>"><?php if (!empty($tag)) echo $tag[$i]['name']; ?></option>
            <?php } ?>
          </select>


          <textarea type="text" name="sentence" id="js-count"></textarea>


          <div class="counter-text"><span id="js-count-view">0</span>/max</div>

          <div class="clear"></div>


          <div style="overflow:hidden;">
              <div class="imgDrop-container">
                画像1
                <label class="area-drop <?php if(!empty($err_msg['pic'])) echo 'err'; ?>">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="pic" class="input-file">
                  <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
                  上に画像をドラッグ&ドロップ
                </label>
                <div class="area-msg">
                  <?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?>
                </div>
              </div>
          </div>
          
          <input type="submit" value="送信">
      </form>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>