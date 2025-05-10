<?php
// [mehmet_account] – Gelişmiş kullanıcı paneli + özel sekme işlevleri
function mehmet_account_shortcode() {
  if (!is_user_logged_in()) return '<div class="mehmet-box">Lütfen giriş yapınız.</div>';

  $user = wp_get_current_user();
  $tabs = get_option('mehmet_account_tabs', []);
  $active_tab = $_GET['tab'] ?? array_key_first($tabs);

  ob_start();
  ?>
  <style>
    .mehmet-tabs { max-width: 700px; margin: 20px auto; }
    .mehmet-tab-nav {
      display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 15px;
      list-style: none; padding: 0;
    }
    .mehmet-tab-nav li a {
      display: block; padding: 10px 15px;
      background: #eee; border-radius: 5px;
      text-decoration: none; color: #333;
    }
    .mehmet-tab-nav li a.active {
      background: #2a7ae2; color: white;
    }
    .mehmet-tab-content {
      background: #fdfdfd; padding: 20px;
      border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
  </style>

  <div class="mehmet-tabs">
    <ul class="mehmet-tab-nav">
      <?php foreach ($tabs as $key => $data): ?>
        <?php $label = is_array($data) ? $data['label'] : $data; ?>
        <li><a href="?tab=<?= esc_attr($key) ?>" class="<?= $key === $active_tab ? 'active' : '' ?>">
          <?= esc_html($label) ?>
        </a></li>
      <?php endforeach; ?>
    </ul>

    <div class="mehmet-tab-content">
      <?php
        if ($active_tab === 'referans') {
          // ÖZEL REFERANS SEKME İÇERİĞİ
          $ref_code = get_user_meta($user->ID, 'mehmet_ref_code', true);
          echo "<p>Senin referans kodun: <strong>$ref_code</strong></p>";
          echo "<p>Paylaş: <code>" . home_url('/register') . "?ref=$ref_code</code></p>";

          $ref_users = get_users(['meta_key' => 'mehmet_referrer', 'meta_value' => $user->ID]);
          if ($ref_users) {
            echo "<h4>Davet Ettiklerin:</h4><ul>";
            foreach ($ref_users as $u) {
              $premium = get_user_meta($u->ID, 'mehmet_user_plans_timed', true);
              $status = (is_array($premium) && !empty($premium)) ? '✅ Premium' : '⏳ Bekliyor';
              echo "<li>{$u->user_login} – $status</li>";
            }
            echo "</ul>";
          } else {
            echo "<p>Henüz kimseyi davet etmediniz.</p>";
          }

        } else {
          // Diğer sekmeler: admin panelden gelen içerik
          $content = $tabs[$active_tab]['content'] ?? '';
          echo do_shortcode(wp_kses_post($content));
        }
      ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode('mehmet_account', 'mehmet_account_shortcode');
