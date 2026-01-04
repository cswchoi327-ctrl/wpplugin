<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap scm-card-editor">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-customizer" style="font-size:30px;"></span>
        AI 카드 자동 생성
    </h1>
    
    <hr class="wp-header-end">
    
    <div class="scm-editor-container" style="max-width:1200px;margin:30px 0;">
        
        <!-- 단일 생성 섹션 -->
        <div class="scm-single-generator" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <h2 style="margin-top:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                <span style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;">1</span>
                단일 카드 생성
            </h2>
            <p style="color:#64748b;margin-bottom:25px;">키워드만 입력하면 AI가 자동으로 완성도 높은 지원금 카드를 생성합니다.</p>
            
            <div class="scm-form-group" style="margin-bottom:20px;">
                <label style="display:block;font-weight:bold;margin-bottom:10px;color:#1e3a5f;">
                    🏷️ 키워드 입력 <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" id="scm-single-keyword" placeholder="예: 청년도약계좌" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:16px;" />
                <small style="color:#64748b;display:block;margin-top:8px;">
                    💡 정확한 정책/지원금 이름을 입력하면 더 정확한 결과를 얻을 수 있습니다.
                </small>
            </div>
            
            <button id="scm-generate-single" class="button button-primary button-hero" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none;padding:15px 40px;font-size:16px;border-radius:8px;cursor:pointer;">
                ✨ AI로 카드 생성하기
            </button>
            
            <div id="scm-single-result" style="margin-top:20px;display:none;">
                <div style="background:#dcfce7;border-left:4px solid #16a34a;padding:15px;border-radius:8px;">
                    <strong style="color:#166534;">✅ 생성 완료!</strong>
                    <p style="color:#15803d;margin:10px 0 0 0;" id="scm-single-message"></p>
                </div>
            </div>
            
            <div id="scm-single-error" style="margin-top:20px;display:none;">
                <div style="background:#fee2e2;border-left:4px solid #ef4444;padding:15px;border-radius:8px;">
                    <strong style="color:#991b1b;">❌ 오류 발생</strong>
                    <p style="color:#b91c1c;margin:10px 0 0 0;" id="scm-single-error-message"></p>
                </div>
            </div>
        </div>
        
        <!-- 대량 생성 섹션 -->
        <div class="scm-bulk-generator" style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
            <h2 style="margin-top:0;color:#1e3a5f;display:flex;align-items:center;gap:10px;">
                <span style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;">2</span>
                대량 카드 생성
            </h2>
            <p style="color:#64748b;margin-bottom:25px;">여러 키워드를 한 번에 입력하여 대량으로 카드를 생성하세요.</p>
            
            <div class="scm-form-group" style="margin-bottom:20px;">
                <label style="display:block;font-weight:bold;margin-bottom:10px;color:#1e3a5f;">
                    📝 키워드 목록 (최대 20개) <span style="color:#ef4444;">*</span>
                </label>
                <div id="scm-keyword-inputs" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:15px;">
                    <?php for ($i = 1; $i <= 9; $i++): ?>
                        <input type="text" class="scm-bulk-keyword" placeholder="키워드 <?php echo $i; ?>" style="padding:10px;border:2px solid #e5e7eb;border-radius:8px;" />
                    <?php endfor; ?>
                </div>
                <button id="scm-add-keyword-field" class="button" style="margin-top:15px;">
                    ➕ 입력칸 추가
                </button>
            </div>
            
            <button id="scm-generate-bulk" class="button button-primary button-hero" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border:none;padding:15px 40px;font-size:16px;border-radius:8px;cursor:pointer;">
                🚀 대량 생성 시작
            </button>
            
            <div id="scm-bulk-progress" style="margin-top:20px;display:none;">
                <div style="background:#f0f9ff;padding:20px;border-radius:8px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                        <strong style="color:#1e3a5f;">생성 중...</strong>
                        <span id="scm-progress-text" style="color:#64748b;">0 / 0</span>
                    </div>
                    <div style="background:#e5e7eb;height:10px;border-radius:10px;overflow:hidden;">
                        <div id="scm-progress-bar" style="background:linear-gradient(90deg,#667eea,#764ba2);height:100%;width:0%;transition:width 0.3s;"></div>
                    </div>
                </div>
            </div>
            
            <div id="scm-bulk-result" style="margin-top:20px;display:none;">
                <div style="background:#dcfce7;border-left:4px solid #16a34a;padding:15px;border-radius:8px;">
                    <strong style="color:#166534;">✅ 생성 완료!</strong>
                    <div id="scm-bulk-message" style="color:#15803d;margin-top:10px;"></div>
                </div>
            </div>
        </div>
        
        <!-- 사용 가이드 -->
        <div class="scm-guide" style="background:#fffbeb;padding:25px;border-radius:12px;border-left:4px solid #f59e0b;">
            <h3 style="margin-top:0;color:#92400e;display:flex;align-items:center;gap:10px;">
                💡 AI 생성 가이드
            </h3>
            <ul style="color:#78350f;line-height:1.8;margin:0;">
                <li><strong>정확한 키워드 사용:</strong> "청년도약계좌", "국민취업지원제도" 등 정확한 정책명 입력</li>
                <li><strong>자동 콘텐츠 생성:</strong> PASONA 법칙 기반으로 설득력 있는 콘텐츠 자동 생성</li>
                <li><strong>초안 저장:</strong> 생성된 카드는 초안으로 저장되며, 검토 후 발행 가능</li>
                <li><strong>메타 정보 자동:</strong> 지원금액, 대상, 신청시기 등 자동으로 채워짐</li>
                <li><strong>수동 편집 가능:</strong> 생성 후 언제든지 수동으로 수정 가능</li>
            </ul>
        </div>
        
        <!-- PASONA 법칙 설명 -->
        <div class="scm-pasona-info" style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-top:30px;">
            <h3 style="margin-top:0;color:#1e3a5f;">📊 PASONA 법칙 기반 수익 최적화</h3>
            <p style="color:#64748b;">AI가 자동으로 생성하는 콘텐츠는 PASONA 법칙을 따라 사용자의 클릭을 유도합니다.</p>
            
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-top:20px;">
                <div style="background:#f0f9ff;padding:20px;border-radius:8px;">
                    <strong style="color:#1e3a5f;display:block;margin-bottom:8px;">1️⃣ Problem (문제)</strong>
                    <small style="color:#64748b;">"이 지원금 모르면 손해봅니다"</small>
                </div>
                <div style="background:#fef3c7;padding:20px;border-radius:8px;">
                    <strong style="color:#92400e;display:block;margin-bottom:8px;">2️⃣ Agitation (선동)</strong>
                    <small style="color:#78350f;">"신청 안 하면 영원히 못 받아요"</small>
                </div>
                <div style="background:#dcfce7;padding:20px;border-radius:8px;">
                    <strong style="color:#166534;display:block;margin-bottom:8px;">3️⃣ Solution (해결책)</strong>
                    <small style="color:#15803d;">"30초 만에 신청하고 혜택 받으세요"</small>
                </div>
                <div style="background:#fce7f3;padding:20px;border-radius:8px;">
                    <strong style="color:#831843;display:block;margin-bottom:8px;">4️⃣ Offer (제안)</strong>
                    <small style="color:#9f1239;">"지금 신청하면 추가 혜택까지"</small>
                </div>
                <div style="background:#f3e8ff;padding:20px;border-radius:8px;">
                    <strong style="color:#581c87;display:block;margin-bottom:8px;">5️⃣ Narrowing (좁히기)</strong>
                    <small style="color:#6b21a8;">"조건 맞으면 바로 신청하세요"</small>
                </div>
                <div style="background:#fee2e2;padding:20px;border-radius:8px;">
                    <strong style="color:#991b1b;display:block;margin-bottom:8px;">6️⃣ Action (행동)</strong>
                    <small style="color:#b91c1c;">"지금 바로 신청하기 ➜"</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.scm-card-editor input:focus {
    border-color: #667eea !important;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.scm-card-editor button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.scm-card-editor button:active {
    transform: translateY(0);
}

