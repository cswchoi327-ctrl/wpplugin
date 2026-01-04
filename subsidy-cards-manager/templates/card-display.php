<?php
if (!defined('ABSPATH')) exit;

// 광고 관리자 인스턴스
$ad_manager = SCM_Ad_Manager::get_instance();
$ad_settings = get_option('scm_ad_settings', array());
?>

<div class="scm-cards-wrapper">
    
    <?php if ($query->have_posts()): ?>
        
        <!-- 히어로 섹션 -->
        <div class="scm-hero-section">
            <div class="scm-hero-content">
                <span class="scm-hero-urgent">🔥 신청마감 D-3일</span>
                <p class="scm-hero-sub">숨은 지원금 1분만에 찾기!</p>
                <h2 class="scm-hero-title">
                    나의 <span class="scm-hero-highlight">숨은 지원금</span> 찾기
                </h2>
                <p class="scm-hero-amount">신청자 <strong>1인 평균 127만원</strong> 수령</p>
                
                <a class="scm-hero-cta" href="#scm-cards">
                    30초만에 내 지원금 확인 <span class="scm-cta-arrow">→</span>
                </a>
                
                <div class="scm-hero-trust">
                    <span class="scm-trust-item">✓ 무료 조회</span>
                    <span class="scm-trust-item">✓ 30초 완료</span>
                    <span class="scm-trust-item">✓ 개인정보 보호</span>
                </div>
                
                <div class="scm-hero-notice">
                    <div class="scm-notice-title">💡 신청 안하면 못 받아요</div>
                    <p class="scm-notice-desc">대한민국 92%가 놓치고 있는 정부 지원금, 지금 확인하고 혜택 놓치지 마세요!</p>
                </div>
            </div>
        </div>
        
        <!-- 정보 박스 -->
        <div class="scm-info-box">
            <div class="scm-info-box-header">
                <span class="scm-info-box-icon">🏷️</span>
                <span class="scm-info-box-title">신청 안하면 절대 못 받아요</span>
            </div>
            <div class="scm-info-box-amount">1인 평균 127만원 환급</div>
            <p class="scm-info-box-desc">대한민국 92%가 놓치고 있는 정부 지원금! 지금 확인하고 혜택 놓치지 마세요.</p>
        </div>
        
        <!-- 카드 그리드 -->
        <div class="scm-info-card-grid" id="scm-cards">
            <?php 
            $card_index = 0;
            while ($query->have_posts()): 
                $query->the_post();
                $card_index++;
                
                $amount = get_post_meta(get_the_ID(), '_scm_amount', true);
                $amount_sub = get_post_meta(get_the_ID(), '_scm_amount_sub', true);
                $target = get_post_meta(get_the_ID(), '_scm_target', true);
                $period = get_post_meta(get_the_ID(), '_scm_period', true);
                $link_url = get_post_meta(get_the_ID(), '_scm_link_url', true);
                $is_featured = get_post_meta(get_the_ID(), '_scm_is_featured', true);
                
                if (empty($link_url)) {
                    $link_url = get_permalink();
                }
                
                $featured_class = ($is_featured === '1') ? ' scm-card-featured' : '';
                
                // 광고 삽입 (1번째, 4번째, 7번째 카드 전에)
                if (in_array($card_index, [1, 4, 7]) && !empty($ad_settings['in_content_ad_enabled'])):
                ?>
                    <div class="scm-ad-card">
                        <div style="display:flex;justify-content:center;width:100%;">
                            <?php echo $ad_settings['in_content_ad_code'] ?? ''; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- 카드 -->
                <a class="scm-info-card<?php echo $featured_class; ?>" href="<?php echo esc_url($link_url); ?>">
                    <div class="scm-info-card-highlight">
                        <?php if ($is_featured === '1'): ?>
                            <span class="scm-info-card-badge">🔥 인기</span>
                        <?php endif; ?>
                        <div class="scm-info-card-amount"><?php echo esc_html($amount); ?></div>
                        <div class="scm-info-card-amount-sub"><?php echo esc_html($amount_sub); ?></div>
                    </div>
                    <div class="scm-info-card-content">
                        <h3 class="scm-info-card-title"><?php the_title(); ?></h3>
                        <p class="scm-info-card-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <div class="scm-info-card-details">
                            <div class="scm-info-card-row">
                                <span class="scm-info-card-label">지원대상</span>
                                <span class="scm-info-card-value"><?php echo esc_html($target); ?></span>
                            </div>
                            <div class="scm-info-card-row">
                                <span class="scm-info-card-label">신청시기</span>
                                <span class="scm-info-card-value"><?php echo esc_html($period); ?></span>
                            </div>
                        </div>
                        <div class="scm-info-card-btn">
                            지금 바로 신청하기 <span class="scm-btn-arrow">→</span>
                        </div>
                    </div>
                </a>
                
            <?php endwhile; ?>
        </div>
        
        <?php wp_reset_postdata(); ?>
        
    <?php else: ?>
        
        <div class="scm-no-cards" style="text-align:center;padding:60px 20px;background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <div style="font-size:48px;margin-bottom:20px;">📭</div>
            <h3 style="color:#64748b;margin-bottom:10px;">아직 카드가 없습니다</h3>
            <p style="color:#94a3b8;">관리자 페이지에서 카드를 추가해주세요.</p>
        </div>
        
    <?php endif; ?>
    
