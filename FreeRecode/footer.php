  <footer id="footer">
    <small>&copy; 2020 Free Record</small>
  </footer>
  <script src="./js/jquery-3.4.1.min.js"></script>
  <script>
    $(function() {

    //フッターを最下部に固定
    //footerのDOMを取得
    var $ftr = $('#footer');
    if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
      $ftr.attr({
        'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
      });
    }

    //テキストエリアカウント
    var $countUp = $('#js-count'),
      $countView = $('#js-count-view');
    $countUp.on('keyup', function(e) {
      $countView.html($(this).val().length);
    });

    //パスワードの表示
    var $showBtn = $('#show-btn'),
      $showArea = $('#show-area');
    $('#show-btn').on('click', function() {
      if ($showBtn.checked) {
        $showArea.attr('type', 'text');
      } else {
        $showArea.attr('type', 'password');
      }
    });


    //メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if (msg.replace(/^[\s　]+|[\s ]+$/g, "").length) {
      $jsShowMsg.slideToggle('slow');
      setTimeout(function() {
        $jsShowMsg.slideToggle('slow');
      }, 5000);
    }

    //画像ライブプレビュー
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');

    $dropArea.on('click', function(e) {
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
    });

    $dropArea.on('dragleave', function(e) {
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });

    $fileInput.on('change', function(e) {
      $dropArea.css('border', 'none');
      var file = this.files[0], //2.files配列にファイルが入っています
        $img = $(this).siblings('.prev-img'), //3.jqueryのsiblingsメソッドで兄弟のimgを取得

        fileReader = new FileReader(); //4.ファイルを読み込むFileReaderオブジェクト

      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        //読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };

      //画像読み込み
      fileReader.readAsDataURL(file);
    });

    });
  </script>
  </body>

  </html>