

<body>
  <?php
  require('header.php');
  ?>
  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <main>
    <div class="inner-wrap">
      <h2 class="page-title">登録画面</h2>
      <form action="" method="post" class="form-width">

        <span class="area-msg <?php if (!empty($err_msg['common'])) echo 'err'; ?>"><?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?></span>

        <span class="area-msg <?php if (!empty($err_msg['email'])) echo 'err'; ?>"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></span>

        <label>Eメール<br>
          <input type="text" name="email" value="<?php getFormData('email'); ?>">
        </label>

        <span class="area-msg <?php if (!empty($err_msg['password'])) echo 'err'; ?>"><?php if (!empty($err_msg['password'])) echo $err_msg['password']; ?></span>

        <label>パスワード
          <input type="password" name="password">
        </label>
        <div class="login-check"><label>ログイン保持
            <input type="checkbox" name="login_save">
          </label>
        </div>
        <input type="submit" value="送信">
      </form>
      <p style="text-align:center; padding-top: 25px; width:560px; margin:0 auto;">パスワードを忘れた方は<a href="./passRemindSend.php">こちら</a></p>
    </div>
  </main>
  <?php
  require('footer.php');
  ?>