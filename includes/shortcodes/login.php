<?php
// Giriş formu – [mehmet_login]
function mehmet_login_shortcode() {
  if (is_user_logged_in()) return '<div class="mehmet-box">Zaten giriş yaptınız.</div>';

  ob_start();
  ?>
  <form method="post" class="mehmet-box">
    <input type="text" name="mehmet_login_username" placeholder="Kullanıcı Adı veya E-Posta" required>
    <input type="password" name="mehmet_login_password" placeholder="Şifre" required>
    <button type="submit" name="mehmet_login_submit">Giriş Yap</button>
  </form>
  <?php
  if (isset($_POST['mehmet_login_submit'])) {
    $creds = [
      'user_login' => $_POST['mehmet_login_username'],
      'user_password' => $_POST['mehmet_login_password'],
      'remember' => true
    ];
    $user = wp_signon($creds, false);
    if (is_wp_error($user)) {
      echo '<div class="mehmet-box" style="background:#ffe0e0;">Hatalı giriş: ' . $user->get_error_message() . '</div>';
    } else {
      wp_redirect(home_url('/hesabim'));
      exit;
    }
  }
  return ob_get_clean();
}
add_shortcode('mehmet_login', 'mehmet_login_shortcode');
