<?php 
/*
**************************************************************************

Plugin Name:  误碰敏感瓷
Plugin URI:   https://studiohyperset.com/how-do-i-launch-a-chinese-website/
Description:  扫描您的网站内被朝廷视为禁忌的词。编辑或移除插件识别出的内容，降低您的站点被长城拦于关外的几率。若您的站点已被流放，本插件可协您清查可能之因。
Version:      1.2
Author:       Studio Hyperset
Author URI:   https://studiohyperset.com
Text Domain:  敏感瓷, 敏感词, sensitive, chinese

**************************************************************************/

define('SCWS_URL', plugins_url('', __FILE__));
define('SCWS_PATH', plugin_dir_path(__FILE__));
define('SCWS_NAME', plugin_basename( __FILE__ ));



//Core Functions
require('include/ui.php');
require('include/words.php');
require('include/editor.php');
require('include/active-scan.php');



//Ajax Functions
require('ajax/db-scan.php');
require('ajax/file-scan.php');
require('ajax/options.php');


function scws_redirect_to_options( $plugin ) {
    
    if( $plugin == plugin_basename( __FILE__ ) ) { 
        wp_redirect('admin.php?page=scws_options');
        exit;
    }

}
add_action( 'activated_plugin', 'cyb_activation_redirect' );


register_activation_hook(__FILE__, 'scws_trigger_activation');
function scws_trigger_activation() {
    add_option('scws_just_activated', true);
}


add_action('admin_init', 'scws_show_activation');
function scws_show_activation() {
    if (get_option('scws_just_activated', false)) {
        delete_option('scws_just_activated');
        wp_redirect('admin.php?page=scws_options');
        exit;
    }
}