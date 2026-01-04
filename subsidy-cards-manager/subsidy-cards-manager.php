<?php
/**
 * Plugin Name: 지원금 카드 매니저 Pro
 * Plugin URI: https://aros100.com
 * Description: 지원금 카드 자동 생성 및 광고 수익 최적화 올인원 플러그인
 * Version: 1.0.0
 * Author: 아로스
 * Author URI: https://aros100.com
 * License: GPL v2 or later
 * Text Domain: subsidy-cards
 */

if (!defined('ABSPATH')) exit;

define('SCM_VERSION', '1.0.0');
define('SCM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SCM_PLUGIN_URL', plugin_dir_url(__FILE__));

class Subsidy_Cards_Manager {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    private function load_dependencies() {
        require_once SCM_PLUGIN_DIR . 'includes/class-card-post-type.php';
        require_once SCM_PLUGIN_DIR . 'includes/class-ad-manager.php';
        require_once SCM_PLUGIN_DIR . 'includes/class-ai-generator.php';
        require_once SCM_PLUGIN_DIR . 'admin/class-admin-menu.php';
    }
    
    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_shortcode('subsidy_cards', array($this, 'render_cards_shortcode'));
        
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function register_post_types() {
        SCM_Card_Post_Type::register();
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style('scm-frontend', SCM_PLUGIN_URL . 'assets/css/frontend.css', array(), SCM_VERSION);
        wp_enqueue_script('scm-frontend', SCM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), SCM_VERSION, true);
        
        wp_localize_script('scm-frontend', 'scmData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('scm_nonce')
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'subsidy-cards') === false) return;
        
        wp_enqueue_style('scm-admin', SCM_PLUGIN_URL . 'assets/css/admin.css', array(), SCM_VERSION);
        wp_enqueue_script('scm-admin', SCM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), SCM_VERSION, true);
        
        wp_localize_script('scm-admin', 'scmAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('scm_admin_nonce'),
            'apiUrl' => 'https://api.anthropic.com/v1/messages'
        ));
    }
    
    public function render_cards_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => -1,
            'category' => '',
            'orderby' => 'date',
            'order' => 'DESC'
        ), $atts);
        
        $args = array(
            'post_type' => 'subsidy_card',
            'posts_per_page' => $atts['count'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish'
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'card_category',
                    'field' => 'slug',
                    'terms' => $atts['category']
                )
            );
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        include SCM_PLUGIN_DIR . 'templates/card-display.php';
        return ob_get_clean();
    }
    
    public function activate() {
        $this->register_post_types();
        flush_rewrite_rules();
        
        // 기본 옵션 설정
        add_option('scm_ad_settings', array(
            'taboola_enabled' => false,
            'dable_enabled' => false,
            'anchor_ad_enabled' => false,
            'interstitial_enabled' => false,
            'ad_density' => 'medium'
        ));
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize plugin
function scm_init() {
    return Subsidy_Cards_Manager::get_instance();
}
add_action('plugins_loaded', 'scm_init');
