<?php
/**
 * @package amigo-performance
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
  die;
}
delete_option( 'amigoPerf_rqs' );
delete_option( 'amigoPerf_remoji' );
delete_option( 'amigoPerf_defer' );
delete_option( 'amigoPerf_iframelazy' );