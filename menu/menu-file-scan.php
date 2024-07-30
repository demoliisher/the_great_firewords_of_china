<?php
/*
 * 输出文件扫描内容
 */
function scws_menu_file_scan() {

    if (scws_menu_check_activation() === false) return;
    ?>

    <h1><?php echo get_option('scws_activation_company', '') . "'s "; ?><?php _e('文件扫描', 'sensitive-chinese'); ?></h1>

    <p><?php _e('敏感瓷插件将扫描以下文件类型：txt, php, js, doc, html & xml.', 'sensitive-chinese'); ?></p>
        
         <p style="margin-right:20px"><?php _e('如果你在扫描大文件夹时遇到了超时问题，尝试使用“分卷搜索”功能将搜索分为小单元。例如，如果你在框内输入“2”，插件会将搜索分为两次进行。对于小型目录，用“1”也随便。'); ?></p>
          
    <form id="scws_run_file_scan">
        <input type="hidden" name="action" value="scws_file_scan" />
        <input type="hidden" name="scws_file_scan_nonce" value="<?php echo wp_create_nonce( 'scws_file_scan_nonce' ); ?>" />
        
        <?php
        $options = array();
        $themes = wp_get_themes( array('errors' => null, 'allowed' => null, 'blog_id' => 0) );
        if (count($themes) > 0) {
            foreach ($themes as $key => $value) {
                $options[] = '<option value="T|||'. $key .'">[Theme] '. $value->display('Name') .'</option>';
            }
        }

        $plugins = get_plugins();
        if (count($plugins) > 0) {
            foreach ($plugins as $key => $value) {
                $options[] = '<option value="P|||'. $key .'">[Plugin] '. $value['Name'] .'</option>';
            }
        }

        if (count($options) > 0) {
            echo '<select name="file_look">';
            foreach($options as $option) {
                echo $option;
            }
            echo '</select>';
        }
        ?>
        <input type="number" value="" style="width:300px" min="1" placeholder="分卷搜索" name="totalpieces" required/>
        <button><?php _e('运行文件扫描', 'sensitive-chinese'); ?></button>
    </form>

    <div id="result"></div>

    <script type="text/javascript">
    jQuery(document).ready( function($){

        var currentPiece = 1,
            currentRunning = false;

        $('#scws_run_file_scan').submit( function(e){

            e.preventDefault();
            
            if (currentRunning === true && currentPiece == 1)
                return;
            currentRunning = true;

            $('#result').addClass('loading');

            var select = $(this).children('select'),
                field = $(this).find('input[name="totalpieces"]'),
                data = $(this).serialize(),
                pieces = parseInt(field.val());

            data += '&nextpiece='+ currentPiece;

            select.attr('disabled', 'disabled');
            field.attr('disabled', 'disabled');

            //Send the form data
            $.post( ajaxurl, data, function( result ) {

                if ( result == '0000') {

                        $('#result').append( '<div>搜索已提前完成，没必要用分卷搜索功能。</div>' );
                        $('#result').removeClass('loading');
                        select.attr('disabled', false);
                        field.attr('disabled', false);
                        currentPiece = 1;
                        currentRunning = false;
                        
                } else {

                        $('#result').append( result );
                        $('#result').removeClass('loading');
                        select.attr('disabled', false);
                        field.attr('disabled', false);

                        if (currentPiece == pieces) {
                            currentPiece = 1;
                        } else {
                            currentPiece++;
                            $('#scws_run_file_scan').submit();
                        }
                        currentRunning = false;

                }

            }).fail(function(){
                $('#result').append( '<div>服务器超时，请尝试在“分卷搜索”框输入一个更大的数字。</div>' );
                $('#result').removeClass('loading');
                select.attr('disabled', false);
                field.attr('disabled', false);
                currentRunning = false;
            });

        });

        $(document).on('click', '#result li', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).toggleClass('open');
        });

        $(document).on('click', '#result li a', function(e){
            e.stopPropagation();
        });

    });
    </script>

    <?php
}