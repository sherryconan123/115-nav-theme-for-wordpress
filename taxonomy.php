<?php
/**
 * 分类页面模板
 */

get_header(); ?>

<main class="main-content">
    <!-- 分类信息 -->
    <div class="category-header">
        <div class="category-info">
            <h1 class="category-title">
                <?php 
                if (is_tax('nav_category')) {
                    single_cat_title();
                } elseif (is_tax('nav_tag')) {
                    echo '# ';
                    single_cat_title();
                } else {
                    echo '分类：';
                    single_cat_title();
                }
                ?>
            </h1>
            
            <?php if (category_description()) : ?>
                <div class="category-description">
                    <?php echo category_description(); ?>
                </div>
            <?php endif; ?>
            
            <div class="category-stats">
                <?php
                $queried_object = get_queried_object();
                if ($queried_object) {
                    echo '<span>共有 <strong>' . $queried_object->count . '</strong> 个网站</span>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- 网站列表 -->
    <div id="category-sites" class="sites-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('templates/site', 'card'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="empty-state">
                <h3>暂无网站</h3>
                <p>该分类下还没有收录任何网站</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 分页导航 -->
    <?php nav115_pagination(); ?>
    
    <!-- 相关分类推荐 -->
    <?php if (is_tax('nav_category')) : ?>
        <section class="related-categories">
            <h3>相关分类</h3>
            <div class="categories-grid">
                <?php
                $current_category = get_queried_object();
                $related_categories = get_terms(array(
                    'taxonomy' => 'nav_category',
                    'hide_empty' => true,
                    'exclude' => array($current_category->term_id),
                    'number' => 8,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                
                if ($related_categories && !is_wp_error($related_categories)) :
                    foreach ($related_categories as $category) :
                ?>
                    <a href="<?php echo get_term_link($category); ?>" class="category-card">
                        <h4><?php echo $category->name; ?></h4>
                        <p><?php echo $category->count; ?> 个网站</p>
                        <?php if ($category->description) : ?>
                            <span><?php echo wp_trim_words($category->description, 10); ?></span>
                        <?php endif; ?>
                    </a>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- 热门标签 -->
    <?php if (is_tax('nav_tag')) : ?>
        <section class="popular-tags">
            <h3>热门标签</h3>
            <div class="tags-cloud">
                <?php
                $popular_tags = get_terms(array(
                    'taxonomy' => 'nav_tag',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 20
                ));
                
                if ($popular_tags && !is_wp_error($popular_tags)) :
                    foreach ($popular_tags as $tag) :
                        $font_size = min(1.2, 0.8 + ($tag->count / 20));
                ?>
                    <a href="<?php echo get_term_link($tag); ?>" 
                       class="tag-cloud-item"
                       style="font-size: <?php echo $font_size; ?>rem;">
                        # <?php echo $tag->name; ?>
                    </a>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
.category-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 50px 0;
    margin: -30px -20px 30px -20px;
    text-align: center;
}

.category-info {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.category-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 20px 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.category-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 20px;
    opacity: 0.9;
}

.category-stats {
    font-size: 1rem;
    opacity: 0.8;
}

.category-stats strong {
    color: #74b9ff;
}

.related-categories,
.popular-tags {
    margin-top: 50px;
    padding: 40px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.related-categories h3,
.popular-tags h3 {
    text-align: center;
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: var(--text-color);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.category-card {
    background: var(--bg-color);
    padding: 25px;
    border-radius: 15px;
    text-decoration: none;
    color: var(--text-color);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
    text-align: center;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.category-card h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 10px 0;
    color: var(--primary-color);
}

.category-card p {
    font-size: 0.9rem;
    color: #666;
    margin: 0 0 10px 0;
}

.category-card span {
    font-size: 0.8rem;
    color: #999;
    line-height: 1.4;
}

.tags-cloud {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

.tag-cloud-item {
    background: var(--bg-color);
    color: var(--text-color);
    padding: 8px 16px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.tag-cloud-item:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .category-header {
        padding: 30px 0;
        margin: -30px -15px 20px -15px;
    }
    
    .category-title {
        font-size: 1.8rem;
    }
    
    .category-description {
        font-size: 1rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .related-categories,
    .popular-tags {
        padding: 25px;
        margin-top: 30px;
    }
    
    .tags-cloud {
        gap: 10px;
    }
}

/* 加载动画 */
@keyframes categorySlideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card,
.tag-cloud-item {
    animation: categorySlideIn 0.5s ease-out forwards;
}

.category-card:nth-child(2) { animation-delay: 0.1s; }
.category-card:nth-child(3) { animation-delay: 0.2s; }
.category-card:nth-child(4) { animation-delay: 0.3s; }
.category-card:nth-child(5) { animation-delay: 0.4s; }
.category-card:nth-child(6) { animation-delay: 0.5s; }
.category-card:nth-child(7) { animation-delay: 0.6s; }
.category-card:nth-child(8) { animation-delay: 0.7s; }
</style>

<?php get_footer(); ?>