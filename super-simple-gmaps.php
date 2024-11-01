<?php

/**
 * Plugin Name: Super Simple Google Maps
 * Description: A super simple Google Maps Plugin to extend wordpress with a Google Map.
 * Plugin URI: http://www.seiboldsoft.de
 * Author: Emanuel Seibold
 * Author URI: http://www.seiboldsoft.de
 * Version: 1.0
 * Text Domain: super-simple-gmaps
 * License: GPL2
 */
?>
<?php

/*
  Copyright 2016 Emanuel Seibold (email : wordpress AT seiboldsoft DOT de)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 * Define versions and pathes
 * 
 */
define('SSG_VERSION', '1.0');
define('SSG_PATH', dirname(__FILE__));
define('SSG_FOLDER', basename(SSG_PATH));
define('SSG_URL', plugins_url() . '/' . SSG_FOLDER);

/**
 * 
 * The plugin base class - the root of all WP goods!
 * 
 * @author Emanuel Seibold
 *
 */
class SSG_Plugin_Base {

    /**
     * 
     * Assign everything as a call from within the constructor
     */
    public function __construct() {



        add_action('wp_enqueue_scripts', array($this, 'ssg_add_CSS'));
        add_action('wp_enqueue_scripts', array($this, 'ssg_add_JS'));

        add_action('admin_enqueue_scripts', array($this, 'ssg_add_admin_JS'));
        add_action('admin_enqueue_scripts', array($this, 'ssg_add_admin_CSS'));

        add_action('admin_init', array($this, 'ssg_admin_init'), 6);

        add_action('plugins_loaded', array($this, 'ssg_add_textdomain'));
        add_action('init', array($this, 'register'));


        // Register activation and deactivation hooks
        register_activation_hook(__FILE__, 'ssg_on_activate_callback');
        register_deactivation_hook(__FILE__, 'ssg_on_deactivate_callback');
    }

    public function register() {
        add_shortcode('simple-gmaps', array($this, 'ssg_shortcode_body'));
    }

    /**
     *
     * Adding JavaScript scripts for the admin pages only
     *
     * Loading existing scripts from wp-includes or adding custom ones
     *
     */
    public function ssg_add_admin_JS($hook) {

        wp_enqueue_script('jquery');
        wp_register_script('super-simple-gmaps-admin', SSG_URL . '/js/super-simple-gmaps-admin.js', array('jquery'), '1.0', true);
        wp_enqueue_script('super-simple-gmaps-admin');
    }

    /**
     *
     * Adding CSS  for the admin pages only
     *
     * Loading existing CSS from wp-includes or adding custom ones
     *
     */
    public function ssg_add_admin_CSS($hook) {
        wp_register_style('super-simple-gmaps-admin-style', SSG_URL . '/css/super-simple-gmaps-admin.css', array(), '1.0', 'screen');
        wp_enqueue_style('super-simple-gmaps-admin-style');
    }

    /**
     * Add JS Scripts
     */
    function ssg_add_JS() {
        wp_enqueue_script('super-simple-gmaps-js', 'http://maps.google.com/maps/api/js#asyncload', null, null, false);
        wp_enqueue_script('super-simple-google-maps', SSG_URL . '/js/super-simple-gmaps.js#asyncload', array('jquery'), '1.0', true);
    }

    /**
     * 
     * Add CSS styles
     * 
     */
    public function ssg_add_CSS() {
        wp_register_style('super-simple-gmaps-style', SSG_URL . '/css/super-simple-gmaps.css', array(), '1.0', 'screen');
        wp_enqueue_style('super-simple-gmaps-style');
    }

    function ssg_admin_init($hook) {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
            return;
        add_filter("mce_external_plugins", array($this, 'ssg_register_tinymce_plugin'));
        add_filter('mce_buttons', array($this, 'ssg_add_tinymce_button'));
    }

    function ssg_register_tinymce_plugin($plugin_array) {
        $plugin_array['simple_gmaps_button'] = SSG_URL . '/js/super-simple-gmaps-admin.js';
        return $plugin_array;
    }

    function ssg_add_tinymce_button($buttons) {
        $buttons[] = "simple_gmaps_button";
        return $buttons;
    }

    /**
     * Returns the content of the Google Maps Plugin
     * @param array $attr arguments passed to array
     * @param string $content optional, could be used for a content to be wrapped
     */
    public function ssg_shortcode_body($attr, $content = null) {

        $pull_atts = shortcode_atts(array('id' => 'id', 'lat' => '0', 'long' => '0', 'name' => 'Beautymatters'), $attr);
        ob_start();
        include(SSG_PATH. "/templates/default-responsive.php");
        $var = trim(ob_get_contents());
        ob_get_clean();

        wp_localize_script('super-simple-google-maps', 'jsparams', array('lat' => $pull_atts["lat"], 'long' => $pull_atts["long"], 'gname' => $pull_atts['name']));

        return preg_replace('/^\s+|\n|\r|\s+$/m', '', $var);
    }

    /**
     * Add textdomain for plugin
     */
    public function ssg_add_textdomain() {
        $lang_dir = basename(dirname(__FILE__)) . '/lang/';
        load_plugin_textdomain('super-simple-gmaps', false, $lang_dir);
    }

}

/**
 * Register activation hook
 *
 */
function ssg_on_activate_callback() {

    flush_rewrite_rules();
}

/**
 * Register deactivation hook
 *
 */
function ssg_on_deactivate_callback() {
    flush_rewrite_rules();
}

// Initialize everything

$ssg_plugin_base = new SSG_Plugin_Base();




