<?php
function mehmet_birthform_shortcode() {
  if (!is_user_logged_in()) return '<p>Lütfen giriş yapınız.</p>';

  $user_id = get_current_user_id();
  $plans = get_user_meta($user_id, 'mehmet_user_plans_timed', true);
  $plan_name = $plans[0]['name'] ?? 'free';
  $month = date('Y-m');
  $max_limit = ($plan_name === 'premium') ? 10 : 1;

  $history = get_user_meta($user_id, 'mehmet_comments_history', true);
  if (!is_array($history)) $history = [];

  // Bu ay kaç kez kullanmış
  $used_this_month = count(array_filter($history, function($entry) use ($month) {
    return strpos($entry['date'], $month) === 0;
  }));

  if ($used_this_month >= $max_limit) {
    return "<p>Bu ay için sorgu sınırınızı doldurdunuz. (Planınız: $plan_name)</p>";
  }

  ob_start();
  ?>
  <form method="post" style="max-width:400px;margin:20px auto;">
    <input type="text" name="adsoyad" placeholder="Ad Soyad" required>
    <input type="date" name="dogum_tarihi" required>
    <input type="text" name="dogum_saati" placeholder="10:10" required>
    <input type="text" name="dogum_yeri" placeholder="Denizli" required>
    <textarea name="notlar" placeholder="Varsa notunuz..."></textarea>
    <button type="submit" name="mehmet_birth_submit">Gönder</button>
  </form>
  <?php
  if (isset($_POST['mehmet_birth_submit'])) {
    $yorum = "Doğum haritası yorumu: " . $_POST['adsoyad'] . " / " . $_POST['dogum_tarihi'] . " / " . $_POST['dogum_saati'];
    $history[] = [
      'date' => date('Y-m-d H:i'),
      'text' => $yorum
    ];
    update_user_meta($user_id, 'mehmet_comments_history', $history);
    echo '<p style="color:green;">Yorum başarıyla oluşturuldu.</p>';
  }

  return ob_get_clean();
}
add_shortcode('mehmet_birthform', 'mehmet_birthform_shortcode');
