<?php
/**
 * 归档页面模板 - 显示所有导航网站
 */

get_header(); ?>

<main class="main-content">
    <!-- 归档页面头部 -->
    <div class="archive-header">
        <div class="archive-info">
            <h1 class="archive-title">
                <?php
                if (is_post_type_archive('nav_site')) {
                    echo '全部网站';
                } else {
                    echo '网站归档';
                }
                ?>
            </h1>
            
            <div class="archive-description">
                <p>浏览我们收录的所有优质网站，发现更多有用的在线工具和资源</p>
            </div>
            
            <div class="archive-stats">
                <?php
                $total_sites = wp_count_posts('nav_site')->publish;
                $total_categories = wp_count_terms('nav_category');
                ?>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_sites; ?></span>
                    <span class="stat-label">收录网站</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_categories; ?></span>
                    <span class="stat-label">网站分类</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo date('Y'); ?></span>
                    <span class="stat-label">建站年份</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 快速导航 -->
    <div class="quick-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" data-view="grid">网格视图</button>
            <button class="nav-tab" data-view="list">列表视图</button>
            <button class="nav-tab" data-view="compact">紧凑视图</button>
        </div>
        
        <div class="view-controls">
            <div class="sort-controls">
                <label>排序:</label>
                <select id="archive-sort">
                    <option value="date">最新收录</option>
                    <option value="title">网站名称</option>
                    <option value="popular">访问量</option>
                    <option value="random">随机排序</option>
                </select>
            </div>
            
            <div class="filter-controls">
                <input type="text" id="quick-filter" placeholder="快速筛选...">
            </div>
        </div>
    </div>
    
    <!-- 网站列表 -->
    <div id="archive-content" class="sites-grid view-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="archive-site-item">
                    <?php get_template_part('templates/site', 'card'); ?>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="no-sites">
                <h3>暂无网站</h3>
                <p>还没有收录任何网站，请稍后再来查看</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 分页导航 -->
    <?php nav115_pagination(); ?>
    
    <!-- 字母索引导航 -->
    <div class="alphabet-index">
        <h3>按首字母浏览</h3>
        <div class="alphabet-links">
            <?php
            $letters = range('A', 'Z');
            $numbers = array('0-9');
            $all_chars = array_merge($numbers, $letters);
            
            foreach ($all_chars as $char) :
                $query_char = ($char === '0-9') ? '[0-9]' : $char;
            ?>
                <a href="#" class="alphabet-link" data-char="<?php echo $char; ?>">
                    <?php echo $char; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- 最近添加 -->
    <section class="recent-additions">
        <h3>最近添加</h3>
        <div class="recent-sites">
            <?php
            $recent_sites = new WP_Query(array(
                'post_type' => 'nav_site',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish'
            ));
            
            if ($recent_sites->have_posts()) :
                while ($recent_sites->have_posts()) : $recent_sites->the_post();
            ?>
                <div class="recent-site">
                    <?php
                    $site_icon = get_post_meta(get_the_ID(), '_nav115_site_icon', true);
                    $site_url = get_post_meta(get_the_ID(), '_nav115_site_url', true);
                    ?>
                    
                    <div class="recent-site-icon">
                        <?php if ($site_icon) : ?>
                            <img src="<?php echo esc_url($site_icon); ?>" alt="<?php the_title(); ?>">
                        <?php else : ?>
                            <span><?php echo mb_substr(get_the_title(), 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="recent-site-info">
                        <h4>
                            <a href="<?php echo $site_url ? esc_url($site_url) : get_permalink(); ?>" 
                               <?php echo $site_url ? 'target="_blank"' : ''; ?>>
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        <span class="recent-date"><?php echo get_the_date('m-d'); ?></span>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    
</main>

<style>
.archive-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 60px 0;
    margin: -30px -20px 40px -20px;
    text-align: center;
}

.archive-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.archive-description p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 40px auto;
    line-height: 1.6;
}

.archive-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #74b9ff;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.quick-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid var(--border-color);
}

.nav-tabs {
    display: flex;
    gap: 5px;
}

