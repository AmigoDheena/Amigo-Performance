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
        <input type="hidden" name="<?php esc_html_e($this->amigoPerf_hfn,'amigo-peformance'); ?>" value="Y">

        <label class="amigoPerf_lable"><?php esc_html_e('Remove Query String','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_rqs,'amigo-peformance'); ?>" value="<?php  esc_html_e($this->amigoPerf_rqs_opt,'amigo-peformance'); ?>" <?php if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_rqs_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Remove Emoji','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_remoji,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_remoji_opt,'amigo-peformance'); ?>" <?php if($this->amigoPerf_remoji_opt == get_option($this->amigoPerf_remoji)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_remoji_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Defer parsing of JavaScript','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_defer,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_defer_opt,'amigo-peformance'); ?>" <?php if($this->amigoPerf_defer_opt == get_option($this->amigoPerf_defer)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_defer_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable"><?php esc_html_e('Iframe Lazyload','amigo-performance'); ?>
            <input type="checkbox" class="custom-control-input" name="<?php esc_html_e($this->amigoPerf_iframelazy,'amigo-peformance'); ?>" value="<?php esc_html_e($this->amigoPerf_iframelazy_opt,'amigo-peformance'); ?>" <?php if($this->amigoPerf_iframelazy_opt == get_option($this->amigoPerf_iframelazy)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_iframelazy_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>
        
        <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" class="amperf-submitbtn" name="submit">
    </form>

</div>