<?php
/********************************
 *
 * 此文件包含与管理员和插件界面的函数
 *
 ********************************/



/*
 * 在插件列表页添加一个设置按钮
 */
function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=scws_options">' . __( '设置' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}
add_filter( "plugin_action_links_". SCWS_NAME, 'plugin_add_settings_link' );



/*
 * 注册菜单页面
 */
function scws_menu() {
    add_menu_page(
        __( '敏感瓷', 'sensitive-chinese' ),
        __('敏感瓷', 'sensitive-chinese' ),
        'manage_options',
        'scws_options',
        'scws_menu_function',
        'dashicons-editor-strikethrough',
        6
    );
    add_submenu_page( 
        'scws_options', 
        __( '【敏感瓷】扫描概览', 'sensitive-chinese' ),
        '概览',
        'manage_options',
        'scws_options', 
        'scws_menu_function' 
    );

    include( SCWS_PATH . '/menu/menu-db-scan.php');
    add_submenu_page( 
        'scws_options', 
        __( '【敏感瓷】数据库扫描', 'sensitive-chinese' ),
        '数据库扫描',
        'manage_options',
        'scws_db_scan', 
        'scws_menu_db_scan' 
    );

    include( SCWS_PATH . '/menu/menu-file-scan.php');
    add_submenu_page( 
        'scws_options', 
        __( '【敏感瓷】主题和插件扫描', 'sensitive-chinese' ),
        __('文件扫描', 'sensitive-chinese' ),
        'manage_options',
        'scws_file_scan', 
        'scws_menu_file_scan' 
    );

    include( SCWS_PATH . '/menu/menu-active-scan.php');
    add_submenu_page( 
        'scws_options', 
        __( '【敏感瓷】主动扫描', 'sensitive-chinese' ),
        __('主动扫描', 'sensitive-chinese' ),
        'manage_options',
        'scws_active_scan', 
        'scws_menu_active_scan' 
    );
}
add_action( 'admin_menu', 'scws_menu' );



/*
 * 输出主菜单
 */
function scws_menu_function() {
    
    if (scws_menu_check_activation() === false) return;

    ?>

    <h1><?php _e('嗨', 'sensitive-chinese'); ?>，<?php echo get_option('scws_activation_firstname', ''); ?>！</h1>

    <p><?php printf( __('感谢采用本【敏感瓷】插件扫描%s网站。此插件有三大功能：', 'sensitive-chinese'), get_option('scws_activation_company', '') . "的"); ?></p>

    <ol>
        <li><?php _e('数据库内容扫描；', 'sensitive-chinese'); ?></li>
        <li><?php _e('主题和插件内容扫描；', 'sensitive-chinese'); ?></li>
        <li><?php _e('主动监测新页面、帖子和评论并在朝廷禁忌内容混入您的站点时提醒您。', 'sensitive-chinese'); ?></li>
    </ol>
    
    <p><?php _e('你可以通过左栏菜单项启用扫描。') ?></p>
	<p><?php printf(__('在编辑本插件识别为敏感的任何内容时，请谨慎判断。本插件数据依赖 %s此列表%s，其中包含多个通用术语，例如“it”、“admin”和“gov”。您的网站并不一定会因为插件识别出禁忌内容而触犯朝廷天条。', 'sensitive-chinese'), '<a href="https://github.com/jasonqng/chinese-keywords?utm_source=StudioHyperset.com&utm_medium=Case%20Study&utm_campaign=Launch%20a%20Chinese%20Website&utm_term=StudioHyperset&utm_content=StudioHyperset" target="_blank">', '</a>'); ?></p>
  	<p><strong><?php printf(__('了解我们如何使用此插件帮助一个全球商业智能公司在华夏大地推出其商店网站，%s点这里%s.', 'sensitive-chinese'), '<a href="http://studiohyperset.com/how-do-i-launch-a-chinese-website/?utm_source=GFW_Plugin&utm_medium=Plugin&utm_campaign=Launch%20a%20Chinese%20Website" target="_blank">', '</a>'); ?></strong></p>
  	<hr />
    <p><em>去 <a href="http://studiohyperset.com/?utm_source=GFW_Plugin&utm_medium=Plugin&utm_campaign=Launch%20a%20Chinese%20Website" target="_blank">Studio Hyperset</a> 了解更多</em></p>

   
    <?php
}



/*
 * 注册 CSS
 */
function scws_admin_style() {
    wp_register_style( 'scws_admin_style', SCWS_URL . '/assets/css/styles.css', false, '1.0.0' );
    wp_enqueue_style( 'scws_admin_style' );
}
add_action( 'admin_enqueue_scripts', 'scws_admin_style' );



/*
 * 处理激活函数
 */
function scws_menu_check_activation() {

    if ( get_option( 'scws_activation_email', '' ) == '' ) {
        ?>
        <div id="scws_activate">
            <div class="center-this">
                <h2><?php _e('让我们开始吧！', 'sensitive-chinese'); ?></h2>
                <p><?php _e('为了可以定制属于您的体验，请允许我们获取一些您的信息。', 'sensitive-chinese'); ?></p>

                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/shell.js"></script>
                <script>
                hbspt.forms.create({ 
                    portalId: "4542224",
                    formId: "7ef5737e-0454-478a-b643-4c85cb1c38b2",
                    redirectUrl: "<?php echo admin_url('admin.php?page=scws_options'); ?>",
                    onFormReady: function($form) {
                        $form.find('input').each( function(){
                            jQuery(this).attr('placeholder', jQuery(this).parent().parent().children('label').text());
                        });
                    },
                    onFormSubmit: function($form) {
                        var data = $form.serialize();
                        data += '&action=scws_save_activation';
                        jQuery.post( ajaxurl, data, function( result ) {

                        });
                    }
                });
                </script>
            </div>
        </div>
        <?php

        return false;
    }
    
    return true;

}