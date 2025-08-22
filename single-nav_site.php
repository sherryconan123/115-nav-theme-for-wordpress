<?php
/**
 * 单个导航网站页面模板
 */

get_header(); ?>

<main class="main-content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            
            <article class="site-single">
                <!-- 网站信息卡片 -->
                <div class="site-info-card">
                    <div class="site-header-single">
                        <div class="site-icon-large">
                            <?php
                            $site_icon = get_post_meta(get_the_ID(), '_nav115_site_icon', true);
                            if ($site_icon) :
                            ?>
                                <img src="<?php echo esc_url($site_icon); ?>" 
                                     alt="<?php the_title(); ?>" 
                                     width="80" 
                                     height="80"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 80px; height: 80px; align-items: center; justify-content: center; background: #e9ecef; color: #666; font-weight: bold; font-size: 32px; border-radius: 20px;">
                                    <?php echo mb_substr(get_the_title(), 0, 1); ?>
                                </div>
                            <?php else : ?>
                                <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #e9ecef; color: #666; font-weight: bold; font-size: 32px; border-radius: 20px;">
                                    <?php echo mb_substr(get_the_title(), 0, 1); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="site-info">
                            <h1 class="site-title-single"><?php the_title(); ?></h1>
                            
                            <?php
                            $site_url = get_post_meta(get_the_ID(), '_nav115_site_url', true);
                            $site_description = get_post_meta(get_the_ID(), '_nav115_site_description', true);
                            ?>
                            
                            <?php if ($site_description) : ?>
                                <p class="site-description-single"><?php echo esc_html($site_description); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($site_url) : ?>
                                <div class="site-actions">
                                    <a href="<?php echo esc_url($site_url); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="visit-site-btn">
                                        访问网站
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <button class="copy-url-btn" data-url="<?php echo esc_url($site_url); ?>">
                                        复制链接
                                    </button>
                                </div>
                                
                                <div class="site-url">
                                    <small><?php echo esc_url($site_url); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- 网站详细信息 -->
                <div class="site-details-section">
                    <div class="site-content">
                        <?php if (get_the_content()) : ?>
                            <h3>网站介绍</h3>
                            <div class="content">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 网站元信息 -->
                    <div class="site-meta-info">
                        <?php
                        $categories = get_the_terms(get_the_ID(), 'nav_category');
                        $tags = get_the_terms(get_the_ID(), 'nav_tag');
                        ?>
                        
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                            <div class="meta-item">
                                <h4>所属分类</h4>
                                <div class="category-list">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?php echo get_term_link($category); ?>" class="category-link">
                                            <?php echo $category->name; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($tags && !is_wp_error($tags)) : ?>
                            <div class="meta-item">
                                <h4>相关标签</h4>
                                <div class="tag-list">
                                    <?php foreach ($tags as $tag) : ?>
                                        <a href="<?php echo get_term_link($tag); ?>" class="tag-link">
                                            # <?php echo $tag->name; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="meta-item">
                            <h4>收录信息</h4>
                            <p>收录时间：<?php echo get_the_date('Y年m月d日'); ?></p>
                            <p>最后更新：<?php echo get_the_modified_date('Y年m月d日'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- 相关推荐 -->
                <?php
                if ($categories && !is_wp_error($categories)) :
                    $related_args = array(
                        'post_type' => 'nav_site',
                        'posts_per_page' => 6,
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'nav_category',
                                'field' => 'term_id',
                                'terms' => wp_list_pluck($categories, 'term_id')
                            )
                        )
                    );
                    
                    $related_sites = new WP_Query($related_args);
                    
                    if ($related_sites->have_posts()) :
                ?>
                    <section class="related-sites">
                        <h3>相关推荐</h3>
                        <div class="sites-grid">
                            <?php while ($related_sites->have_posts()) : $related_sites->the_post(); ?>
                                <?php get_template_part('templates/site', 'card'); ?>
                            <?php endwhile; ?>
                        </div>
                    </section>
                <?php
                    endif;
                    wp_reset_postdata();
                endif;
                ?>
                
            </article>
            
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<style>
.site-single {
    max-width: 900px;
    margin: 0 auto;
}

.site-info-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    border: 1px solid var(--border-color);
}

.site-header-single {
    display: flex;
    align-items: flex-start;
    gap: 30px;
}

.site-icon-large img {
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.site-info {
    flex: 1;
}

.site-title-single {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-color);
    margin: 0 0 15px 0;
}

.site-description-single {
    font-size: 1.1rem;
    color: #666;
    line-height: 1.6;
    margin-bottom: 25px;
}

.site-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.visit-site-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.visit-site-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.copy-url-btn {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.copy-url-btn:hover {
    background: var(--primary-color);
    color: white;
}

.site-url {
    color: #999;
    font-size: 0.9rem;
}

.site-details-section {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.site-content h3 {
    color: var(--text-color);
    font-size: 1.3rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--border-color);
}

.content {
    line-height: 1.8;
    color: #555;
}

.site-meta-info {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}

.meta-item {
    margin-bottom: 25px;
}

.meta-item h4 {
    color: var(--text-color);
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.category-list, .tag-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.category-link, .tag-link {
    background: var(--bg-color);
    color: var(--text-color);
    padding: 6px 15px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.category-link:hover, .tag-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.related-sites {
    margin-top: 50px;
}

.related-sites h3 {
    text-align: center;
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: var(--text-color);
}

.related-sites .sites-grid {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

@media (max-width: 768px) {
    .site-info-card, 
    .site-details-section {
        padding: 25px;
    }
    
    .site-header-single {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .site-title-single {
        font-size: 1.6rem;
    }
    
    .site-actions {
        justify-content: center;
    }
}
</style>

<script>
// 复制链接功能
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.querySelector('.copy-url-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const url = this.dataset.url;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(function() {
                    copyBtn.textContent = '已复制!';
                    setTimeout(function() {
                        copyBtn.textContent = '复制链接';
                    }, 2000);
                });
            } else {
                // 兼容旧版浏览器
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                copyBtn.textContent = '已复制!';
                setTimeout(function() {
                    copyBtn.textContent = '复制链接';
                }, 2000);
            }
        });
    }
});
</script>

<?php get_footer(); ?>