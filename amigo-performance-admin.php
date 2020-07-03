<?php 

    // if (!current_user_can('manage_options')) {
    //     die('You do not have sufficient permissions to access this page.');
    // }

    class AmigoPerfAdmin {        
        public $amigoPerf_hfn = 'amigoPerf_hfn'; //hidden field name
        public $PluginName = 'Amigo Performance';
        public $PluginVersion = '0.1';
        
        public function __construct(){
            //Add Menu
            add_action('admin_menu', array($this, 'amigoperformance_add_pages'));            
        }

        public function amigoPerf_Default(){
            global $amigoPerf_rqs_opt;
            $this->amigoPerf_rqs_opt = ( FALSE !== get_option($this->amigoPerf_rqs) ? get_option($this->amigoPerf_rqs) : 'on'  ); 
            $this->amigoPerf_rqs = 'amigoPerf_rqs';
            $this->amigoPerf_rqs_val = $amigoPerf_rqs_opt;
        }
            
        public function amigoperf_hiddenField(){
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
            return $src;
        }

        public function amigoPerf_rqs_operation()
        {
            //Remove Query Strings
            if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) {
                if(!is_admin()) {                   
                    add_filter( 'style_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                    add_filter( 'script_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                }
            }
            
        }

        public function amigoPerf_menu(){ ?>
            <div class='amperf-container'>

                <div class= 'amperf-header'>
                    <h1> <?php echo $this->PluginName?> </h1>
                </div>

                <form method="post" id="formid">
                    <input type="hidden" name="<?php echo $this->amigoPerf_hfn; ?>" value="Y">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="<?php echo $this->amigoPerf_rqs; ?>" value="<?php echo $this->amigoPerf_rqs_opt ?>" <?php if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) echo 'checked="checked"'; ?> <?php checked($this->amigoPerf_rqs_val, 'on',true) ?> >
                        <label class="custom-control-label" for="<?php echo $this->amigoPerf_rqs; ?>" <?php esc_attr_e('Remove query strings from static content', 'Amigo-Performance'); ?>>Remove Query Strings</label>
                    </div>
                    <input type="submit" value="<?php esc_attr_e('Save Changes','Amigo-Performance') ?>" class="amperf-submitbtn" name="submit">
                </form>

            </div>

       <?php } //amigoPerf_menu() End

       // Register Menu Page
        public function amigoperformance_add_pages() {        
            add_menu_page(
                __('Amigo Perf Page','amigoperf-menupage'), //Page title
                __('Amigo Perf','amigoperf-menu'), //Menu title
                'manage_options', //capability
                'amigo-perf-handle', //menu_slug
                array($this, 'amigoPerf_menu'), //function
                'dashicons-buddicons-activity' //icon url
            );
        }
        
    }
    $amigoPerfDefault = new AmigoPerfAdmin();
    $amigoPerfDefault ->amigoPerf_Default();
    $amigoPerfDefault -> amigoperf_hiddenField();
    $amigoPerfDefault -> amigoPerf_rqs_query('details');
    $amigoPerfDefault -> amigoPerf_rqs_operation();
?>