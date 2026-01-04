<?php
if (!defined('ABSPATH')) exit;

class SCM_Admin_Menu {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
    }
    
    public function add_menu_pages() {
        add_menu_page(
            '지원금 카드 관리',
            '지원금 카드',
            'manage_options',
            'subsidy-cards-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-money-alt',
            30
        );
        
        add_submenu_page(
            'subsidy-cards-dashboard',
            '대시보드',
            '대시보드',
            'manage_options',
            'subsidy-cards-dashboard',
            array($this, 'render_dashboard')
        );
        
        add_submenu_page(
            'subsidy-cards-dashboard',
            'AI 카드 생성',
            'AI 카드 생성',
            'edit_posts',
            'subsidy-cards-generator',
            array($this, 'render_generator')
        );
        
        add_submenu_page(
            'subsidy-cards-dashboard',
            '광고 설정',
            '광고 설정',
            'manage_options',
            'subsidy-cards-ads',
            array($this, 'render_ad_settings')
        );
    }
    
    public function render_dashboard() {
        include SCM_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function render_generator() {
        include SCM_PLUGIN_DIR . 'admin/views/card-editor.php';
    }
    
    public function render_ad_settings() {
        include SCM_PLUGIN_DIR . 'admin/views/ad-settings.php';
    }
}

new SCM_Admin_Menu();
