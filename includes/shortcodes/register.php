<?php
// Kayıt formu – [mehmet_register]
function mehmet_register_shortcode() {
  if (is_user_logged_in()) return '<div class="mehmet-box">Zaten giriş yaptınız.</div>';

  ob_start();
  ?>
  <style>
    .mehmet-box {
      max-width: 400px; margin: 20px auto; padding: 25px;
      background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .mehmet-box input, .mehmet-box button {
      width: 100%; padding: 10px; margin-bottom: 10px;
      border-radius: 5px; border: 1px solid #ccc;
    }
    .mehmet-box button {
      background-color: #2a7ae2; color: white; border: none;
      transition: background 0.3s;
    }
    .mehmet-box button:hover {
      background-color: #1a5cc0;
    }
  </style>
  <form method="post" class="mehmet-box">
    <input type="text" name="mehmet_username" placeholder="Kullanıcı Adı" required>
    <input type="email" name="mehmet_email" placeholder="E-Posta" required>
    <input type="password" name="mehmet_password" placeholder="Şifre" required>
    <input type="text" name="mehmet_ref_input" placeholder="Referans Kodu (isteğe bağlı)" value="<?php echo isset($_GET['ref']) ? esc_attr($_GET['ref']) : ''; ?>">
    <button type="submit" name="mehmet_register_submit">Kayıt Ol</button>
  </form>
  <?php

  if (isset($_POST['mehmet_register_submit'])) {
    $username = sanitize_user($_POST['mehmet_username']);
    $email = sanitize_email($_POST['mehmet_email']);
    $password = $_POST['mehmet_password'];

    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
      echo '<div class="mehmet-box" style="background:#ffe0e0;">Hata: ' . $user_id->get_error_message() . '</div>';
    } else {
      // REFERANS KODU İŞLEME
      if (!empty($_POST['mehmet_ref_input'])) {
        $ref_code = sanitize_text_field($_POST['mehmet_ref_input']);
        $ref_user = get_users([
          'meta_key' => 'mehmet_ref_code',
          'meta_value' => $ref_code,
          'number' => 1
        ]);
        if (!empty($ref_user)) {
          $ref_id = $ref_user[0]->ID;
          if (!get_user_meta($user_id, 'mehmet_referrer', true)) {
            update_user_meta($user_id, 'mehmet_referrer', $ref_id);
          }
        }
      }

      echo '<div class="mehmet-box" style="background:#e0ffe0;">Kayıt başarılı! Giriş yapabilirsiniz.</div>';
    }
  }

  return ob_get_clean();
}
add_shortcode('mehmet_register', 'mehmet_register_shortcode');
