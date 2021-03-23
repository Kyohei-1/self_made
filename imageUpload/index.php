<?php
//関数や変数の読み込み
require('./function.php');

//POST送信がある場合
if (!empty($_POST)) {
  debug(print_r($_FILES, true));
  $pic = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic') : '';
  // error_log($pic);
}

?>
<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ライブプレビュー</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
    <div class="area-msg">
      <?php
      if (!empty($err_msg['common'])) echo $err_msg['common'];
      ?>
    </div>
    <div style="overflow: hidden">
      <div class="imgDrop-container">
        画像
        <label class="area-drop">
          <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
          <input type="file" name="pic1" class="input-file">
          <img src="" alt="" class="prev-img">
          ドラッグ＆ドロップ
        </label>
      </div>
    </div>
    <input type="submit" value="送信">
  </form>

  <?php
  if (!empty($_GET)) {
    debug('GETがあります');
    debug($_GET['p']);
  }
  //現在のページ
  // $currentPage = empty($_GET['p']);
  $currentPage = (!empty($_GET['p'])) ? $_GET['p'] : 1;
  debug($currentPage);


  //一番小さいページ
  $minPageNum = $currentPage - 2;
  //一番大きいページ
  $maxPageNum = $currentPage + 2;
  //全てのページ数
  $totalPage = 10;
  $pageColNum = 5;
  $totalPageNum = ceil(100 / 20);




  //総ページ数が現在のページの場合
  if ($currentPage == $totalPage && $totalPageNum >= $pageColNum) { //表示項目数より総ページ数が多い場合に限る
    // 左に4ページ表示する
    $minPageNum = $currentPage - 4;
    //右側の値を現在ページに更新
    $maxPageNum = $currentPage;
    //総ページ数が10で今のページ数が9の場合
  } elseif ($currentPage == $totalPage - 1 && $totalPageNum >= $pageColNum) {

    $minPageNum = $currentPage - 3;
    $maxPageNum = $currentPage + 1;
  } elseif ($currentPage == 2 && $totalPageNum >= $pageColNum) {
    $minPageNum = $currentPage - 1;
    $maxPageNum = $currentPage + 3;
  } elseif ($currentPage == 1 && $totalPageNum >= $pageColNum) {
    $minPageNum = $currentPage;
    $maxPageNum = $currentPage + 4;
  } elseif ($totalPageNum < $pageColNum) {
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
  } else {
    $minPageNum = $currentPage - 2;
    $maxPageNum = $currentPage + 2;
  } ?>


  <?php

  if ($currentPage > 1) {   //最小ページ数のケア
    $currentPage = $currentPage;
  ?>
  <li class="list-item"><a href="?p=<?php echo $currentPage - 1; ?>"><?php echo '&lt'; ?></a></li>
  <?php } else {
    $currentPage = 1;
  ?>

  <?php } ?>
  <?php
  for ($i = $minPageNum; $i <= $maxPageNum; $i++) {
  ?>
  <li class="list-item"><a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
  <?php
  }
  ?>

  <?php if ($currentPage >= $totalPage) { //最大ページ数のケア
    $currentPage = $totalPage;
  } else {
    $currentPage = $currentPage;
  ?>
  <li class="list-item"><a href="?p=<?php echo $currentPage + 1; ?>"><?php echo '&gt'; ?></a></li>
  <?php } ?>




  <script src="./js/jquery-3.5.1.min.js"></script>
  <script src="./js/main.js"></script>
</body>

</html>