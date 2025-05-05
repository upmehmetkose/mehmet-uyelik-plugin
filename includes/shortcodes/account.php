<?php
// [mehmet_account] – Kullanıcı paneli nav-tab ile
function mehmet_account_shortcode() {
  if (!is_user_logged_in()) {
    return '<div class="mehmet-box">Lütfen giriş yapınız.</div>';
  }

  $user = wp_get_current_user();
  $tabs = get_option('mehmet_account_tabs', [
    'subscriptions' => 'Aboneliklerim',
    'profile' => 'Hesap Bilgileri',
    'password' => 'Şifre Değiştir',
    'cancel' => 'Üyelik İptali',
  ]);
  $active_tab = $_GET['tab'] ?? array_key_first($tabs);

  ob_start();
  ?>
  <style>
    .mehmet-tabs { max-width: 600px; margin: 20px auto; }
    .mehmet-tab-nav { display: flex; gap: 5px; margin-bottom: 20px; list-style: none; padding: 0; }
    .mehmet-tab-nav li a {
      display: inline-block; padding: 10px 15px;
      background: #eee; border-radius: 5px; text-decoration: none;
      transition: background 0.3s;
    }
    .mehmet-tab-nav li a.active { background: #2a7ae2; color: white; }
    .mehmet-tab-content { background: #fafafa; padding: 20px; border-radius: 5px; box-shadow: 0 1px 5px rgba(0,0,0,0.1); }
  </style>

  <div class="mehmet-tabs">
    <ul class="mehmet-tab-nav">
      <?php foreach ($tabs as $key => $label): ?>
        <li><a class="<?= $key === $active_tab ? 'active' : '' ?>" href="?tab=<?= esc_attr($key) ?>"><?= esc_html($label) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <div class="mehmet-tab-content">
      <?php switch ($active_tab):
        case 'subscriptions': ?>
          <p>Burada kullanıcının abonelik bilgileri gösterilecek.</p>
          <?php break;
        case 'profile': ?>
          <p>Ad Soyad: <?= esc_html($user->display_name) ?></p>
          <p>E-Posta: <?= esc_html($user->user_email) ?></p>
          <?php break;
        case 'password': ?>
          <form method="post">
            <input type="password" name="mehmet_new_pass" placeholder="Yeni Şifre" required>
            <button type="submit" name="mehmet_change_pass">Şifreyi Güncelle</button>
          </form>
          <?php
          if (isset($_POST['mehmet_change_pass'])) {
            wp_set_password($_POST['mehmet_new_pass'], $user->ID);
            echo '<p style="color:green;">Şifreniz güncellendi.</p>';
          }
          break;
        case 'cancel': ?>
          <form method="post">
            <button type="submit" name="mehmet_cancel_membership" onclick="return confirm('Üyeliğinizi iptal etmek istediğinize emin misiniz?')">Üyeliği İptal Et</button>
          </form>
          <?php
          if (isset($_POST['mehmet_cancel_membership'])) {
            delete_user_meta($user->ID, 'mehmet_user_plans_timed');
            echo '<p style="color:red;">Üyeliğiniz iptal edildi.</p>';
          }
          break;
        default:
          echo '<p>Bu sekme henüz tanımlanmadı.</p>';
      endswitch; ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode('mehmet_account', 'mehmet_account_shortcode');
