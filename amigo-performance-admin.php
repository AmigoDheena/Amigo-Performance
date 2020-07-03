<?php 
    require 'amigo-performance-global.php';

    if (!current_user_can('manage_options')) {
        die('You do not have sufficient permissions to access this page.');
    }

    class AmigoPerfAdmin{        
        public $amigoPerf_hfn = 'amigoPerf_hfn'; //hidden field name
        
        public function __construct(){
            if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) {
                if(!is_admin()) {
                    add_filter( 'style_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                    add_filter( 'script_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                }
            }
        }

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

        public function amigoPerf_rqs_query($src)
        {           
            if(strpos( $src, '?ver=' ))
                $src = remove_query_arg( 'ver', $src );
                echo "SRCC".$src;
            return $src;
        }
    }
    $amigoPerfDefault = new AmigoPerfAdmin();
    $amigoPerfDefault ->amigoPerf_Default();
    $amigoPerfDefault -> amigoperf_hiddenField();
    $amigoPerfDefault -> amigoPerf_rqs_query('details');

?>

<div class='amperf-container'>

    <div class= 'amperf-header'>
        <h1> <?php echo $PluginName?> </h1>
    </div>

    <form method="post" id="formid">
        <input type="hidden" name="<?php echo $amigoPerfDefault->amigoPerf_hfn; ?>" value="Y">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="<?php echo $amigoPerfDefault->amigoPerf_rqs; ?>" value="<?php echo $amigoPerfDefault->amigoPerf_rqs_opt ?>" <?php if($amigoPerfDefault->amigoPerf_rqs_opt == get_option($amigoPerfDefault->amigoPerf_rqs)) echo 'checked="checked"'; ?> <?php checked($amigoPerfDefault->amigoPerf_rqs_val, 'on',true) ?> >
            <label class="custom-control-label" for="<?php echo $amigoPerfDefault->amigoPerf_rqs; ?>" <?php esc_attr_e('Remove query strings from static content', 'Amigo-Performance'); ?>>Remove Query Strings</label>
        </div>
        <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" class="amperf-submitbtn" name="submit">
    </form>

</div>