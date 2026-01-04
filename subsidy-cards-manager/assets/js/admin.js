/**
 * 지원금 카드 매니저 - 관리자 스크립트
 */

(function($) {
    'use strict';
    
    /**
     * 글자 수 제한 체크
     */
    function initCharacterLimit() {
        $('#scm_target').on('input', function() {
            var maxLength = 20;
            var currentLength = $(this).val().length;
            
            if (currentLength > maxLength) {
                $(this).val($(this).val().substring(0, maxLength));
                currentLength = maxLength;
            }
            
            var $counter = $(this).siblings('.char-counter');
            if (!$counter.length) {
                $counter = $('<span class="char-counter"></span>');
                $(this).after($counter);
            }
            
            $counter.text(currentLength + ' / ' + maxLength)
                .css({
                    'color': currentLength >= maxLength ? '#ef4444' : '#64748b',
                    'font-size': '12px',
                    'display': 'block',
                    'margin-top': '5px'
                });
        });
    }
    
    /**
     * 실시간 미리보기
     */
    function initLivePreview() {
        var $previewBtn = $('<button type="button" class="button" style="margin-left:10px;">미리보기</button>');
        $('.scm-meta-field').last().after($previewBtn);
        
        $previewBtn.on('click', function() {
            var previewHtml = generatePreviewHtml();
            showPreviewModal(previewHtml);
        });
    }
    
    function generatePreviewHtml() {
        var title = $('#title').val() || '지원금 제목';
        var amount = $('#scm_amount').val() || '최대 300만원';
        var amountSub = $('#scm_amount_sub').val() || '최대 6개월 지급';
        var description = $('#content').val() || '지원금 설명';
        var target = $('#scm_target').val() || '만 19~34세 청년';
        var period = $('#scm_period').val() || '상시 신청';
        var isFeatured = $('#scm_is_featured').is(':checked');
        
        return `
            <div style="max-width:400px;margin:0 auto;">
                <div class="scm-info-card${isFeatured ? ' scm-card-featured' : ''}" style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,0.06);overflow:hidden;">
                    <div class="scm-info-card-highlight" style="background:linear-gradient(135deg,#3182F6 0%,#1E6AD4 100%);padding:24px;">
                        ${isFeatured ? '<span class="scm-info-card-badge" style="background:rgba(255,255,255,0.2);padding:6px 12px;border-radius:20px;font-size:13px;font-weight:600;color:#fff;display:inline-block;margin-bottom:10px;">🔥 인기</span>' : ''}
                        <div style="font-size:32px;font-weight:800;color:#fff;letter-spacing:-1px;line-height:1.2;">${amount}</div>
                        <div style="font-size:13px;color:rgba(255,255,255,0.8);font-weight:500;margin-top:6px;">${amountSub}</div>
                    </div>
                    <div style="padding:24px;">
                        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin-bottom:8px;">${title}</h3>
                        <p style="font-size:14px;color:#71717a;line-height:1.6;margin-bottom:20px;">${description.substring(0, 100)}...</p>
                        <div style="background:#F0F9FF;border-radius:12px;padding:16px;margin-bottom:20px;">
                            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #bfdbfe;padding-bottom:10px;margin-bottom:10px;">
                                <span style="font-size:13px;color:#64748b;font-weight:500;">지원대상</span>
                                <span style="font-size:13px;font-weight:600;color:#1e3a5f;">${target}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:6px 0;">
                                <span style="font-size:13px;color:#64748b;font-weight:500;">신청시기</span>
                                <span style="font-size:13px;font-weight:600;color:#1e3a5f;">${period}</span>
                            </div>
                        </div>
                        <div style="background:linear-gradient(135deg,#3182F6 0%,#1E6AD4 100%);color:white;padding:16px;border-radius:12px;font-size:15px;font-weight:700;text-align:center;">
                            지금 바로 신청하기 →
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    function showPreviewModal(html) {
        var $modal = $('<div class="scm-preview-modal"></div>');
        var $overlay = $('<div class="scm-preview-overlay"></div>').css({
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'width': '100%',
            'height': '100%',
            'background': 'rgba(0,0,0,0.8)',
            'z-index': '999999',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center'
        });
        
        var $content = $('<div></div>').css({
            'background': 'white',
            'padding': '30px',
            'border-radius': '12px',
            'max-width': '500px',
            'position': 'relative'
        });
        
        var $closeBtn = $('<button>✕</button>').css({
            'position': 'absolute',
            'top': '10px',
            'right': '10px',
            'background': 'none',
            'border': 'none',
            'font-size': '24px',
            'cursor': 'pointer',
            'color': '#999'
        }).on('click', function() {
            $modal.remove();
        });
        
        var $title = $('<h2>카드 미리보기</h2>').css({
            'margin-top': '0',
            'margin-bottom': '20px',
            'color': '#1e3a5f'
        });
        
        $content.append($closeBtn, $title, html);
        $modal.append($overlay);
        $overlay.append($content);
        $('body').append($modal);
        
        $overlay.on('click', function(e) {
            if (e.target === this) {
                $modal.remove();
            }
        });
    }
    
    /**
     * 일괄 작업 기능
     */
    function initBulkActions() {
        // 워드프레스 기본 일괄 작업에 사용자 정의 액션 추가
        if ($('select[name="action"]').length) {
            $('select[name="action"], select[name="action2"]').append(
                '<option value="scm_set_featured">인기 카드로 설정</option>' +
                '<option value="scm_unset_featured">인기 카드 해제</option>'
            );
        }
    }
    
    /**
     * 자동 저장 알림
     */
    function initAutoSaveNotification() {
        $(document).on('heartbeat-tick', function(e, data) {
            if (data.wp_autosave) {
                showNotification('자동 저장되었습니다', 'success');
            }
        });
    }
    
    function showNotification(message, type) {
        var bgColor = type === 'success' ? '#dcfce7' : '#fee2e2';
        var textColor = type === 'success' ? '#166534' : '#991b1b';
        
        var $notification = $('<div></div>').css({
            'position': 'fixed',
            'top': '32px',
            'right': '20px',
            'background': bgColor,
            'color': textColor,
            'padding': '12px 20px',
            'border-radius': '8px',
            'box-shadow': '0 4px 12px rgba(0,0,0,0.1)',
            'z-index': '99999',
            'font-weight': '600',
            'animation': 'slideInRight 0.3s ease'
        }).text(message);
        
        $('body').append($notification);
        
        setTimeout(function() {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    /**
     * 키보드 단축키
     */
    function initKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S: 저장
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('#publish, #save-post').click();
                showNotification('저장 중...', 'info');
            }
            
            // Ctrl/Cmd + P: 미리보기
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                var previewHtml = generatePreviewHtml();
                showPreviewModal(previewHtml);
            }
        });
    }
    
    /**
     * 드래그 앤 드롭 정렬
     */
    function initDragAndDrop() {
        if ($('.scm-recent-cards table tbody').length) {
            $('.scm-recent-cards table tbody').sortable({
                handle: '.scm-drag-handle',
                placeholder: 'scm-sort-placeholder',
                update: function(event, ui) {
                    // 정렬 순서 저장
                    var order = [];
                    $(this).find('tr').each(function() {
                        order.push($(this).data('post-id'));
                    });
                    
                    $.ajax({
                        url: scmAdmin.ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'scm_save_card_order',
                            nonce: scmAdmin.nonce,
                            order: order
                        }
                    });
                }
            });
        }
    }
    
    /**
     * 복제 기능
     */
    function initDuplicateButton() {
        $('.scm-recent-cards table').on('click', '.scm-duplicate-btn', function(e) {
            e.preventDefault();
            
            var postId = $(this).data('post-id');
            var $btn = $(this);
            
            $btn.prop('disabled', true).text('복제 중...');
            
            $.ajax({
                url: scmAdmin.ajaxurl,
                method: 'POST',
                data: {
                    action: 'scm_duplicate_card',
                    nonce: scmAdmin.nonce,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('카드가 복제되었습니다', 'success');
                        location.reload();
                    } else {
                        showNotification('복제에 실패했습니다', 'error');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text('복제');
                }
            });
        });
    }
    
    /**
     * 검색 필터
     */
    function initSearchFilter() {
        var $searchInput = $('<input type="text" placeholder="카드 검색..." />').css({
            'padding': '8px 12px',
            'border': '2px solid #e5e7eb',
            'border-radius': '8px',
            'width': '250px',
            'margin-bottom': '15px'
        });
        
        $('.scm-recent-cards table').before($searchInput);
        
        $searchInput.on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            
            $('.scm-recent-cards table tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    }
    
    /**
     * 통계 대시보드 애니메이션
     */
    function animateStats() {
        $('.scm-stat-card').each(function(index) {
            $(this).css('opacity', '0').delay(index * 100).animate({
                opacity: 1
            }, 500);
        });
    }
    
    /**
     * 툴팁 초기화
     */
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            var tooltipText = $(this).data('tooltip');
            var $tooltip = $('<span class="scm-tooltiptext"></span>').text(tooltipText);
            $(this).addClass('scm-tooltip').append($tooltip);
        });
    }
    
    /**
     * 초기화
     */
    $(document).ready(function() {
        // 메타박스가 있는 페이지에서만 실행
        if ($('#scm_target').length) {
            initCharacterLimit();
            initLivePreview();
        }
        
        // 대시보드에서만 실행
        if ($('.scm-dashboard').length) {
            animateStats();
            initSearchFilter();
        }
        
        // 전역 기능
        initBulkActions();
        initAutoSaveNotification();
        initKeyboardShortcuts();
        initDuplicateButton();
        initTooltips();
        
        // 안내 메시지
        console.log('%c지원금 카드 매니저 Pro', 'font-size:20px;font-weight:bold;color:#667eea;');
        console.log('%c제작: 아로스 | https://aros100.com', 'color:#999;');
    });
    
})(jQuery);
