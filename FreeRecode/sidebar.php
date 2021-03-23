      <div class="side-bar">
        <p><a href="./profEdit.php">プロフィール編集</a></p>
        <p><a href="./passEdit.php">パスワード変更</a></p>
        <p><a href="./withdraw.php">退会</a></p>
        <?php if (basename($_SERVER['PHP_SELF']) === 'mypage.php') { ?>
          <form action="" method="get" class="word_search">
            <label>
              検索フォーム
              <input type="text" name="search-word" class="search">
            </label>
            <input type="submit" value="送信">
          </form>
        <?php }
        ?>
      </div>