</div>

<!-- 이탈 방지 팝업 -->
<div class="scm-exit-popup-overlay" id="scmExitPopup">
    <div class="scm-exit-popup">
        <div class="scm-exit-popup-title">🎁 잠깐! 놓치신 혜택이 있어요</div>
        <div class="scm-exit-popup-desc">
            지금 확인 안 하면<br>
            <strong>최대 300만원</strong> 지원금을 못 받을 수 있어요!
        </div>
        <button class="scm-exit-popup-btn" onclick="scmClosePopupAndScroll()">
            내 지원금 확인하기 →
        </button>
        <button class="scm-exit-popup-close" onclick="scmClosePopupNotNow()">
            다음에 할게요
        </button>
    </div>
</div>

<script>
(function() {
    var popupShown = sessionStorage.getItem('scm_exit_popup_shown');
    var closeCount = parseInt(sessionStorage.getItem('scm_exit_popup_close_count')) || 0;
    var scrollTriggered = false;
    
    function showPopup() {
        document.getElementById('scmExitPopup').style.display = 'flex';
    }
    
    function closePopup() {
        document.getElementById('scmExitPopup').style.display = 'none';
    }
    
    window.scmClosePopupAndScroll = function() {
        closePopup();
        var hero = document.querySelector('.scm-hero-section');
        if (hero) {
            hero.scrollIntoView({ behavior: 'smooth' });
        }
    };
    
    window.scmClosePopupNotNow = function() {
        closePopup();
        popupShown = true;
        closeCount++;
        sessionStorage.setItem('scm_exit_popup_shown', 'true');
        sessionStorage.setItem('scm_exit_popup_close_count', closeCount);
    };
    
    // PC: 마우스 이탈 감지
    document.addEventListener('mouseout', function(e) {
        if (e.clientY < 0 && !popupShown && closeCount < 2) {
            showPopup();
        }
    });
    
    // PC + 모바일: 뒤로가기 감지
    history.pushState(null, '', location.href);
    window.addEventListener('popstate', function() {
        if (closeCount < 2) {
            showPopup();
        }
        history.pushState(null, '', location.href);
    });
    
    // 모바일: 스크롤 60% 도달 시
    window.addEventListener('scroll', function() {
        var h = document.body.scrollHeight - window.innerHeight;
        var percent = (window.scrollY / h) * 100;
        
        if (percent > 60 && !popupShown && !scrollTriggered && closeCount < 2) {
            showPopup();
            scrollTriggered = true;
        }
    });
})();
</script>
