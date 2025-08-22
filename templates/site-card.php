<?php
/**
 * 网站卡片模板
 */

$site_url = get_post_meta(get_the_ID(), '_nav115_site_url', true);
$site_description = get_post_meta(get_the_ID(), '_nav115_site_description', true);
$site_keywords = get_post_meta(get_the_ID(), '_nav115_site_keywords', true);
$site_icon = get_post_meta(get_the_ID(), '_nav115_site_icon', true);

// 获取网站分类
$categories = get_the_terms(get_the_ID(), 'nav_category');
$tags = get_the_terms(get_the_ID(), 'nav_tag');
?>

<article class="site-card" data-id="<?php the_ID(); ?>">
    <div class="site-header">
        <div class="site-icon">
            <?php if ($site_icon) : ?>
                <img src="<?php echo esc_url($site_icon); ?>" 
                     alt="<?php the_title(); ?>" 
                     width="40" 
                     height="40" 
                     style="border-radius: 10px; object-fit: cover;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #e9ecef; color: #666; font-weight: bold; font-size: 16px;">
                    <?php echo mb_substr(get_the_title(), 0, 1); ?>
                </div>
            <?php else : ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e9ecef; color: #666; font-weight: bold; font-size: 16px;">
                    <?php echo mb_substr(get_the_title(), 0, 1); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <h3 class="site-title">
            <?php if ($site_url) : ?>
                <a href="<?php echo esc_url($site_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php the_title(); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            <?php endif; ?>
        </h3>
    </div>
    
    <?php if ($site_description) : ?>
        <div class="site-description">
            <?php echo esc_html($site_description); ?>
        </div>
    <?php elseif (has_excerpt()) : ?>
        <div class="site-description">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>
    
    <!-- 网站分类和标签 -->
    <?php if ($categories || $tags) : ?>
        <div class="site-tags">
            <?php 
            // 显示分类
            if ($categories && !is_wp_error($categories)) :
                foreach ($categories as $category) :
            ?>
                <a href="<?php echo get_term_link($category); ?>" class="site-tag category-tag">
                    <?php echo $category->name; ?>
                </a>
            <?php 
                endforeach;
            endif;
            
            // 显示标签 (限制显示数量)
            if ($tags && !is_wp_error($tags)) :
                $tag_count = 0;
                foreach ($tags as $tag) :
                    if ($tag_count >= 3) break; // 最多显示3个标签
            ?>
                <a href="<?php echo get_term_link($tag); ?>" class="site-tag">
                    # <?php echo $tag->name; ?>
                </a>
            <?php 
                    $tag_count++;
                endforeach;
            endif;
            ?>
        </div>
    <?php endif; ?>
    
    <!-- 网站信息 -->
    <div class="site-meta" style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #999;">
        <span class="site-date">
            <?php echo get_the_date('Y-m-d'); ?>
        </span>
        
        <?php if ($site_url) : ?>
            <a href="<?php the_permalink(); ?>" class="site-details" style="color: var(--primary-color); text-decoration: none;">
                查看详情 →
            </a>
        <?php endif; ?>
    </div>
</article>

<style>
.category-tag {
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color)) !important;
    color: white !important;
    font-weight: 500;
}

.site-meta {
    border-top: 1px solid var(--border-color);
    padding-top: 12px;
}

.site-details:hover {
    color: var(--hover-color) !important;
}

.site-card:hover .site-meta {
    border-color: var(--primary-color);
}

/* 卡片动画效果 */
.site-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* 网站图标加载失败时的样式 */
.site-icon img {
    transition: all 0.3s ease;
}

.site-icon img:hover {
    transform: scale(1.1);
}
</style>