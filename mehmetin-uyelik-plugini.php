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

// Admin panel
require_once plugin_dir_path(__FILE__) . 'includes/admin/account-tabs.php';

// Daha sonra: planlar, ödeme sistemleri vs. buraya eklenecek