.nav-tab {
    padding: 10px 20px;
    border: none;
    background: var(--bg-color);
    color: var(--text-color);
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-tab:hover,
.nav-tab.active {
    background: var(--primary-color);
    color: white;
}

.view-controls {
    display: flex;
    gap: 20px;
    align-items: center;
}

.sort-controls,
.filter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-controls label {
    font-weight: 500;
    color: var(--text-color);
}

#archive-sort,
#quick-filter {
    padding: 8px 15px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.9rem;
}

#quick-filter {
    width: 200px;
}

/* 视图样式 */
.view-grid .archive-site-item {
    display: block;
}

.view-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.view-list .archive-site-item .site-card {
    display: flex;
    align-items: center;
    padding: 20px;
}

.view-list .site-header {
    flex-direction: row;
    align-items: center;
    margin-bottom: 0;
    width: 300px;
}

.view-list .site-description {
    flex: 1;
    margin-left: 20px;
}

.view-compact {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}

.view-compact .site-card {
    padding: 15px;
}

.view-compact .site-description {
    display: none;
}

.alphabet-index {
    margin: 50px 0;
    text-align: center;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.alphabet-index h3 {
    margin-bottom: 20px;
    color: var(--text-color);
}

.alphabet-links {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
}

.alphabet-link {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-color);
    color: var(--text-color);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.alphabet-link:hover,
.alphabet-link.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.recent-additions {
    margin-top: 40px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.recent-additions h3 {
    margin-bottom: 25px;
    color: var(--text-color);
    text-align: center;
}

.recent-sites {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.recent-site {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: var(--bg-color);
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.recent-site:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recent-site-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.recent-site-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-site-icon span {
    font-weight: bold;
    color: var(--primary-color);
}

.recent-site-info h4 {
    margin: 0 0 5px 0;
    font-size: 1rem;
}

.recent-site-info a {
    color: var(--text-color);
    text-decoration: none;
}

.recent-site-info a:hover {
    color: var(--primary-color);
}

.recent-date {
    font-size: 0.8rem;
    color: #999;
}

@media (max-width: 768px) {
    .archive-header {
        padding: 40px 20px;
        margin: -30px -20px 20px -20px;
    }
    
    .archive-title {
        font-size: 2rem;
    }
    
    .archive-stats {
        gap: 20px;
    }
    
    .quick-navigation {
        flex-direction: column;
        gap: 20px;
        align-items: stretch;
    }
    
    .view-controls {
        flex-direction: column;
        gap: 15px;
    }
    
    .sort-controls,
    .filter-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    #quick-filter {
        width: auto;
        flex: 1;
    }
    
    .alphabet-links {
        gap: 5px;
    }
    
    .alphabet-link {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
    }
    
    .recent-sites {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // 视图切换功能
    $('.nav-tab').on('click', function() {
        var view = $(this).data('view');
        
        $('.nav-tab').removeClass('active');
        $(this).addClass('active');
        
        $('#archive-content').removeClass('view-grid view-list view-compact').addClass('view-' + view);
    });
    
    // 快速筛选功能
    $('#quick-filter').on('input', function() {
        var filter = $(this).val().toLowerCase();
        
        $('.archive-site-item').each(function() {
            var title = $(this).find('.site-title').text().toLowerCase();
            var description = $(this).find('.site-description').text().toLowerCase();
            
            if (title.includes(filter) || description.includes(filter)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // 字母索引功能
    $('.alphabet-link').on('click', function(e) {
        e.preventDefault();
        var char = $(this).data('char');
        
        $('.alphabet-link').removeClass('active');
        $(this).addClass('active');
        
        $('.archive-site-item').each(function() {
            var title = $(this).find('.site-title').text().charAt(0).toUpperCase();
            
            if (char === '0-9') {
                if (/[0-9]/.test(title)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if (title === char) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
        
        // 滚动到内容区域
        $('html, body').animate({
            scrollTop: $('#archive-content').offset().top - 100
        }, 500);
    });
    
    // 排序功能
    $('#archive-sort').on('change', function() {
        var sortBy = $(this).val();
        // 这里可以实现AJAX排序或重新加载页面
        console.log('Sort by:', sortBy);
    });
});
</script>

<?php get_footer(); ?>