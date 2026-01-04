<?php
if (!defined('ABSPATH')) exit;

$total_cards = wp_count_posts('subsidy_card')->publish;
$draft_cards = wp_count_posts('subsidy_card')->draft;

$recent_cards = get_posts(array(
    'post_type' => 'subsidy_card',
    'posts_per_page' => 5,
    'post_status' => 'any'
));
?>

<div class="wrap scm-dashboard">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-money-alt" style="font-size:30px;"></span>
        지원금 카드 대시보드
    </h1>
    
    <hr class="wp-header-end">
    
    <div class="scm-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin:30px 0;">
        
        <div class="scm-stat-card" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:25px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
            <div style="font-size:14px;opacity:0.9;margin-bottom:10px;">전체 카드</div>
            <div style="font-size:36px;font-weight:bold;"><?php echo $total_cards; ?>개</div>
            <div style="font-size:13px;opacity:0.8;margin-top:10px;">
                <a href="<?php echo admin_url('edit.php?post_type=subsidy_card'); ?>" style="color:white;text-decoration:underline;">
                    전체 보기 →
                </a>
            </div>
        </div>
        
        <div class="scm-stat-card" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;padding:25px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
            <div style="font-size:14px;opacity:0.9;margin-bottom:10px;">임시저장</div>
            <div style="font-size:36px;font-weight:bold;"><?php echo $draft_cards; ?>개</div>
            <div style="font-size:13px;opacity:0.8;margin-top:10px;">
                <a href="<?php echo admin_url('edit.php?post_status=draft&post_type=subsidy_card'); ?>" style="color:white;text-decoration:underline;">
                    임시저장 보기 →
                </a>
            </div>
        </div>
        
        <div class="scm-stat-card" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);color:white;padding:25px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
            <div style="font-size:14px;opacity:0.9;margin-bottom:10px;">AI 생성</div>
            <div style="font-size:36px;font-weight:bold;">∞</div>
            <div style="font-size:13px;opacity:0.8;margin-top:10px;">
                <a href="<?php echo admin_url('admin.php?page=subsidy-cards-generator'); ?>" style="color:white;text-decoration:underline;">
                    새 카드 생성 →
                </a>
            </div>
        </div>
        
    </div>
    
    <div class="scm-quick-actions" style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:30px;">
        <h2 style="margin-top:0;color:#1e3a5f;">빠른 작업</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">
            <a href="<?php echo admin_url('admin.php?page=subsidy-cards-generator'); ?>" class="button button-primary button-hero" style="text-align:center;padding:15px;">
                ✨ AI로 카드 생성
            </a>
            <a href="<?php echo admin_url('post-new.php?post_type=subsidy_card'); ?>" class="button button-hero" style="text-align:center;padding:15px;">
                ➕ 수동으로 카드 추가
            </a>
            <a href="<?php echo admin_url('admin.php?page=subsidy-cards-ads'); ?>" class="button button-hero" style="text-align:center;padding:15px;">
                📢 광고 설정
            </a>
        </div>
    </div>
    
    <div class="scm-recent-cards" style="background:white;padding:25px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="margin-top:0;color:#1e3a5f;">최근 카드</h2>
        
        <?php if (!empty($recent_cards)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>제목</th>
                        <th>지원금액</th>
                        <th>대상</th>
                        <th>상태</th>
                        <th>작성일</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_cards as $card): ?>
                        <?php
                        $amount = get_post_meta($card->ID, '_scm_amount', true);
                        $target = get_post_meta($card->ID, '_scm_target', true);
                        $status_labels = array('publish' => '발행됨', 'draft' => '임시저장', 'pending' => '검토중');
                        $status = $status_labels[$card->post_status] ?? $card->post_status;
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($card->post_title); ?></strong>
                            </td>
                            <td><?php echo esc_html($amount ?: '-'); ?></td>
                            <td><?php echo esc_html($target ?: '-'); ?></td>
                            <td>
                                <span class="status-badge" style="padding:3px 10px;border-radius:12px;font-size:12px;background:<?php echo $card->post_status === 'publish' ? '#dcfce7' : '#fef3c7'; ?>;color:<?php echo $card->post_status === 'publish' ? '#166534' : '#92400e'; ?>;">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td><?php echo get_the_date('Y-m-d H:i', $card); ?></td>
                            <td>
                                <a href="<?php echo get_edit_post_link($card->ID); ?>" class="button button-small">편집</a>
                                <a href="<?php echo get_permalink($card->ID); ?>" class="button button-small" target="_blank">보기</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center;color:#999;padding:40px 0;">아직 카드가 없습니다. AI로 카드를 생성해보세요!</p>
        <?php endif; ?>
    </div>
    
    <div class="scm-help" style="background:#f0f9ff;padding:20px;border-radius:12px;margin-top:30px;border-left:4px solid #3182f6;">
        <h3 style="margin-top:0;color:#1e3a5f;">💡 사용 방법</h3>
        <ol style="color:#64748b;line-height:1.8;">
            <li><strong>AI 카드 생성</strong> - 키워드만 입력하면 자동으로 완성도 높은 카드 생성</li>
            <li><strong>광고 설정</strong> - 타뷸라, 데이블 등 다양한 광고 네트워크 통합 관리</li>
            <li><strong>숏코드 사용</strong> - <code>[subsidy_cards]</code>를 페이지에 삽입하여 카드 표시</li>
            <li><strong>수익 최적화</strong> - PASONA 법칙 기반 CTR 극대화 콘텐츠 자동 생성</li>
        </ol>
    </div>
</div>

<style>
.scm-stat-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}
</style>
