/**
 * 지원금 카드 매니저 - 프론트엔드 스크립트
 * CTR 극대화 및 사용자 경험 최적화
 */

(function($) {
    'use strict';
    
    // 카드 노출 추적
    var cardImpressions = {};
    var clickTracking = {};
    
    /**
     * 카드 뷰포트 노출 감지 (CTR 분석용)
     */
    function initCardTracking() {
        if (!window.IntersectionObserver) return;
        
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var cardId = $(entry.target).attr('href') || entry.target.innerText;
                    
                    if (!cardImpressions[cardId]) {
                        cardImpressions[cardId] = {
                            timestamp: Date.now(),
                            viewed: true
                        };
                        
                        // 서버에 노출 기록 (선택사항)
                        // trackImpression(cardId);
                    }
                }
            });
        }, {
            threshold: 0.5 // 50% 노출 시 카운트
        });
        
        $('.scm-info-card').each(function() {
            observer.observe(this);
        });
    }
    
    /**
     * 클릭 이벤트 추적 및 최적화
     */
    function initClickTracking() {
        $('.scm-info-card').on('click', function(e) {
            var cardTitle = $(this).find('.scm-info-card-title').text();
            var cardUrl = $(this).attr('href');
            
            // 클릭 데이터 저장
            clickTracking[cardTitle] = {
                timestamp: Date.now(),
                url: cardUrl
            };
            
            // Google Analytics 이벤트 (GA4)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'card_click', {
                    'event_category': 'subsidy_card',
                    'event_label': cardTitle,
                    'value': 1
                });
            }
            
            // 부드러운 전환 효과
            $(this).css('opacity', '0.7');
        });
        
        // 버튼 호버 시 미세한 피드백
        $('.scm-info-card-btn').on('mouseenter', function() {
            $(this).css('transform', 'scale(1.02)');
        }).on('mouseleave', function() {
            $(this).css('transform', 'scale(1)');
        });
    }
    
    /**
     * 스크롤 진행률 추적
     */
    function initScrollTracking() {
        var scrollPercentages = [25, 50, 75, 100];
        var triggeredPercentages = {};
        
        $(window).on('scroll', function() {
            var scrollTop = $(window).scrollTop();
            var docHeight = $(document).height() - $(window).height();
            var scrollPercent = Math.round((scrollTop / docHeight) * 100);
            
            scrollPercentages.forEach(function(percentage) {
                if (scrollPercent >= percentage && !triggeredPercentages[percentage]) {
                    triggeredPercentages[percentage] = true;
                    
                    // GA4 이벤트
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            'event_category': 'engagement',
                            'event_label': percentage + '%',
                            'value': percentage
                        });
                    }
                }
            });
        });
    }
    
    /**
     * 카드 애니메이션 (부드러운 등장)
     */
    function initCardAnimations() {
        if (!window.IntersectionObserver) return;
        
        var animationObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    $(entry.target).css({
                        'opacity': '0',
                        'transform': 'translateY(20px)'
                    }).animate({
                        'opacity': '1',
                        'transform': 'translateY(0)'
                    }, 400);
                    
                    animationObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        $('.scm-info-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transition': 'all 0.4s ease'
            });
            
            animationObserver.observe(this);
        });
    }
    
    /**
     * CTA 버튼 긴급성 강조 (깜빡임 효과)
     */
    function initUrgencyEffects() {
        setInterval(function() {
            $('.scm-hero-urgent, .scm-info-card-badge').each(function() {
                $(this).fadeOut(200).fadeIn(200);
            });
        }, 3000);
    }
    
    /**
     * 모바일 터치 최적화
     */
    function initMobileOptimization() {
        if ('ontouchstart' in window) {
            $('.scm-info-card').on('touchstart', function() {
                $(this).css('transform', 'scale(0.98)');
            }).on('touchend', function() {
                $(this).css('transform', 'scale(1)');
            });
        }
    }
    
    /**
     * 읽기 시간 예측 표시
     */
    function addReadingTime() {
        $('.scm-info-card-desc').each(function() {
            var text = $(this).text();
            var wordCount = text.split(/\s+/).length;
            var readingTime = Math.ceil(wordCount / 200); // 분당 200단어
            
            if (readingTime > 0) {
                var badge = $('<span>')
                    .addClass('scm-reading-time')
                    .text(readingTime + '분 소요')
                    .css({
                        'display': 'inline-block',
                        'background': '#f0f9ff',
                        'color': '#1e3a5f',
                        'padding': '4px 8px',
                        'border-radius': '12px',
                        'font-size': '11px',
                        'margin-top': '8px',
                        'font-weight': '600'
                    });
                
                // $(this).after(badge); // 선택사항
            }
        });
    }
    
    /**
     * 로컬 스토리지 활용 - 최근 본 카드
     */
    function trackRecentlyViewed() {
        var currentCard = $('.scm-info-card').first().find('.scm-info-card-title').text();
        
        if (currentCard) {
            var recentCards = JSON.parse(localStorage.getItem('scm_recent_cards') || '[]');
            
            // 중복 제거
            recentCards = recentCards.filter(function(card) {
                return card !== currentCard;
            });
            
            // 최신 항목 추가
            recentCards.unshift(currentCard);
            
            // 최대 10개만 유지
            if (recentCards.length > 10) {
                recentCards = recentCards.slice(0, 10);
            }
            
            localStorage.setItem('scm_recent_cards', JSON.stringify(recentCards));
        }
    }
    
    /**
     * 공유 기능 추가
     */
    function initShareButtons() {
        $('.scm-info-card').each(function() {
            var $card = $(this);
            var cardTitle = $card.find('.scm-info-card-title').text();
            var cardUrl = $card.attr('href');
            
            var $shareBtn = $('<button>')
                .addClass('scm-share-btn')
                .html('📤')
                .css({
                    'position': 'absolute',
                    'top': '10px',
                    'right': '10px',
                    'background': 'rgba(255,255,255,0.9)',
                    'border': 'none',
                    'width': '36px',
                    'height': '36px',
                    'border-radius': '50%',
                    'cursor': 'pointer',
                    'font-size': '16px',
                    'box-shadow': '0 2px 8px rgba(0,0,0,0.1)',
                    'z-index': '10',
                    'transition': 'all 0.3s ease'
                })
                .on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (navigator.share) {
                        navigator.share({
                            title: cardTitle,
                            text: '놓치면 손해! ' + cardTitle,
                            url: cardUrl
                        });
                    } else {
                        // 폴백: URL 복사
                        var tempInput = $('<input>');
                        $('body').append(tempInput);
                        tempInput.val(cardUrl).select();
                        document.execCommand('copy');
                        tempInput.remove();
                        
                        alert('링크가 복사되었습니다!');
                    }
                });
            
            $card.css('position', 'relative').append($shareBtn);
        });
    }
    
    /**
     * 스켈레톤 로딩 효과
     */
    function addSkeletonLoading() {
        // 이미지가 로드되기 전 스켈레톤 표시
        $('.scm-info-card img').each(function() {
            var $img = $(this);
            var $skeleton = $('<div>').css({
                'background': 'linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%)',
                'background-size': '200% 100%',
                'animation': 'shimmer 1.5s infinite',
                'width': '100%',
                'height': '100%',
                'border-radius': '8px'
            });
            
            $img.before($skeleton).hide();
            
            $img.on('load', function() {
                $skeleton.remove();
                $img.fadeIn(300);
            });
        });
        
        // CSS 애니메이션 추가
        if (!$('#scm-skeleton-style').length) {
            $('head').append(
                '<style id="scm-skeleton-style">' +
                '@keyframes shimmer {' +
                '0% { background-position: -200% 0; }' +
                '100% { background-position: 200% 0; }' +
                '}' +
                '</style>'
            );
        }
    }
    
    /**
     * 초기화
     */
    $(document).ready(function() {
        // 모든 기능 초기화
        initCardTracking();
        initClickTracking();
        initScrollTracking();
        initCardAnimations();
        initUrgencyEffects();
        initMobileOptimization();
        addReadingTime();
        trackRecentlyViewed();
        // initShareButtons(); // 선택사항
        addSkeletonLoading();
        
        // 디버그 모드 (개발 시에만)
        if (window.location.search.includes('scm_debug=1')) {
            console.log('SCM Debug Mode');
            console.log('Card Impressions:', cardImpressions);
            console.log('Click Tracking:', clickTracking);
        }
    });
    
    /**
     * 페이지 이탈 전 데이터 전송
     */
    $(window).on('beforeunload', function() {
        // 세션 스토리지에 분석 데이터 저장
        sessionStorage.setItem('scm_impressions', JSON.stringify(cardImpressions));
        sessionStorage.setItem('scm_clicks', JSON.stringify(clickTracking));
    });
    
})(jQuery);
