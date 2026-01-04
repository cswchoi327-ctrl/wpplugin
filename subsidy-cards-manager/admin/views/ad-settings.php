<?php
if (!defined('ABSPATH')) exit;

$settings = get_option('scm_ad_settings', array());
?>

<div class="wrap scm-ad-settings">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-megaphone" style="font-size:30px;"></span>
        광고 설정
    </h1>
    
    <hr class="wp-header-end">
    
    <form id="scm-ad-settings-form" style="max-width:1200px;margin:30px 0;">
        
        <!-- 타뷸라 설정 -->
        <div class="scm-ad-section" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45' fill='%2300a4e4'/%3E%3C/svg%3E" width="30" height="30" style="border-radius:50%;" />
                    타뷸라 (Taboola)
                </h2>
                <label class="scm-switch">
                    <input type="checkbox" name="taboola_enabled" value="1" <?php checked(!empty($settings['taboola_enabled'])); ?> />
                    <span class="scm-slider"></span>
                </label>
            </div>
            
            <p style="color:#64748b;margin-bottom:20px;">타뷸라 네이티브 광고를 콘텐츠에 자동으로 삽입합니다.</p>
            
            <div class="scm-form-group">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    Publisher ID
                </label>
                <input type="text" name="taboola_publisher_id" value="<?php echo esc_attr($settings['taboola_publisher_id'] ?? ''); ?>" placeholder="your-publisher-id" style="width:100%;padding:10px;border:2px solid #e5e7eb;border-radius:8px;" />
                <small style="color:#64748b;display:block;margin-top:5px;">
                    타뷸라 대시보드에서 확인할 수 있습니다.
                </small>
            </div>
        </div>
        
        <!-- 데이블 설정 -->
        <div class="scm-ad-section" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45' fill='%23ff6b6b'/%3E%3C/svg%3E" width="30" height="30" style="border-radius:50%;" />
                    데이블 (Dable)
                </h2>
                <label class="scm-switch">
                    <input type="checkbox" name="dable_enabled" value="1" <?php checked(!empty($settings['dable_enabled'])); ?> />
                    <span class="scm-slider"></span>
                </label>
            </div>
            
            <p style="color:#64748b;margin-bottom:20px;">데이블 추천 위젯을 자동으로 표시합니다.</p>
            
            <div class="scm-form-group">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    Service ID
                </label>
                <input type="text" name="dable_service_id" value="<?php echo esc_attr($settings['dable_service_id'] ?? ''); ?>" placeholder="your-service-id" style="width:100%;padding:10px;border:2px solid #e5e7eb;border-radius:8px;" />
                <small style="color:#64748b;display:block;margin-top:5px;">
                    데이블 관리자 페이지에서 확인할 수 있습니다.
                </small>
            </div>
        </div>
        
        <!-- 앵커 광고 -->
        <div class="scm-ad-section" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                    📌 앵커 광고 (하단 고정)
                </h2>
                <label class="scm-switch">
                    <input type="checkbox" name="anchor_ad_enabled" value="1" <?php checked(!empty($settings['anchor_ad_enabled'])); ?> />
                    <span class="scm-slider"></span>
                </label>
            </div>
            
            <p style="color:#64748b;margin-bottom:20px;">화면 하단에 고정되는 광고를 표시합니다. 사용자가 닫기 버튼으로 닫을 수 있습니다.</p>
            
            <div class="scm-form-group">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    광고 코드
                </label>
                <textarea name="anchor_ad_code" rows="6" placeholder="<script>...</script> 또는 광고 코드" style="width:100%;padding:10px;border:2px solid #e5e7eb;border-radius:8px;font-family:monospace;font-size:13px;"><?php echo esc_textarea($settings['anchor_ad_code'] ?? ''); ?></textarea>
            </div>
        </div>
        
        <!-- 전면 광고 -->
        <div class="scm-ad-section" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                    🖼️ 전면 광고 (Interstitial)
                </h2>
                <label class="scm-switch">
                    <input type="checkbox" name="interstitial_enabled" value="1" <?php checked(!empty($settings['interstitial_enabled'])); ?> />
                    <span class="scm-slider"></span>
                </label>
            </div>
            
            <p style="color:#64748b;margin-bottom:20px;">일정 페이지 조회 후 전체 화면 광고를 표시합니다.</p>
            
            <div class="scm-form-group" style="margin-bottom:20px;">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    표시 빈도 (페이지 뷰마다)
                </label>
                <select name="interstitial_frequency" style="padding:10px;border:2px solid #e5e7eb;border-radius:8px;width:200px;">
                    <option value="1" <?php selected($settings['interstitial_frequency'] ?? 3, 1); ?>>매 페이지마다</option>
                    <option value="2" <?php selected($settings['interstitial_frequency'] ?? 3, 2); ?>>2페이지마다</option>
                    <option value="3" <?php selected($settings['interstitial_frequency'] ?? 3, 3); ?>>3페이지마다</option>
                    <option value="5" <?php selected($settings['interstitial_frequency'] ?? 3, 5); ?>>5페이지마다</option>
                    <option value="10" <?php selected($settings['interstitial_frequency'] ?? 3, 10); ?>>10페이지마다</option>
                </select>
                <small style="color:#64748b;display:block;margin-top:5px;">
                    ⚠️ 너무 자주 표시하면 사용자 경험이 저하될 수 있습니다.
                </small>
            </div>
            
            <div class="scm-form-group">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    광고 코드
                </label>
                <textarea name="interstitial_code" rows="6" placeholder="<script>...</script> 또는 광고 코드" style="width:100%;padding:10px;border:2px solid #e5e7eb;border-radius:8px;font-family:monospace;font-size:13px;"><?php echo esc_textarea($settings['interstitial_code'] ?? ''); ?></textarea>
            </div>
        </div>
        
        <!-- 콘텐츠 내 광고 -->
        <div class="scm-ad-section" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h2 style="margin:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                    📄 콘텐츠 내 광고
                </h2>
                <label class="scm-switch">
                    <input type="checkbox" name="in_content_ad_enabled" value="1" <?php checked(!empty($settings['in_content_ad_enabled'])); ?> />
                    <span class="scm-slider"></span>
                </label>
            </div>
            
            <p style="color:#64748b;margin-bottom:20px;">본문 중간에 자동으로 광고를 삽입합니다. (3번째 문단 뒤)</p>
            
            <div class="scm-form-group">
                <label style="display:block;font-weight:bold;margin-bottom:8px;color:#1e3a5f;">
                    광고 코드
                </label>
                <textarea name="in_content_ad_code" rows="6" placeholder="<script>...</script> 또는 광고 코드" style="width:100%;padding:10px;border:2px solid #e5e7eb;border-radius:8px;font-family:monospace;font-size:13px;"><?php echo esc_textarea($settings['in_content_ad_code'] ?? ''); ?></textarea>
            </div>
        </div>
        
        <!-- 광고 밀도 설정 -->
        <div class="scm-ad-section" style="background:#fffbeb;padding:25px;border-radius:12px;border-left:4px solid #f59e0b;margin-bottom:30px;">
            <h3 style="margin-top:0;color:#92400e;">⚙️ 광고 밀도 설정</h3>
            <p style="color:#78350f;margin-bottom:20px;">수익과 사용자 경험의 균형을 조절합니다.</p>
            
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">
                <label style="display:block;padding:15px;background:white;border:2px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:all 0.3s;" class="ad-density-option">
                    <input type="radio" name="ad_density" value="low" <?php checked($settings['ad_density'] ?? 'medium', 'low'); ?> style="margin-right:8px;" />
                    <strong>낮음</strong><br>
                    <small style="color:#64748b;">사용자 경험 우선</small>
                </label>
                <label style="display:block;padding:15px;background:white;border:2px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:all 0.3s;" class="ad-density-option">
                    <input type="radio" name="ad_density" value="medium" <?php checked($settings['ad_density'] ?? 'medium', 'medium'); ?> style="margin-right:8px;" />
                    <strong>중간</strong><br>
                    <small style="color:#64748b;">균형잡힌 설정 (권장)</small>
                </label>
                <label style="display:block;padding:15px;background:white;border:2px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:all 0.3s;" class="ad-density-option">
                    <input type="radio" name="ad_density" value="high" <?php checked($settings['ad_density'] ?? 'medium', 'high'); ?> style="margin-right:8px;" />
                    <strong>높음</strong><br>
                    <small style="color:#64748b;">수익 최대화</small>
                </label>
            </div>
        </div>
        
        <!-- 저장 버튼 -->
        <div style="text-align:center;">
            <button type="submit" class="button button-primary button-hero" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none;padding:15px 60px;font-size:18px;border-radius:8px;cursor:pointer;">
                💾 설정 저장
            </button>
        </div>
        
        <div id="scm-save-result" style="margin-top:20px;display:none;"></div>
    </form>
