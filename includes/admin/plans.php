<?php
// Admin panel > Planlar
add_action('admin_menu', function () {
  add_submenu_page(
    'options-general.php',
    'Plan Yönetimi',
    'Planlar',
    'manage_options',
    'mehmet_plans',
    'mehmet_plan_settings_page'
  );
});

function mehmet_plan_settings_page() {
  if (isset($_POST['mehmet_plan_save'])) {
    $names = $_POST['plan_name'] ?? [];
    $durations = $_POST['plan_duration'] ?? [];
    $prices = $_POST['plan_price'] ?? [];
    $features = $_POST['plan_features'] ?? [];

    $plans = [];
    foreach ($names as $i => $name) {
      if (!empty($name)) {
        $plans[] = [
          'name' => sanitize_text_field($name),
          'duration' => intval($durations[$i]),
          'price' => floatval($prices[$i]),
          'features' => array_map('sanitize_text_field', $features[$i] ?? [])
        ];
      }
    }

    update_option('mehmet_plans', $plans);
    echo '<div class="updated"><p>Planlar kaydedildi.</p></div>';
  }

  $plans = get_option('mehmet_plans', []);
  ?>

  <div class="wrap">
    <h1>Plan Yönetimi</h1>
    <form method="post">
      <table class="form-table" id="plan-table">
        <thead><tr><th>Plan Adı</th><th>Süre (gün)</th><th>Fiyat ($)</th><th>Erişim</th></tr></thead>
        <tbody>
        <?php foreach ($plans as $i => $plan): ?>
          <tr>
            <td><input type="text" name="plan_name[]" value="<?= esc_attr($plan['name']) ?>"></td>
            <td><input type="number" name="plan_duration[]" value="<?= esc_attr($plan['duration']) ?>"></td>
            <td><input type="number" name="plan_price[]" step="0.01" value="<?= esc_attr($plan['price']) ?>"></td>
            <td>
              <label><input type="checkbox" name="plan_features[<?= $i ?>][]" value="premium" <?= in_array('premium', $plan['features']) ? 'checked' : '' ?>> Premium İçerik</label><br>
              <label><input type="checkbox" name="plan_features[<?= $i ?>][]" value="indirim" <?= in_array('indirim', $plan['features']) ? 'checked' : '' ?>> Özel İndirim</label>
            </td>
          </tr>
        <?php endforeach; ?>
          <tr>
            <td><input type="text" name="plan_name[]" placeholder="Plan adı"></td>
            <td><input type="number" name="plan_duration[]" placeholder="30"></td>
            <td><input type="number" name="plan_price[]" step="0.01" placeholder="19.99"></td>
            <td>
              <label><input type="checkbox" name="plan_features[<?= count($plans) ?>][]" value="premium"> Premium İçerik</label><br>
              <label><input type="checkbox" name="plan_features[<?= count($plans) ?>][]" value="indirim"> Özel İndirim</label>
            </td>
          </tr>
        </tbody>
      </table>
      <p><button type="submit" name="mehmet_plan_save" class="button button-primary">Planları Kaydet</button></p>
    </form>
  </div>
<?php } ?>
