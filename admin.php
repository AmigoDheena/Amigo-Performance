<?php
/**
 * @package amigo-performance
 */
?>

<div class='amperf-container'>

    <div class= 'amperf-header'>
        <h1>
            <span class="dashicons dashicons-buddicons-activity amigoperf_icon"></span><?php echo $this->amigoPerf_PluginName?>
            <span class="amigoperf_pluginversion"> <?php echo AMIGOPERF_PLUGIN_VERSION ?></span> 
        </h1>
    </div>

    <form method="post" id="formid">
        <input type="hidden" name="<?php echo $this->amigoPerf_hfn; ?>" value="Y">

        <label class="amigoPerf_lable">Remove Query String                        
            <input type="checkbox" class="custom-control-input" name="<?php echo $this->amigoPerf_rqs; ?>" value="<?php echo $this->amigoPerf_rqs_opt ?>" <?php if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_rqs_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable">Remove Emoji
            <input type="checkbox" class="custom-control-input" name="<?php echo $this->amigoPerf_remoji; ?>" value="<?php echo $this->amigoPerf_remoji_opt ?>" <?php if($this->amigoPerf_remoji_opt == get_option($this->amigoPerf_remoji)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_remoji_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable">Defer parsing of JavaScript
            <input type="checkbox" class="custom-control-input" name="<?php echo $this->amigoPerf_defer; ?>" value="<?php echo $this->amigoPerf_defer_opt ?>" <?php if($this->amigoPerf_defer_opt == get_option($this->amigoPerf_defer)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_defer_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>

        <label class="amigoPerf_lable">Iframe Lazyload
            <input type="checkbox" class="custom-control-input" name="<?php echo $this->amigoPerf_iframelazy; ?>" value="<?php echo $this->amigoPerf_iframelazy_opt ?>" <?php if($this->amigoPerf_iframelazy_opt == get_option($this->amigoPerf_iframelazy)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_iframelazy_val, 'on',true) ?> >
            <span class="checkmark"></span>
        </label>
        
        <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" class="amperf-submitbtn" name="submit">
    </form>

</div>