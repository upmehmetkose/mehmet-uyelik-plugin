<?php
// [mehmet_restrict plan="premium,gold" feature="astroloji,indirim" blur="yes"]
function mehmet_restrict_shortcode($atts, $content = '') {
  $a = shortcode_atts([
    'plan' => '',
    'feature' => '',
    'blur' => 'yes'
  ], $atts);

  $is_logged_in = is_user_logged_in();
  $user_id = get_current_user_id();
  $allowed_plans = array_map('trim', explode(',', $a['plan']));
  $allowed_features = array_map('trim', explode(',', $a['feature']));
  $has_access = false;
  $now = time();

  if ($is_logged_in) {
    $user_plans = get_user_meta($user_id, 'mehmet_user_plans_timed', true);
    $all_plans = get_option('mehmet_plans', []);
    if (is_array($user_plans)) {
      foreach ($user_plans as $user_plan) {
        if ($user_plan['expires'] < $now) continue;

        // Plan adı eşleşmesi varsa
        if (in_array($user_plan['name'], $allowed_plans)) {
          $has_access = true;
          break;
        }

        // Özellik eşleşmesi varsa
        foreach ($all_plans as $defined_plan) {
          if ($defined_plan['name'] === $user_plan['name']) {
            if (!empty($defined_plan['features'])) {
              foreach ($defined_plan['features'] as $f) {
                if (in_array($f, $allowed_features)) {
                  $has_access = true;
                  break 3;
                }
              }
            }
          }
        }
      }
    }
  }

  if ($has_access) {
    return do_shortcode($content);
  }

  $blur = ($a['blur'] === 'yes');

  ob_start();
  ?>
  <div class="<?= $blur ? 'mehmet-blurred-box' : '' ?>">
    <?= do_shortcode($content); ?>
    <?php if ($blur): ?>
      <div class="mehmet-blur-overlay">
        <div class="mehmet-blur-msg">Bu içerik sadece belirli üyeliklere özeldir.</div>
      </div>
    <?php else: ?>
      <p><em>Bu içerik yalnızca yetkili üyeler tarafından görüntülenebilir.</em></p>
    <?php endif; ?>
  </div>
  <style>
    .mehmet-blurred-box {
      position: relative;
      overflow: hidden;
      filter: blur(5px);
      pointer-events: none;
      user-select: none;
    }
    .mehmet-blur-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(255,255,255,0.85);
      display: flex;
      justify-content: center;
      align-items: center;
      pointer-events: auto;
      filter: none;
    }
    .mehmet-blur-msg {
      background: #fff;
      padding: 15px 20px;
      border-radius: 5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      font-weight: bold;
      color: #333;
    }
  </style>
  <?php
  return ob_get_clean();
}
add_shortcode('mehmet_restrict', 'mehmet_restrict_shortcode');
