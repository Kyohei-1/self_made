$(function () {
  //画像ライブプレビュー
  var $dropArea = $(".area-drop"); //labelのDOMを取得
  var $fileInput = $(".input-file"); //input[file]のDOMを取得

  //labelエリアがクリックされたら
  $dropArea.on("click", function (e) {
    //親要素へのバブリングを阻止する
    //この場合だと、inputをクリックするとlabelもクリックされたことになる
    //この現象をバブリングという
    // e.stopPropagation(); //イベントのバブリングを停止する
    // e.preventDefault(); //ブラウザの持っているもともとの処理を抑制する
    $(this).css("border", "3px #ccc dashed");
    //点線の枠線をつける
  });
  $dropArea.on("dragleave", function (e) {
    // ドラッグして離れたときにイベント発火
    // e.stopPropagation();
    // e.preventDefault();
    $(this).css("border", "none");
    // 枠線を消す
  });
  $fileInput.on("change", function (e) {
    $dropArea.css("border", "none");
    var file = this.files[0]; //files配列にファイルが入っている
    var $img = $(this).siblings(".prev-img");
    //jqueryのsiblingsメソッドで兄弟のimgを取得
    var fileReader = new FileReader();
    //ファイルを読み込むFileReaderオブジェクト

    //読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
    fileReader.onload = function (event) {
      //読み込んだデータをimgに設定
      $img.attr("src", event.target.result).show();
    };

    //画像読み込み
    fileReader.readAsDataURL(file);
  });
});
