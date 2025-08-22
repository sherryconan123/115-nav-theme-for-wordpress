    </div><!-- #content -->
    
    <!-- 页脚 -->
    <footer class="nav-footer">
        <div class="nav-container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo get_option('nav115_site_title', '115导航'); ?>. 
               Powered by <a href="https://wordpress.org" style="color: #74b9ff;">WordPress</a>
            </p>
            
            <!-- 页脚菜单 -->
            <?php if (has_nav_menu('footer')) : ?>
                <nav class="footer-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'container' => false,
                        'depth' => 1
                    ));
                    ?>
                </nav>
            <?php endif; ?>
            
            <!-- 统计信息 -->
            <div class="site-stats">
                <?php
                $site_count = wp_count_posts('nav_site')->publish;
                $category_count = wp_count_terms('nav_category');
                ?>
                <span>共收录 <strong><?php echo $site_count; ?></strong> 个网站</span>
                <span>·</span>
                <span><strong><?php echo $category_count; ?></strong> 个分类</span>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<!-- 返回顶部按钮 -->
<button id="back-to-top" class="back-to-top" style="display: none;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<style>
.footer-navigation {
    margin: 15px 0;
}

.footer-menu {
    display: flex;
    justify-content: center;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-menu a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-menu a:hover {
    color: white;
}

.site-stats {
    margin-top: 15px;
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}

.site-stats span {
    margin: 0 5px;
}

.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.back-to-top:hover {
    background: var(--hover-color);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

@media (max-width: 768px) {
    .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
    
    .footer-menu {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
// 返回顶部功能
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('back-to-top');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'flex';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

</body>
</html>