.scm-card-editor button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.scm-loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-right: 10px;
    vertical-align: middle;
}
</style>

<script>
jQuery(document).ready(function($) {
    let keywordCount = 9;
    
    // 입력칸 추가
    $('#scm-add-keyword-field').on('click', function() {
        if (keywordCount >= 20) {
            alert('최대 20개까지 입력 가능합니다.');
            return;
        }
        keywordCount++;
        $('#scm-keyword-inputs').append(
            '<input type="text" class="scm-bulk-keyword" placeholder="키워드 ' + keywordCount + '" style="padding:10px;border:2px solid #e5e7eb;border-radius:8px;" />'
        );
    });
    
    // 단일 생성
    $('#scm-generate-single').on('click', function() {
        const keyword = $('#scm-single-keyword').val().trim();
        
        if (!keyword) {
            alert('키워드를 입력해주세요.');
            return;
        }
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="scm-loading"></span>생성 중...');
        $('#scm-single-result, #scm-single-error').hide();
        
        $.ajax({
            url: scmAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'scm_generate_card',
                nonce: scmAdmin.nonce,
                keyword: keyword
            },
            success: function(response) {
                if (response.success) {
                    $('#scm-single-message').html(
                        '카드가 생성되었습니다! <a href="' + response.data.edit_url + '" target="_blank" style="color:#166534;font-weight:bold;text-decoration:underline;">편집하러 가기 →</a>'
                    );
                    $('#scm-single-result').fadeIn();
                    $('#scm-single-keyword').val('');
                } else {
                    $('#scm-single-error-message').text(response.data || '생성에 실패했습니다.');
                    $('#scm-single-error').fadeIn();
                }
            },
            error: function() {
                $('#scm-single-error-message').text('서버 오류가 발생했습니다.');
                $('#scm-single-error').fadeIn();
            },
            complete: function() {
                $btn.prop('disabled', false).html('✨ AI로 카드 생성하기');
            }
        });
    });
    
    // 대량 생성
    $('#scm-generate-bulk').on('click', function() {
        const keywords = [];
        $('.scm-bulk-keyword').each(function() {
            const val = $(this).val().trim();
            if (val) keywords.push(val);
        });
        
        if (keywords.length === 0) {
            alert('최소 1개의 키워드를 입력해주세요.');
            return;
        }
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="scm-loading"></span>생성 중...');
        $('#scm-bulk-result').hide();
        $('#scm-bulk-progress').show();
        $('#scm-progress-text').text('0 / ' + keywords.length);
        $('#scm-progress-bar').css('width', '0%');
        
        $.ajax({
            url: scmAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'scm_bulk_generate',
                nonce: scmAdmin.nonce,
                keywords: keywords
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    let html = '<strong style="font-size:18px;">' + data.created + '개의 카드가 생성되었습니다!</strong>';
                    
                    if (data.results.length > 0) {
                        html += '<ul style="margin-top:10px;list-style:none;padding:0;">';
                        data.results.forEach(function(result) {
                            html += '<li style="margin:5px 0;">✅ ' + result.keyword + ' - <a href="' + result.edit_url + '" target="_blank" style="color:#15803d;text-decoration:underline;">편집</a></li>';
                        });
                        html += '</ul>';
                    }
                    
                    if (data.errors.length > 0) {
                        html += '<div style="margin-top:15px;padding:10px;background:#fee2e2;border-radius:6px;"><strong style="color:#991b1b;">일부 오류 발생:</strong><ul style="margin:5px 0;color:#b91c1c;">';
                        data.errors.forEach(function(error) {
                            html += '<li>' + error + '</li>';
                        });
                        html += '</ul></div>';
                    }
                    
                    $('#scm-bulk-message').html(html);
                    $('#scm-bulk-result').fadeIn();
                    $('.scm-bulk-keyword').val('');
                } else {
                    alert('생성에 실패했습니다: ' + (response.data || '알 수 없는 오류'));
                }
            },
            error: function() {
                alert('서버 오류가 발생했습니다.');
            },
            complete: function() {
                $btn.prop('disabled', false).html('🚀 대량 생성 시작');
                $('#scm-bulk-progress').hide();
                $('#scm-progress-bar').css('width', '100%');
            }
        });
    });
});
</script>
