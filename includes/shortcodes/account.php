<?php
// [mehmet_account] – Gelişmiş kullanıcı paneli
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
        $content = $tabs[$active_tab]['content'] ?? '';
        echo do_shortcode(wp_kses_post($content));
      ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode('mehmet_account', 'mehmet_account_shortcode');