</div>

<style>
/* 스위치 스타일 */
.scm-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.scm-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.scm-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.scm-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.scm-switch input:checked + .scm-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.scm-switch input:checked + .scm-slider:before {
    transform: translateX(26px);
}

/* 광고 밀도 옵션 */
.ad-density-option:has(input:checked) {
    border-color: #667eea !important;
    background: #f0f9ff !important;
}

.ad-density-option:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.scm-ad-settings input:focus,
.scm-ad-settings textarea:focus,
.scm-ad-settings select:focus {
    border-color: #667eea !important;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#scm-ad-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<span class="scm-loading"></span>저장 중...');
        $('#scm-save-result').hide();
        
        $.ajax({
            url: scmAdmin.ajaxurl,
            method: 'POST',
            data: $(this).serialize() + '&action=scm_save_ad_settings&nonce=' + scmAdmin.nonce,
            success: function(response) {
                if (response.success) {
                    $('#scm-save-result').html(
                        '<div style="background:#dcfce7;border-left:4px solid #16a34a;padding:15px;border-radius:8px;text-align:center;"><strong style="color:#166534;">✅ 설정이 저장되었습니다!</strong></div>'
                    ).fadeIn();
                } else {
                    $('#scm-save-result').html(
                        '<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:15px;border-radius:8px;text-align:center;"><strong style="color:#991b1b;">❌ 저장에 실패했습니다</strong></div>'
                    ).fadeIn();
                }
            },
            error: function() {
                $('#scm-save-result').html(
                    '<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:15px;border-radius:8px;text-align:center;"><strong style="color:#991b1b;">❌ 서버 오류가 발생했습니다</strong></div>'
                ).fadeIn();
            },
            complete: function() {
                $btn.prop('disabled', false).html('💾 설정 저장');
                
                setTimeout(function() {
                    $('#scm-save-result').fadeOut();
                }, 3000);
            }
        });
    });
});
</script>
