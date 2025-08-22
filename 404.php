<?php
/**
 * 404错误页面模板
 */

get_header(); ?>

<main class="main-content">
    <div class="error-404-container">
        <div class="error-404-content">
            <!-- 404图标 -->
            <div class="error-404-icon">
                <svg width="200" height="160" viewBox="0 0 200 160" fill="none">
                    <circle cx="70" cy="50" r="12" fill="var(--primary-color)" opacity="0.3"/>
                    <circle cx="130" cy="50" r="12" fill="var(--primary-color)" opacity="0.3"/>
                    <path d="M60 80 Q100 110 140 80" stroke="var(--primary-color)" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <rect x="20" y="20" width="160" height="100" rx="20" stroke="var(--primary-color)" stroke-width="2" fill="none"/>
                    <text x="100" y="50" text-anchor="middle" font-size="24" font-weight="bold" fill="var(--primary-color)">404</text>
                </svg>
            </div>
            
            <!-- 错误信息 -->
            <div class="error-404-text">
                <h1>页面未找到</h1>
                <p class="error-message">抱歉，您要访问的页面不存在或已被移除</p>
                <p class="error-description">这可能是因为：</p>
                <ul class="error-reasons">
                    <li>输入的网址有误</li>
                    <li>页面已被删除或移动</li>
                    <li>网站正在维护中</li>
                    <li>链接已过期</li>
                </ul>
            </div>
            
            <!-- 搜索和导航 -->
            <div class="error-404-actions">
                <div class="search-section">
                    <h3>尝试搜索您需要的网站</h3>
                    <form class="error-search-form" role="search" method="get" action="<?php echo home_url('/'); ?>">
                        <input type="search" 
                               class="error-search-input" 
                               placeholder="输入关键词搜索网站..." 
                               name="s" 
                               autocomplete="off">
                        <button type="submit" class="error-search-btn">
                            搜索
                        </button>
                    </form>
                </div>
                
                <div class="navigation-section">
                    <h3>或者浏览以下内容</h3>
                    <div class="quick-links">
                        <a href="<?php echo home_url(); ?>" class="quick-link primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            返回首页
                        </a>
                        
                        <a href="<?php echo get_post_type_archive_link('nav_site'); ?>" class="quick-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            浏览全部网站
                        </a>
                        
                        <a href="#popular-categories" class="quick-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            热门分类
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 热门分类 -->
        <section id="popular-categories" class="popular-categories-404">
            <h3>热门分类</h3>
            <div class="categories-grid-404">
                <?php
                $popular_categories = get_terms(array(
                    'taxonomy' => 'nav_category',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 8
                ));
                
                if ($popular_categories && !is_wp_error($popular_categories)) :
                    foreach ($popular_categories as $category) :
                ?>
                    <a href="<?php echo get_term_link($category); ?>" class="category-card-404">
                        <div class="category-icon">
                            <?php echo mb_substr($category->name, 0, 1); ?>
                        </div>
                        <div class="category-info">
                            <h4><?php echo $category->name; ?></h4>
                            <span><?php echo $category->count; ?> 个网站</span>
                        </div>
                    </a>
                <?php 
                    endforeach;
                else :
                ?>
                    <div class="no-categories">
                        <p>暂无分类数据</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- 最新网站 -->
        <section class="latest-sites-404">
            <h3>最新收录</h3>
            <div class="latest-sites-list">
                <?php
                $latest_sites = new WP_Query(array(
                    'post_type' => 'nav_site',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish'
                ));
                
                if ($latest_sites->have_posts()) :
                    while ($latest_sites->have_posts()) : $latest_sites->the_post();
                        $site_url = get_post_meta(get_the_ID(), '_nav115_site_url', true);
                        $site_icon = get_post_meta(get_the_ID(), '_nav115_site_icon', true);
                ?>
                    <div class="latest-site-item">
                        <div class="site-icon-small">
                            <?php if ($site_icon) : ?>
                                <img src="<?php echo esc_url($site_icon); ?>" alt="<?php the_title(); ?>">
                            <?php else : ?>
                                <span><?php echo mb_substr(get_the_title(), 0, 1); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="site-details">
                            <h5>
                                <a href="<?php echo $site_url ? esc_url($site_url) : get_permalink(); ?>" 
                                   <?php echo $site_url ? 'target="_blank"' : ''; ?>>
                                    <?php the_title(); ?>
                                </a>
                            </h5>
                            <span class="site-date"><?php echo get_the_date('m-d'); ?></span>
                        </div>
                    </div>
                <?php 
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <div class="no-sites">
                        <p>暂无网站数据</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<style>
