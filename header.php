<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    
    <!-- 网站头部 -->
    <header class="nav-header">
        <div class="nav-container">
            <h1 class="nav-title">
                <a href="<?php echo home_url(); ?>" style="color: white; text-decoration: none;">
                    <?php echo get_option('nav115_site_title', '115导航'); ?>
                </a>
            </h1>
            <p class="nav-subtitle"><?php echo get_option('nav115_site_description', '精心收录各类优质网站，为您提供便捷的网址导航服务'); ?></p>
            
            <!-- 搜索框 -->
            <div class="nav-search">
                <form id="nav-search-form" role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="search" 
                           id="search-input" 
                           class="search-input" 
                           placeholder="搜索网站..." 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" 
                           autocomplete="off">
                    <button type="submit" class="search-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>
    
    <!-- 分类导航 -->
    <nav class="nav-categories">
        <div class="categories-list">
            <a href="<?php echo home_url(); ?>" 
               class="category-item <?php echo (!isset($_GET['category']) && !is_search()) ? 'active' : ''; ?>">
                全部
            </a>
            
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'nav_category',
                'hide_empty' => true,
                'orderby' => 'count',
                'order' => 'DESC'
            ));
            
            if ($categories && !is_wp_error($categories)) :
                foreach ($categories as $category) :
                    $is_active = (isset($_GET['category']) && $_GET['category'] == $category->slug) ? 'active' : '';
            ?>
                <a href="<?php echo add_query_arg('category', $category->slug, home_url()); ?>" 
                   class="category-item <?php echo $is_active; ?>" 
                   data-category="<?php echo $category->slug; ?>">
                    <?php echo $category->name; ?>
                    <span class="category-count">(<?php echo $category->count; ?>)</span>
                </a>
            <?php 
                endforeach;
            endif;
            ?>
            
            <!-- 热门标签 -->
            <?php
            $popular_tags = get_terms(array(
                'taxonomy' => 'nav_tag',
                'hide_empty' => true,
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 10
            ));
            
            if ($popular_tags && !is_wp_error($popular_tags)) :
                foreach ($popular_tags as $tag) :
            ?>
                <a href="<?php echo get_term_link($tag); ?>" 
                   class="category-item" 
                   style="background: #e8f4f8; color: #2980b9;">
                    # <?php echo $tag->name; ?>
                </a>
            <?php 
                endforeach;
            endif;
            ?>
        </div>
    </nav>

    <div id="content" class="site-content">