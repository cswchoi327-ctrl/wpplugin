<?php
if (!defined('ABSPATH')) exit;

class SCM_Card_Post_Type {
    
    public static function register() {
        self::register_post_type();
        self::register_taxonomy();
        self::register_meta_boxes();
    }
    
    private static function register_post_type() {
        $labels = array(
            'name' => '지원금 카드',
            'singular_name' => '지원금 카드',
            'add_new' => '새 카드 추가',
            'add_new_item' => '새 지원금 카드 추가',
            'edit_item' => '카드 편집',
            'new_item' => '새 카드',
            'view_item' => '카드 보기',
            'search_items' => '카드 검색',
            'not_found' => '카드가 없습니다',
            'not_found_in_trash' => '휴지통에 카드가 없습니다',
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-money-alt',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'subsidy'),
        );
        
        register_post_type('subsidy_card', $args);
    }
    
    private static function register_taxonomy() {
        $labels = array(
            'name' => '카드 카테고리',
            'singular_name' => '카테고리',
            'search_items' => '카테고리 검색',
            'all_items' => '모든 카테고리',
            'edit_item' => '카테고리 편집',
            'update_item' => '카테고리 업데이트',
            'add_new_item' => '새 카테고리 추가',
            'new_item_name' => '새 카테고리 이름',
            'menu_name' => '카테고리',
        );
        
        register_taxonomy('card_category', 'subsidy_card', array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'card-category'),
        ));
    }
    
    private static function register_meta_boxes() {
        add_action('add_meta_boxes', function() {
            add_meta_box(
                'scm_card_details',
                '카드 상세 정보',
                array('SCM_Card_Post_Type', 'render_meta_box'),
                'subsidy_card',
                'normal',
                'high'
            );
        });
        
        add_action('save_post_subsidy_card', array('SCM_Card_Post_Type', 'save_meta_box'));
    }
    
    public static function render_meta_box($post) {
        wp_nonce_field('scm_card_meta', 'scm_card_meta_nonce');
        
        $amount = get_post_meta($post->ID, '_scm_amount', true);
        $amount_sub = get_post_meta($post->ID, '_scm_amount_sub', true);
        $target = get_post_meta($post->ID, '_scm_target', true);
        $period = get_post_meta($post->ID, '_scm_period', true);
        $link_url = get_post_meta($post->ID, '_scm_link_url', true);
        $is_featured = get_post_meta($post->ID, '_scm_is_featured', true);
        ?>
        <style>
            .scm-meta-field { margin-bottom: 20px; }
            .scm-meta-field label { 
                display: block; 
                font-weight: bold; 
                margin-bottom: 5px;
                color: #2271b1;
            }
            .scm-meta-field input[type="text"],
            .scm-meta-field input[type="url"] { 
                width: 100%; 
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .scm-meta-field input[type="text"]:focus,
            .scm-meta-field input[type="url"]:focus {
                border-color: #2271b1;
                outline: none;
                box-shadow: 0 0 0 1px #2271b1;
            }
            .scm-meta-field small { 
                color: #666; 
                font-style: italic;
            }
            .scm-featured-wrapper {
                background: #f0f9ff;
                padding: 15px;
                border-radius: 4px;
                border-left: 4px solid #2271b1;
            }
        </style>
        
        <div class="scm-meta-field">
            <label for="scm_amount">💰 금액/혜택 강조</label>
            <input type="text" id="scm_amount" name="scm_amount" value="<?php echo esc_attr($amount); ?>" placeholder="예: 최대 4.5% 금리" />
            <small>카드 상단에 크게 표시될 금액 정보</small>
        </div>
        
        <div class="scm-meta-field">
            <label for="scm_amount_sub">📝 부가 설명</label>
            <input type="text" id="scm_amount_sub" name="scm_amount_sub" value="<?php echo esc_attr($amount_sub); ?>" placeholder="예: 비과세 + 대출 우대" />
            <small>금액 아래에 표시될 부연 설명</small>
        </div>
        
        <div class="scm-meta-field">
            <label for="scm_target">👥 지원대상</label>
            <input type="text" id="scm_target" name="scm_target" value="<?php echo esc_attr($target); ?>" placeholder="예: 만 19~34세 청년" maxlength="20" />
            <small>⚠️ 반드시 공백 포함 20글자 이내로 입력</small>
        </div>
        
        <div class="scm-meta-field">
            <label for="scm_period">📅 신청시기</label>
            <input type="text" id="scm_period" name="scm_period" value="<?php echo esc_attr($period); ?>" placeholder="예: 상시, 매년 5월" />
        </div>
        
        <div class="scm-meta-field">
            <label for="scm_link_url">🔗 신청 링크 URL</label>
            <input type="url" id="scm_link_url" name="scm_link_url" value="<?php echo esc_url($link_url); ?>" placeholder="https://example.com" />
            <small>사용자가 클릭했을 때 이동할 URL (광고 링크 가능)</small>
        </div>
        
        <div class="scm-meta-field scm-featured-wrapper">
            <label>
                <input type="checkbox" name="scm_is_featured" value="1" <?php checked($is_featured, '1'); ?> />
                🔥 이 카드를 인기 카드로 표시 (첫 번째 카드에 적용)
            </label>
        </div>
        <?php
    }
    
    public static function save_meta_box($post_id) {
        if (!isset($_POST['scm_card_meta_nonce']) || 
            !wp_verify_nonce($_POST['scm_card_meta_nonce'], 'scm_card_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        $fields = array('amount', 'amount_sub', 'target', 'period', 'link_url');
        
        foreach ($fields as $field) {
            if (isset($_POST["scm_{$field}"])) {
                $value = sanitize_text_field($_POST["scm_{$field}"]);
                if ($field === 'link_url') {
                    $value = esc_url_raw($value);
                }
                update_post_meta($post_id, "_scm_{$field}", $value);
            }
        }
        
        $is_featured = isset($_POST['scm_is_featured']) ? '1' : '0';
        update_post_meta($post_id, '_scm_is_featured', $is_featured);
    }
}
