<?php
if (!defined('ABSPATH')) exit;

class SCM_Ad_Manager {
    
    private static $instance = null;
    private $settings;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->settings = get_option('scm_ad_settings', array());
        $this->init_hooks();
    }
    
    private function init_hooks() {
        add_action('wp_footer', array($this, 'render_ads'));
        add_action('wp_ajax_scm_save_ad_settings', array($this, 'save_settings'));
    }
    
    public function render_ads() {
        // 타뷸라 광고
        if ($this->is_enabled('taboola')) {
            $this->render_taboola();
        }
        
        // 데이블 광고
        if ($this->is_enabled('dable')) {
            $this->render_dable();
        }
        
        // 앵커 광고
        if ($this->is_enabled('anchor_ad')) {
            $this->render_anchor_ad();
        }
        
        // 전면 광고
        if ($this->is_enabled('interstitial')) {
            $this->render_interstitial();
        }
    }
    
    private function is_enabled($type) {
        return !empty($this->settings["{$type}_enabled"]);
    }
    
    private function render_taboola() {
        $publisher_id = $this->get_setting('taboola_publisher_id');
        if (empty($publisher_id)) return;
        
        ?>
        <!-- Taboola 스크립트 -->
        <script type="text/javascript">
            window._taboola = window._taboola || [];
            _taboola.push({article:'auto'});
            !function (e, f, u, i) {
                if (!document.getElementById(i)){
                    e.async = 1;
                    e.src = u;
                    e.id = i;
                    f.parentNode.insertBefore(e, f);
                }
            }(document.createElement('script'),
            document.getElementsByTagName('script')[0],
            '//cdn.taboola.com/libtrc/<?php echo esc_js($publisher_id); ?>/loader.js',
            'tb_loader_script');
        </script>
        <?php
    }
    
    private function render_dable() {
        $service_id = $this->get_setting('dable_service_id');
        if (empty($service_id)) return;
        
        ?>
        <!-- Dable 스크립트 -->
        <script>
            (function(d,a,b,l,e,_) {
                d[b]=d[b]||function(){(d[b].q=d[b].q||[]).push(arguments)};
                e=a.createElement(l);e.async=1;e.charset='utf-8';
                e.src='//static.dable.io/dist/plugin.min.js';
                _=a.getElementsByTagName(l)[0];_.parentNode.insertBefore(e,_);
            })(window,document,'dable','script');
            dable('setService', '<?php echo esc_js($service_id); ?>');
            dable('sendLogOnce');
        </script>
        <?php
    }
    
    private function render_anchor_ad() {
        $anchor_code = $this->get_setting('anchor_ad_code');
        if (empty($anchor_code)) return;
        
        ?>
        <!-- 앵커 광고 -->
        <div id="scm-anchor-ad" style="position:fixed;bottom:0;left:0;width:100%;z-index:9998;background:#fff;box-shadow:0 -2px 10px rgba(0,0,0,0.1);">
            <?php echo $anchor_code; ?>
            <button id="scm-close-anchor" style="position:absolute;top:5px;right:5px;background:#333;color:#fff;border:none;padding:5px 10px;cursor:pointer;border-radius:3px;font-size:12px;">닫기</button>
        </div>
        <script>
        (function($) {
            $('#scm-close-anchor').on('click', function() {
                $('#scm-anchor-ad').fadeOut();
                sessionStorage.setItem('scm_anchor_closed', '1');
            });
            
            if (sessionStorage.getItem('scm_anchor_closed') === '1') {
                $('#scm-anchor-ad').hide();
            }
        })(jQuery);
        </script>
        <?php
    }
    
    private function render_interstitial() {
        $interstitial_code = $this->get_setting('interstitial_code');
        $frequency = $this->get_setting('interstitial_frequency', 3);
        
        if (empty($interstitial_code)) return;
        
        ?>
        <!-- 전면 광고 -->
        <div id="scm-interstitial-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;justify-content:center;align-items:center;">
            <div style="position:relative;max-width:90%;max-height:90%;background:#fff;padding:20px;border-radius:10px;">
                <button id="scm-close-interstitial" style="position:absolute;top:10px;right:10px;background:#333;color:#fff;border:none;padding:10px 15px;cursor:pointer;border-radius:5px;font-weight:bold;">✕ 닫기</button>
                <div id="scm-interstitial-content">
                    <?php echo $interstitial_code; ?>
                </div>
            </div>
        </div>
        <script>
        (function($) {
            var pageViews = parseInt(sessionStorage.getItem('scm_page_views') || '0');
            pageViews++;
            sessionStorage.setItem('scm_page_views', pageViews);
            
            var frequency = <?php echo intval($frequency); ?>;
            var lastShown = sessionStorage.getItem('scm_interstitial_shown');
            
            if (pageViews % frequency === 0 && !lastShown) {
                setTimeout(function() {
                    $('#scm-interstitial-overlay').css('display', 'flex').hide().fadeIn();
                    sessionStorage.setItem('scm_interstitial_shown', '1');
                }, 2000);
            }
            
            $('#scm-close-interstitial').on('click', function() {
                $('#scm-interstitial-overlay').fadeOut();
                sessionStorage.removeItem('scm_interstitial_shown');
            });
        })(jQuery);
        </script>
        <?php
    }
    
    public function insert_in_content_ad($content, $position = 3) {
        if (!is_single() || !$this->is_enabled('in_content_ad')) {
            return $content;
        }
        
        $ad_code = $this->get_setting('in_content_ad_code');
        if (empty($ad_code)) return $content;
        
        $paragraphs = explode('</p>', $content);
        $total = count($paragraphs);
        
        if ($total > $position) {
            $ad_wrapper = '<div class="scm-in-content-ad" style="margin:30px 0;padding:20px;background:#f9fafb;border-radius:10px;text-align:center;">';
            $ad_wrapper .= '<div style="font-size:12px;color:#999;margin-bottom:10px;">Sponsored</div>';
            $ad_wrapper .= $ad_code;
            $ad_wrapper .= '</div>';
            
            $paragraphs[$position] .= $ad_wrapper;
        }
        
        return implode('</p>', $paragraphs);
    }
    
    private function get_setting($key, $default = '') {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
    
    public function save_settings() {
        check_ajax_referer('scm_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }
        
        $settings = array(
            'taboola_enabled' => isset($_POST['taboola_enabled']),
            'taboola_publisher_id' => sanitize_text_field($_POST['taboola_publisher_id'] ?? ''),
            'dable_enabled' => isset($_POST['dable_enabled']),
            'dable_service_id' => sanitize_text_field($_POST['dable_service_id'] ?? ''),
            'anchor_ad_enabled' => isset($_POST['anchor_ad_enabled']),
            'anchor_ad_code' => wp_kses_post($_POST['anchor_ad_code'] ?? ''),
            'interstitial_enabled' => isset($_POST['interstitial_enabled']),
            'interstitial_code' => wp_kses_post($_POST['interstitial_code'] ?? ''),
            'interstitial_frequency' => intval($_POST['interstitial_frequency'] ?? 3),
            'in_content_ad_enabled' => isset($_POST['in_content_ad_enabled']),
            'in_content_ad_code' => wp_kses_post($_POST['in_content_ad_code'] ?? ''),
            'ad_density' => sanitize_text_field($_POST['ad_density'] ?? 'medium'),
        );
        
        update_option('scm_ad_settings', $settings);
        wp_send_json_success('설정이 저장되었습니다.');
    }
}

// 콘텐츠 내 광고 삽입
add_filter('the_content', function($content) {
    $ad_manager = SCM_Ad_Manager::get_instance();
    return $ad_manager->insert_in_content_ad($content);
});
