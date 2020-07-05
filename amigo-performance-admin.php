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
            global $amigoPerf_rqs_opt, $amigoPerf_remoji_opt, $amigoPerf_defer_opt, $amigoPerf_iframelazy_opt;

            $this->amigoPerf_rqs_opt = ( FALSE !== get_option($this->amigoPerf_rqs) ? get_option($this->amigoPerf_rqs) : 'on'  ); 
            $this->amigoPerf_rqs = 'amigoPerf_rqs';
            $this->amigoPerf_rqs_val = $amigoPerf_rqs_opt;

            $this->amigoPerf_remoji_opt = ( FALSE !== get_option($this->amigoPerf_remoji) ? get_option($this->amigoPerf_remoji) : 'on'  ); 
            $this->amigoPerf_remoji = 'amigoPerf_remoji';
            $this->amigoPerf_remoji_val = $amigoPerf_remoji_opt;
            
            $this->amigoPerf_defer_opt = ( FALSE !== get_option($this->amigoPerf_defer) ? get_option($this->amigoPerf_defer) : 'on'  ); 
            $this->amigoPerf_defer = 'amigoPerf_defer';
            $this->amigoPerf_defer_val = $amigoPerf_defer_opt;
            
            $this->amigoPerf_iframelazy_opt = ( FALSE !== get_option($this->amigoPerf_iframelazy) ? get_option($this->amigoPerf_iframelazy) : 'on'  ); 
            $this->amigoPerf_iframelazy = 'amigoPerf_iframelazy';
            $this->amigoPerf_iframelazy_val = $amigoPerf_iframelazy_opt;
        }
            
        public function amigoperf_hiddenField(){
            if (isset($_POST[$this->amigoPerf_hfn]) && $_POST[$this->amigoPerf_hfn] == 'Y') {
                $this->amigoPerf_rqs_val = (isset($_POST[$this->amigoPerf_rqs]) ? $_POST[$this->amigoPerf_rqs] : "off");
                update_option( $this->amigoPerf_rqs, $this->amigoPerf_rqs_val );

                $this->amigoPerf_remoji_val = (isset($_POST[$this->amigoPerf_remoji]) ? $_POST[$this->amigoPerf_remoji] : "off");
                update_option( $this->amigoPerf_remoji, $this->amigoPerf_remoji_val );
                
                $this->amigoPerf_defer_val = (isset($_POST[$this->amigoPerf_defer]) ? $_POST[$this->amigoPerf_defer] : "off");
                update_option( $this->amigoPerf_defer, $this->amigoPerf_defer_val );
                
                $this->amigoPerf_iframelazy_val = (isset($_POST[$this->amigoPerf_iframelazy]) ? $_POST[$this->amigoPerf_iframelazy] : "off");
                update_option( $this->amigoPerf_iframelazy, $this->amigoPerf_iframelazy_val );

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
            if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) {
                if(!is_admin()) {                   
                    add_filter( 'style_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                    add_filter( 'script_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                }
            }            
        }

        public function amigoPerf_remoji_operation()
        {
            if($this->amigoPerf_remoji_opt == get_option($this->amigoPerf_remoji)) {
                remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
                remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
                remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
                remove_action( 'admin_print_styles', 'print_emoji_styles' );
            }
        }

        public function amigoPerf_defer_operation(){
            if($this->amigoPerf_defer_opt == get_option($this->amigoPerf_defer)) {
                if(!is_admin()) {
                    add_filter( 'script_loader_tag', function ( $tag, $handle ) {
                        if(is_front_page()) {
                            if ( 'jquery-core' == $handle){ return $tag; } 
                        } else {
                        return $tag;
                        }
                        return str_replace( ' src', ' defer="defer" src', $tag );
                    }, 10, 2 );
                }
            }
        }

        public function amigoPerf_iframelazy_operation()
        {
            //Inline Footer
            function inline_footer(){ ?>
                <!-- Allpage -->
                <script>
                    /* 
                    URL: https://github.com/AmigoDheena/amigolazy
                    Author: Amigo Dheena
                    */
                    let amframe = document.querySelectorAll('.amigolazy');
                    window.onload = function(){
                        for(let i=0; i<amframe.length;i++){
                            let amsrc = amframe[i];
                            let amdata = amsrc.getAttribute("data-src");
                            let datanew = amsrc.getAttribute("lazy");
                            if(datanew === null){
                            datanew = 1500;
                            }
                            setTimeout(function(){
                            amframe[i].setAttribute("src",amdata);
                            console.info(datanew + "ms Lazyloaded " + amframe[i].src);
                            }, datanew);
                        }
                    }
                </script>
                <!-- Allpage -->
            <?php }
            //Inline Footer
            add_action('wp_footer', 'inline_footer', 100);
        }

        public function link_rel_buffer_callback($buffer) {
            $buffer = preg_replace('/iframe(.src)/m', 'iframe class="amigolazy" lazy="3000" data-src', $buffer);
            return $buffer;
        }

        public function link_rel_buffer_start() {
            ob_start(array($this,'link_rel_buffer_callback'));
        }

        public function link_rel_buffer_end() {
            ob_flush();
        } 

        public function amigoPerf_iframelazy_execute(){
            if($this->amigoPerf_iframelazy_opt == get_option($this->amigoPerf_iframelazy)) {

                // Inline amigolazy script at footer
                $this->amigoPerf_iframelazy_operation();

                //str replace
                if (!is_admin()) {
                    add_action('template_redirect', array($this,'link_rel_buffer_start'));
                    add_action('get_header', array($this,'link_rel_buffer_start'));
                    add_action('wp_footer', array($this,'link_rel_buffer_end'));
                }

            }
        }

        public function amigoPerf_menu(){ ?>
            <div class='amperf-container'>

                <div class= 'amperf-header'>
                    <h1> 
                        <span class="dashicons dashicons-buddicons-activity amigoperf_icon"></span><?php echo $this->PluginName?>
                        <span class="amigoperf_pluginversion"> <?php echo $this->PluginVersion ?></span> 
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
    $amigoPerfDefault -> amigoPerf_Default();
    $amigoPerfDefault -> amigoperf_hiddenField();
    $amigoPerfDefault -> amigoPerf_rqs_query('details');
    $amigoPerfDefault -> amigoPerf_rqs_operation(); //Remove Query Strings Operation
    $amigoPerfDefault -> amigoPerf_remoji_operation(); //Remove Emoji Operation
    $amigoPerfDefault -> amigoPerf_defer_operation(); //Defer parsing of JavaScript
    $amigoPerfDefault -> amigoPerf_iframelazy_execute(); //Iframe Lazyload
?>