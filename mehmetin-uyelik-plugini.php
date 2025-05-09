<?php
/*
Plugin Name: Mehmet'in Üyelik Plugini
Description: Gelişmiş üyelik sistemi. Kayıt, giriş, hesap yönetimi, ödeme entegrasyonu.
Version: 1.0
Author: Mehmet Köse
*/

defined('ABSPATH') || exit;

// Kısa kodlar
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/register.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/account.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/restrict.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/cancel.php';

// Admin panel
require_once plugin_dir_path(__FILE__) . 'includes/admin/account-tabs.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/plans.php';

// Daha sonra: planlar, ödeme sistemleri vs. buraya eklenecek
