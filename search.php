<?php
/**
 * 搜索结果页面模板
 */

get_header(); ?>

<main class="main-content">
    <!-- 搜索结果头部 -->
    <div class="search-results-header">
        <div class="search-info">
            <h1 class="search-title">
                搜索结果: "<?php echo get_search_query(); ?>"
            </h1>
            
            <div class="search-meta">
                <?php
                global $wp_query;
                $found_posts = $wp_query->found_posts;
                ?>
                <span class="results-count">找到 <strong><?php echo $found_posts; ?></strong> 个相关网站</span>
                
                <?php if ($found_posts > 0) : ?>
                    <span class="search-time">
                        搜索用时 <?php timer_stop(1); ?> 秒
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 搜索过滤器 -->
        <div class="search-filters">
            <div class="filter-group">
                <label>排序方式:</label>
                <select id="search-sort" class="filter-select">
                    <option value="relevance">相关度</option>
                    <option value="date">最新收录</option>
                    <option value="title">网站名称</option>
                    <option value="popular">热门程度</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>分类筛选:</label>
                <select id="search-category" class="filter-select">
                    <option value="">全部分类</option>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'nav_category',
                        'hide_empty' => true
                    ));
                    
                    foreach ($categories as $category) :
                    ?>
                        <option value="<?php echo $category->slug; ?>">
                            <?php echo $category->name; ?> (<?php echo $category->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <!-- 搜索结果内容 -->
    <div id="search-results-content" class="sites-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('templates/site', 'card'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                <h3>未找到相关网站</h3>
                <p>抱歉，没有找到与 "<strong><?php echo get_search_query(); ?></strong>" 相关的网站</p>
                
                <div class="search-suggestions">
                    <h4>搜索建议:</h4>
                    <ul>
                        <li>检查拼写是否正确</li>
                        <li>尝试使用更通用的关键词</li>
                        <li>使用不同的搜索词组合</li>
                        <li>浏览我们的网站分类</li>
                    </ul>
                </div>
                
                <div class="alternative-actions">
                    <a href="<?php echo home_url(); ?>" class="btn-primary">返回首页</a>
                    <button id="random-search" class="btn-secondary">随机浏览</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 分页导航 -->
    <?php if (have_posts()) : ?>
        <?php nav115_pagination(); ?>
    <?php endif; ?>
    
    <!-- 热门搜索词 -->
    <?php if (!have_posts()) : ?>
        <section class="popular-searches">
            <h3>热门搜索</h3>
            <div class="popular-terms">
                <?php
                // 这里可以从数据库获取热门搜索词，暂时用静态数据
                $popular_terms = array('设计工具', 'AI工具', '开发工具', '在线编辑器', '图片处理', '数据分析');
                foreach ($popular_terms as $term) :
                ?>
                    <a href="<?php echo home_url('?s=' . urlencode($term)); ?>" class="popular-term">
                        <?php echo $term; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
.search-results-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 40px;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 1px solid var(--border-color);
}

.search-title {
    font-size: 1.8rem;
    color: var(--text-color);
    margin-bottom: 15px;
    font-weight: 600;
}

.search-meta {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 25px;
    color: #666;
}

.results-count strong {
    color: var(--primary-color);
}

.search-filters {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-group label {
    font-weight: 500;
    color: var(--text-color);
}

.filter-select {
    padding: 8px 15px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: white;
    color: var(--text-color);
    font-size: 0.9rem;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    grid-column: 1 / -1;
}

.no-results-icon {
    margin-bottom: 20px;
    color: #ccc;
}

.no-results h3 {
    font-size: 1.5rem;
    color: var(--text-color);
    margin-bottom: 10px;
}

.no-results p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.search-suggestions {
    background: var(--bg-color);
    padding: 25px;
    border-radius: 15px;
    margin: 30px 0;
    text-align: left;
    display: inline-block;
}

.search-suggestions h4 {
    color: var(--text-color);
    margin-bottom: 15px;
}

.search-suggestions ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.search-suggestions li {
    padding: 5px 0;
    color: #666;
    position: relative;
    padding-left: 20px;
}

.search-suggestions li::before {
    content: '•';
    color: var(--primary-color);
    position: absolute;
    left: 0;
}

.alternative-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn-primary, .btn-secondary {
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--hover-color);
    transform: translateY(-2px);
}

.btn-secondary {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-secondary:hover {
    background: var(--primary-color);
    color: white;
}

.popular-searches {
    margin-top: 50px;
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.popular-searches h3 {
    color: var(--text-color);
    margin-bottom: 25px;
    font-size: 1.3rem;
}

.popular-terms {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.popular-term {
    background: var(--bg-color);
    color: var(--text-color);
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.popular-term:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .search-results-header {
        padding: 25px;
        margin: -30px -20px 20px -20px;
        border-radius: 0;
    }
    
    .search-filters {
        flex-direction: column;
        gap: 15px;
    }
    
    .filter-group {
        width: 100%;
        justify-content: space-between;
    }
    
    .filter-select {
        flex: 1;
        margin-left: 15px;
    }
    
    .alternative-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .popular-terms {
        gap: 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // 搜索过滤器功能
    $('#search-sort, #search-category').on('change', function() {
        var sort = $('#search-sort').val();
        var category = $('#search-category').val();
        var search = '<?php echo get_search_query(); ?>';
        
        // 重新加载页面with new parameters
        var url = new URL(window.location);
        url.searchParams.set('s', search);
        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        if (sort !== 'relevance') {
            url.searchParams.set('orderby', sort);
        } else {
            url.searchParams.delete('orderby');
        }
        
        window.location.href = url.toString();
    });
    
    // 随机浏览功能
    $('#random-search').on('click', function() {
        var randomTerms = ['设计工具', 'AI工具', '开发工具', '在线编辑器', '图片处理', '数据分析', '云服务', '学习资源'];
        var randomTerm = randomTerms[Math.floor(Math.random() * randomTerms.length)];
        window.location.href = '<?php echo home_url(); ?>?s=' + encodeURIComponent(randomTerm);
    });
});
</script>

<?php get_footer(); ?>