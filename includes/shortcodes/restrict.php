<?php
// [mehmet_restrict plan="premium"]...[/mehmet_restrict]
function mehmet_restrict_shortcode($atts, $content = '') {
  $a = shortcode_atts(['plan' => ''], $atts);
  if (!is_user_logged_in()) return '<p>Sadece üyeler içindir.</p>';

  $user_id = get_current_user_id();
  $user_plans = get_user_meta($user_id, 'mehmet_user_plans_timed', true);
  if (!is_array($user_plans)) return '<p>Plan bulunamadı.</p>';

  foreach ($user_plans as $plan) {
    $plan_name = $plan['name'];
    $plans = get_option('mehmet_plans', []);
    foreach ($plans as $p) {
      if ($p['name'] === $plan_name && in_array($a['plan'], $p['features'])) {
        return do_shortcode($content);
      }
    }
  }

  return '<p>Bu içeriği görüntülemek için uygun plana sahip olmanız gerekir.</p>';
}
add_shortcode('mehmet_restrict', 'mehmet_restrict_shortcode');
