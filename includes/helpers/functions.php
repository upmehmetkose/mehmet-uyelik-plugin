<?php
// Yeni kullanıcıya otomatik referans kodu atayan fonksiyon
add_action('user_register', function ($user_id) {
  $user = get_userdata($user_id);
  $existing_code = get_user_meta($user_id, 'mehmet_ref_code', true);
  if (!$existing_code && $user) {
    $code = sanitize_user($user->user_login) . '_' . wp_generate_password(5, false, false);
    update_user_meta($user_id, 'mehmet_ref_code', $code);
  }
});
