<?php
/**
 * @package amigo-performance
 */
?>

<div class='amperf-container'>

    <div class= 'amperf-header'>
        <h1>
            <span class="dashicons dashicons-buddicons-activity amigoperf_icon"></span><?php esc_html_e($this->amigoPerf_PluginName,'amigo-performance'); ?>
            <span class="amigoperf_pluginversion"> <?php esc_html_e(AMIGOPERF_PLUGIN_VERSION,'amigo-performance'); ?></span> 
        </h1>
    </div>

    <form method="post" id="formid">
        <input type="hidden" name="<?php esc_html_e($this->amigoPerf_hfn,'amigo-peformance'); ?>" value="<?php esc_html_e('Y','amigo-performance'); ?>">

        <label class="amigoPerf_lable"><?php esc_html_e('Remove Query String','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_rqs,'amigo-peformance'); ?>" value="<?php  esc_html_e($this->amigoPerf_rqs_opt,'amigo-peformance'); ?>" <?php checked($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs),true);?>  >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Remove Emoji','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_remoji,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_remoji_opt,'amigo-peformance'); ?>" <?php checked($this->amigoPerf_remoji_opt == get_option($this->amigoPerf_remoji),true);?>  >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Defer parsing of JavaScript','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_defer,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_defer_opt,'amigo-peformance'); ?>" <?php checked($this->amigoPerf_defer_opt == get_option($this->amigoPerf_defer),true);?>  >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Iframe Lazyload','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_iframelazy,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_iframelazy_opt,'amigo-peformance'); ?>" <?php checked($this->amigoPerf_iframelazy_opt == get_option($this->amigoPerf_iframelazy), true); ?>  >
            <span class="checkmark"></span>
        </label>
        
        <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" class="amperf-submitbtn" name="submit">
    </form><br>

    <div class="amigoPerf-row">
        <div class="amigoPerf-column">
            <form method="post" id="formid">
            <p><?php esc_html_e('Remove JS','amigo-peformance') ?></p>
            <p><?php esc_html_e('Enter your JS handle which you want to remove form Front Page','amigo-peformance') ?></p>
                <textarea name="<?php esc_html_e('js_hadle','amigo-peformance') ?>" id="js_handle" cols="100" rows="10"><?php echo get_option('amigoPerf_save_nq_script'); ?></textarea><br>
                <input type="submit" value="Save" name="enqueued_js_submit" class="amperf-submitbtn">
            </form>
        </div>
        <div class="amigoPerf-column">
            <form method="post" id="formid">
            <p><?php esc_html_e('Remove CSS','amigo-peformance') ?></p>
            <p><?php esc_html_e('Enter your CSS handle which you want to remove form Front Page','amigo-peformance') ?></p>
                <textarea name="<?php esc_html_e('css_hadle','amigo-peformance') ?>" id="css_handle" cols="100" rows="10"><?php echo get_option('amigoPerf_save_nq_style'); ?></textarea><br>
                <input type="submit" value="Save" name="enqueued_css_submit" class="amperf-submitbtn">
            </form>
        </div>
    </div>

    <div class="amigoPerf-row">
        <div class="amigoPerf-column">
            <?php if (!empty(get_option('amigoPerf_nq_script'))):?>
                <table class="amigoPerf_enqueued">
                    <tr>
                        <th><?php esc_html_e('S.no','amigo-peformance') ?></th>
                        <th><?php esc_html_e('Handle','amigo-peformance') ?></th>
                        <th><?php esc_html_e('Src','amigo-peformance') ?></th>
                    </tr>
                    <?php
                    array_unshift($this->amigoPerf_nqjs_array,"");
                    unset($this->amigoPerf_nqjs_array[0]);
                        for ($i=1; $i <=count($this->amigoPerf_nqjs_array); $i++) {
                            echo '<tr><td>'.$i.'</td>';
                            echo '<td>'.$this->amigoPerf_nqjs_array[$i]['handle'].'</td>';
                            echo '<td>'.$this->amigoPerf_nqjs_array[$i]['src'].'</td></tr>';
                        }
                    ?>
                </table>
                <?php else: esc_html_e('Refresh front page to see results.!','amigo-peformance'); endif; ?>
        </div>

        <div class="amigoPerf-column">
            <?php if (!empty(get_option('amigoPerf_nq_style'))):?>
                <table class="amigoPerf_enqueued">
                    <tr>
                        <th><?php esc_html_e('S.no','amigo-peformance') ?></th>
                        <th><?php esc_html_e('Handle','amigo-peformance') ?></th>
                        <th><?php esc_html_e('Src','amigo-peformance') ?></th>
                    </tr>
                    <?php
                    array_unshift($this->amigoPerf_nqcss_array,"");
                    unset($this->amigoPerf_nqcss_array[0]);
                        for ($i=1; $i <=count($this->amigoPerf_nqcss_array); $i++) {
                            echo '<tr><td>'.$i.'</td>';
                            echo '<td>'.$this->amigoPerf_nqcss_array[$i]['handle'].'</td>';
                            echo '<td>'.$this->amigoPerf_nqcss_array[$i]['src'].'</td></tr>';
                        }
                    ?>
                </table>
                <?php else: esc_html_e('Refresh front page to see results.!','amigo-peformance'); endif; ?>
        </div>
    </div>
</div>