<?php 
    require 'amigo-performance-global.php';

    if (!current_user_can('manage_options')) {
        die('You do not have sufficient permissions to access this page.');
    }

    class AmigoPerfAdmin{        
        public $amigoPerf_hfn = 'amigoPerf_hfn'; //hidden field name

        public function amigoPerf_Default(){
            global $amigoPerf_rqs_opt;
            $this->amigoPerf_rqs_opt = ( FALSE !== get_option($this->amigoPerf_rqs) ? get_option($this->amigoPerf_rqs) : 'on'  ); 
            $this->amigoPerf_rqs = 'amigoPerf_rqs';
            $this->amigoPerf_rqs_val = $amigoPerf_rqs_opt;
        }
            
        function amigoperf_hiddenField(){
            if (isset($_POST[$this->amigoPerf_hfn]) && $_POST[$this->amigoPerf_hfn] == 'Y') {
                $this->amigoPerf_rqs_val = (isset($_POST[$this->amigoPerf_rqs]) ? $_POST[$this->amigoPerf_rqs] : "off");

                update_option( $this->amigoPerf_rqs, $this->amigoPerf_rqs_val );
                flush_rewrite_rules();
            }
        }
    }
    $amigoPerfDefault = new AmigoPerfAdmin();
    $amigoPerfDefault ->amigoPerf_Default();
    $amigoPerfDefault -> amigoperf_hiddenField();
?>

<div class='container'>

    <div class= 'amperf-header'>
        <h1> <?php echo $PluginName?> </h1>
    </div>

<form method="post">
    <input type="hidden" name="<?php echo $amigoPerfDefault->amigoPerf_hfn; ?>" value="Y">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="<?php echo $amigoPerfDefault->amigoPerf_rqs; ?>" <?php checked($amigoPerfDefault->amigoPerf_rqs_val, 'on',true) ?> >
        <label class="custom-control-label" for="<?php echo $amigoPerfDefault->amigoPerf_rqs; ?>" <?php esc_attr_e('Remove query strings from static content', 'Amigo-Performance'); ?>>Remove Query Strings</label>
    </div>
    <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" name="submit">
</form>

</div>