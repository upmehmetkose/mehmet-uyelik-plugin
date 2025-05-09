<?php
// [mehmet_cancel] – Aboneliği iptal etme kısa kodu
function mehmet_cancel_shortcode() {
  if (!is_user_logged_in()) return '<p>Lütfen giriş yapınız.</p>';

  $user_id = get_current_user_id();
  $plans = get_user_meta($user_id, 'mehmet_user_plans_timed', true);

  if (!is_array($plans) || empty($plans)) {
    return '<p>Aktif bir aboneliğiniz bulunmamaktadır.</p>';
  }

  if (isset($_POST['mehmet_plan_cancel'])) {
    delete_user_meta($user_id, 'mehmet_user_plans_timed');
    return '<p style="color:red;">Aboneliğiniz iptal edilmiştir.</p>';
  }

  ob_start();
  echo '<form method="post" style="max-width:400px; margin:20px auto; background:#fff; padding:20px; border-radius:8px;">';
  echo '<p><strong>Aktif Abonelikleriniz:</strong></p><ul>';
  foreach ($plans as $p) {
    echo '<li>' . esc_html($p['name']) . ' – Bitiş: ' . date('d.m.Y', $p['expires']) . '</li>';
  }
  echo '</ul>';
  echo '<p>Aboneliğinizi iptal etmek üzeresiniz. Bu işlem geri alınamaz.</p>';
  echo '<button type="submit" name="mehmet_plan_cancel">Aboneliği İptal Et</button>';
  echo '</form>';
  return ob_get_clean();
}
add_shortcode('mehmet_cancel', 'mehmet_cancel_shortcode');
