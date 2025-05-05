<?php
// Admin panelden "Hesabım Sekmeleri"ni yönet
add_action('admin_menu', function () {
  add_submenu_page(
    'options-general.php',
    'Hesap Sayfası Sekmeleri',
    'Hesap Sekmeleri',
    'manage_options',
    'mehmet_account_tabs',
    'mehmet_account_tabs_page'
  );
});

function mehmet_account_tabs_page() {
  if (isset($_POST['mehmet_tabs_save'])) {
    $labels = $_POST['tab_labels'] ?? [];
    $keys = $_POST['tab_keys'] ?? [];
    $new_tabs = [];

    foreach ($keys as $i => $key) {
      if (!empty($key) && !empty($labels[$i])) {
        $new_tabs[sanitize_key($key)] = sanitize_text_field($labels[$i]);
      }
    }

    update_option('mehmet_account_tabs', $new_tabs);
    echo '<div class="updated"><p>Kaydedildi.</p></div>';
  }

  $tabs = get_option('mehmet_account_tabs', [
    'subscriptions' => 'Aboneliklerim',
    'profile' => 'Hesap Bilgileri',
    'password' => 'Şifre Değiştir',
    'cancel' => 'Üyelik İptali',
  ]);
  ?>

  <div class="wrap">
    <h1>Hesap Sayfası Sekmeleri</h1>
    <form method="post">
      <table class="form-table" id="tab-table">
        <thead><tr><th>Tab Key</th><th>Etiket</th></tr></thead>
        <tbody>
          <?php foreach ($tabs as $key => $label): ?>
            <tr>
              <td><input type="text" name="tab_keys[]" value="<?= esc_attr($key) ?>"></td>
              <td><input type="text" name="tab_labels[]" value="<?= esc_attr($label) ?>"></td>
            </tr>
          <?php endforeach; ?>
          <tr>
              <td><input type="text" name="tab_keys[]" placeholder="yeni_key"></td>
              <td><input type="text" name="tab_labels[]" placeholder="Yeni Etiket"></td>
          </tr>
        </tbody>
      </table>
      <p><button type="submit" name="mehmet_tabs_save" class="button-primary">Kaydet</button></p>
    </form>
  </div>
<?php
}
