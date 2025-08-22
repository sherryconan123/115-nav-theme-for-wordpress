<?php get_header(); ?>

<main class="main-content">
    <!-- 搜索结果容器 -->
    <div id="search-results" class="sites-grid">
        <?php
        // 获取导航网站
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'nav_site',
            'post_status' => 'publish',
            'posts_per_page' => get_option('nav115_items_per_page', 12),
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key' => '_nav115_site_url',
                    'compare' => 'EXISTS'
                )
            )
        );
        
        // 如果有分类筛选
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'nav_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['category'])
                )
            );
        }
        
        // 如果有搜索
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $args['s'] = sanitize_text_field($_GET['s']);
        }
        
        $nav_sites = new WP_Query($args);
        
        if ($nav_sites->have_posts()) :
            while ($nav_sites->have_posts()) : $nav_sites->the_post();
                get_template_part('templates/site', 'card');
            endwhile;
        else :
        ?>
            <div class="empty-state">
                <h3>暂无网站</h3>
                <p>请添加一些导航网站</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 分页导航 -->
    <?php nav115_pagination($nav_sites->max_num_pages); ?>
    
    <?php wp_reset_postdata(); ?>
</main>

<!-- 加载更多按钮 -->
<div class="load-more-container" style="text-align: center; margin: 30px 0; display: none;">
    <button id="load-more-btn" class="button button-primary">加载更多</button>
</div>

<?php get_footer(); ?>