.error-404-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 0;
    text-align: center;
}

.error-404-content {
    background: white;
    border-radius: 20px;
    padding: 60px 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}

.error-404-icon {
    margin-bottom: 30px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.error-404-text h1 {
    font-size: 2.5rem;
    color: var(--text-color);
    margin-bottom: 15px;
    font-weight: 700;
}

.error-message {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 25px;
}

.error-description {
    color: #888;
    margin-bottom: 15px;
}

.error-reasons {
    list-style: none;
    padding: 0;
    margin: 0 0 40px 0;
    display: inline-block;
    text-align: left;
}

.error-reasons li {
    color: #666;
    margin: 8px 0;
    padding-left: 20px;
    position: relative;
}

.error-reasons li::before {
    content: '•';
    color: var(--primary-color);
    position: absolute;
    left: 0;
    font-weight: bold;
}

.error-404-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 40px;
}

.search-section h3,
.navigation-section h3 {
    color: var(--text-color);
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.error-search-form {
    display: flex;
    max-width: 300px;
    margin: 0 auto;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.error-search-input {
    flex: 1;
    padding: 12px 20px;
    border: none;
    font-size: 1rem;
    outline: none;
}

.error-search-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.error-search-btn:hover {
    background: var(--hover-color);
}

.quick-links {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 250px;
    margin: 0 auto;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: var(--bg-color);
    color: var(--text-color);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.quick-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.quick-link.primary {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.quick-link.primary:hover {
    background: var(--hover-color);
}

.popular-categories-404,
.latest-sites-404 {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.popular-categories-404 h3,
.latest-sites-404 h3 {
    color: var(--text-color);
    margin-bottom: 30px;
    font-size: 1.5rem;
}

.categories-grid-404 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.category-card-404 {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: var(--bg-color);
    border-radius: 15px;
    text-decoration: none;
    color: var(--text-color);
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.category-card-404:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.category-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.category-card-404:hover .category-icon {
    background: white;
    color: var(--primary-color);
}

.category-info h4 {
    margin: 0 0 5px 0;
    font-size: 1.1rem;
}

.category-info span {
    font-size: 0.9rem;
    opacity: 0.8;
}

.latest-sites-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.latest-site-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: var(--bg-color);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.latest-site-item:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.site-icon-small {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    flex-shrink: 0;
}

.site-icon-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.site-icon-small span {
    font-weight: bold;
    color: var(--primary-color);
    font-size: 0.9rem;
}

.latest-site-item:hover .site-icon-small span {
    color: var(--primary-color);
}

.site-details h5 {
    margin: 0 0 5px 0;
    font-size: 1rem;
}

.site-details a {
    color: inherit;
    text-decoration: none;
}

.site-date {
    font-size: 0.8rem;
    opacity: 0.7;
}

@media (max-width: 768px) {
    .error-404-content {
        padding: 40px 25px;
    }
    
    .error-404-text h1 {
        font-size: 2rem;
    }
    
    .error-404-actions {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .categories-grid-404,
    .latest-sites-list {
        grid-template-columns: 1fr;
    }
    
    .popular-categories-404,
    .latest-sites-404 {
        padding: 25px;
    }
}

.no-categories,
.no-sites {
    grid-column: 1 / -1;
    text-align: center;
    color: #999;
    padding: 30px;
}
</style>

<script>
// 平滑滚动到锚点
document.addEventListener('DOMContentLoaded', function() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<?php get_footer(); ?>