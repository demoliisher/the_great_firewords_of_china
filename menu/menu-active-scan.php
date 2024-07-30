<?php
/*
 * 输出主动扫描内容
 */
function scws_menu_active_scan() {
    
    if (scws_menu_check_activation() === false) return;

    ?>

    <h1><?php echo get_option('scws_activation_company', '') . "'s "; ?><?php _e('主动扫描结果', 'sensitive-chinese'); ?></h1>

    <h2><?php _e('选项', 'sensitive-chinese'); ?></h2>

    <table>
        <tr>
            <td>
                <label for="enable_active_scan"><?php _e('是否启用主动扫描？', 'sensitive-chinese'); ?></label>
            </td>
            <td>
                <select name="enable_active_scan" id="enable_active_scan">
                        <?php $option = get_option( 'scws_enable_active_scan', 'no' ); ?>
                        <option value="yes" <?php selected( 'yes', $option, true ); ?>><?php _e('是', 'sensitive-chinese'); ?></option>
                        <option value="no" <?php selected( 'no', $option, true ); ?>><?php _e('否', 'sensitive-chinese'); ?></option>
                </select>
                <p><em><?php _e('选择“是”将主动监测新页面、新帖子、新评论、新用户和新条目内容中是否有敏感词。', 'sensitive-chinese'); ?></em></p>
            </td>
        </tr>
        <tr>
            <td>
                <label for="active_scan_warn"><?php _e('发送警报邮件？', 'sensitive-chinese'); ?></label>
            </td>
            <td>
                <select name="active_scan_warn" id="active_scan_warn">
                        <?php $option = get_option( 'scws_active_scan_warn', 'no' ); ?>
                        <option value="yes" <?php selected( 'yes', $option, true ); ?>><?php _e('是', 'sensitive-chinese'); ?></option>
                        <option value="no" <?php selected( 'no', $option, true ); ?>><?php _e('否', 'sensitive-chinese'); ?></option> 
                </select>
                <input type="email" name="active_scan_warn_email" id="active_scan_warn_email" value="<?php echo get_option( 'scws_active_scan_warn_email', get_option('scws_activation_email', '') ); ?>" placeholder="<?php _e('Email Address', 'sensitive-chinese'); ?>" />
                <p><em><?php _e('选择“是”并输入您的名字和邮箱地址，您将会在插件发现敏感词时收到警报邮件。', 'sensitive-chinese'); ?></em></p>
            </td>
        </tr>
        <?php /*<tr>
            <td>
                <label for="active_scan_autoreplace"><?php _e('自动替换关键词？', 'sensitive-chinese'); ?></label>
            </td>
            <td>
                <select name="active_scan_autoreplace" id="active_scan_autoreplace">
                        <?php $option = get_option( 'scws_active_scan_autoreplace', 'no' ); ?>
                        <option value="yes" <?php selected( 'yes', $option, true ); ?>><?php _e('是', 'sensitive-chinese'); ?></option>
                        <option value="no" <?php selected( 'no', $option, true ); ?>><?php _e('否', 'sensitive-chinese'); ?></option> 
                </select>
                <input type="text" name="active_scan_autoreplace_word" id="active_scan_autoreplace_word" value="<?php echo get_option( 'scws_active_scan_autoreplace_word', '' ); ?>" placeholder="" />
                <p><em><?php _e('当捕获到新的检测结果时，插件会用这个词替换敏感词。', 'sensitive-chinese'); ?></em></p>
            </td>
        </tr> */ ?>
    </table>

    <script type="text/javascript">
    jQuery(document).ready( function($){
    
        $('table select').change( function(){
            
            $('#result').addClass('loading');

            //Send the form data
            var data = 'action=scws_save_options&' + $(this).attr('name') + '=' + $(this).val();

            $.post( ajaxurl, data, function( result ) {

                $('#result').removeClass('loading');
                if ( result != '0')
                        $('#result').html('已保存！');
                else
                        $('#result').html('出错了，稍后重试！');

                setTimeout(function() {
                        $('#result').html('');
                }, 2000);

            });

        });

        var runningSaveEmail = false;
        $('#active_scan_warn_email').keyup( function(){

            $('#result').addClass('loading');

            //Send the form data
            var data = 'action=scws_save_options&' + $(this).attr('name') + '=' + $(this).val();

            clearTimeout(runningSaveEmail);
            runningSaveEmail = setTimeout(function() {
                

                $.post( ajaxurl, data, function( result ) {

                        $('#result').removeClass('loading');
                        if ( result != '0')
                            $('#result').html('已保存！');
                        else
                            $('#result').html('出错了，稍后重试！');

                        setTimeout(function() {
                            $('#result').html('');
                        }, 2000);

                });
            }, 500);

        });

        var runningSaveReplace = false;
        $('#active_scan_autoreplace_word').keyup( function(){
            
            $('#result').addClass('loading');

            //Send the form data
            var data = 'action=scws_save_options&' + $(this).attr('name') + '=' + $(this).val();

            clearTimeout(runningSaveReplace);
            runningSaveReplace = setTimeout(function() {
                $.post( ajaxurl, data, function( result ) {

                        $('#result').removeClass('loading');
                        if ( result != '0')
                            $('#result').html('已保存！');
                        else
                            $('#result').html('出错了，稍后重试！');

                        setTimeout(function() {
                            $('#result').html('');
                        }, 2000);

                });
            }, 500);

        });

    });
    </script>

    <div id="result"></div>

    <h2><?php _e('Recent Scans', 'sensitive-chinese'); ?></h2>

    <?php $report = get_option( 'scws_active_report', array() ); ?>

    <?php if (empty($report)) : ?>

        <p><?php _e('插件还没识别到敏感内容。', 'sensitive-chinese'); ?></p>

    <?php else : ?>

        <?php $report = array_reverse($report); ?>

        <?php foreach ($report as $r) : ?>
            
            <?php echo '<li class="recent-scan">'. $r . '</li>'; ?>
        
        <?php endforeach; ?>

    <?php endif; ?>
    <?php
}