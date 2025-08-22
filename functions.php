<?php
/**
 * 115导航主题功能文件
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 定义主题常量
define('NAV115_VERSION', '1.0.0');
define('NAV115_THEME_DIR', get_template_directory());
define('NAV115_THEME_URL', get_template_directory_uri());

// 引入演示数据功能
require_once NAV115_THEME_DIR . '/inc/demo-data.php';

/**
 * 主题初始化
 */
function nav115_theme_setup() {
    // 添加主题支持
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // 注册菜单
    register_nav_menus(array(
        'primary' => '主导航菜单',
        'footer' => '页脚菜单'
    ));
}
add_action('after_setup_theme', 'nav115_theme_setup');

/**
 * 加载样式和脚本
 */
function nav115_enqueue_scripts() {
    // 加载CSS
    wp_enqueue_style('nav115-style', get_stylesheet_uri(), array(), NAV115_VERSION);
    wp_enqueue_style('nav115-custom', NAV115_THEME_URL . '/assets/css/custom.css', array(), NAV115_VERSION);
    
    // 加载JavaScript
    wp_enqueue_script('nav115-main', NAV115_THEME_URL . '/assets/js/main.js', array('jquery'), NAV115_VERSION, true);
    
    // 本地化脚本
    wp_localize_script('nav115-main', 'nav115_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('nav115_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'nav115_enqueue_scripts');

/**
 * 注册自定义文章类型 - 导航网站
 */
function nav115_register_nav_sites() {
    $labels = array(
        'name' => '导航网站',
        'singular_name' => '导航网站',
        'menu_name' => '导航网站',
        'add_new' => '添加网站',
        'add_new_item' => '添加新网站',
        'edit_item' => '编辑网站',
        'new_item' => '新网站',
        'view_item' => '查看网站',
        'search_items' => '搜索网站',
        'not_found' => '未找到网站',
        'not_found_in_trash' => '回收站中未找到网站'
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => 'nav115_admin',
        'query_var' => true,
        'rewrite' => array('slug' => 'sites'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-admin-links'
    );
    
    register_post_type('nav_site', $args);
}
add_action('init', 'nav115_register_nav_sites');

/**
 * 注册自定义分类法 - 网站分类
 */
function nav115_register_taxonomies() {
    // 网站分类
    $labels = array(
        'name' => '网站分类',
        'singular_name' => '网站分类',
        'search_items' => '搜索分类',
        'all_items' => '所有分类',
        'parent_item' => '父级分类',
        'parent_item_colon' => '父级分类:',
        'edit_item' => '编辑分类',
        'update_item' => '更新分类',
        'add_new_item' => '添加新分类',
        'new_item_name' => '新分类名称',
        'menu_name' => '网站分类'
    );
    
    register_taxonomy('nav_category', 'nav_site', array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'favorites'),
        'show_in_menu' => true
    ));
    
    // 网站标签
    $tag_labels = array(
        'name' => '网站标签',
        'singular_name' => '网站标签',
        'search_items' => '搜索标签',
        'popular_items' => '热门标签',
        'all_items' => '所有标签',
        'edit_item' => '编辑标签',
        'update_item' => '更新标签',
        'add_new_item' => '添加新标签',
        'new_item_name' => '新标签名称',
        'menu_name' => '网站标签'
    );
    
    register_taxonomy('nav_tag', 'nav_site', array(
        'labels' => $tag_labels,
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'sitetag'),
        'show_in_menu' => true
    ));
}
add_action('init', 'nav115_register_taxonomies');

/**
 * 添加115导航管理菜单
 */
function nav115_admin_menu() {
    add_menu_page(
        '115导航',
        '115导航',
        'manage_options',
        'nav115_admin',
        'nav115_admin_page',
        'dashicons-admin-site-alt3',
        3
    );
    
    add_submenu_page(
        'nav115_admin',
        '导航设置',
        '导航设置',
        'manage_options',
        'nav115_settings',
        'nav115_settings_page'
    );
    
    add_submenu_page(
        'nav115_admin',
        '网站管理',
        '网站管理',
        'manage_options',
        'edit.php?post_type=nav_site'
    );
    
    add_submenu_page(
        'nav115_admin',
        '网站分类',
        '网站分类',
        'manage_options',
        'edit-tags.php?taxonomy=nav_category&post_type=nav_site'
    );
    
    add_submenu_page(
        'nav115_admin',
        '网站标签',
        '网站标签',
        'manage_options',
        'edit-tags.php?taxonomy=nav_tag&post_type=nav_site'
    );
    
    add_submenu_page(
        'nav115_admin',
        '导入演示数据',
        '导入演示数据',
        'manage_options',
        'nav115_demo_data',
        'nav115_demo_data_page'
    );
}
add_action('admin_menu', 'nav115_admin_menu');

/**
 * 115导航主页面
 */
function nav115_admin_page() {
    ?>
    <div class="wrap">
        <h1>115导航</h1>
        <div class="nav115-admin-dashboard">
            <div class="nav115-stats">
                <div class="stat-box">
                    <h3>总网站数</h3>
                    <p><?php echo wp_count_posts('nav_site')->publish; ?></p>
                </div>
                <div class="stat-box">
                    <h3>分类数</h3>
                    <p><?php echo wp_count_terms('nav_category'); ?></p>
                </div>
                <div class="stat-box">
                    <h3>标签数</h3>
                    <p><?php echo wp_count_terms('nav_tag'); ?></p>
                </div>
            </div>
            
            <div class="nav115-quick-actions">
                <h3>快速操作</h3>
                <a href="<?php echo admin_url('post-new.php?post_type=nav_site'); ?>" class="button button-primary">添加新网站</a>
                <a href="<?php echo admin_url('edit.php?post_type=nav_site'); ?>" class="button">管理网站</a>
                <a href="<?php echo admin_url('admin.php?page=nav115_settings'); ?>" class="button">导航设置</a>
            </div>
        </div>
    </div>
    
    <style>
    .nav115-admin-dashboard {
        margin-top: 20px;
    }
    .nav115-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
        min-width: 150px;
    }
    .stat-box h3 {
        margin: 0 0 10px 0;
        color: #666;
        font-size: 14px;
    }
    .stat-box p {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
        color: #0073aa;
    }
    .nav115-quick-actions {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .nav115-quick-actions h3 {
        margin-top: 0;
    }
    .nav115-quick-actions .button {
        margin-right: 10px;
    }
    </style>
    <?php
}

/**
 * 115导航设置页面
 */
function nav115_settings_page() {
    if (isset($_POST['submit'])) {
        update_option('nav115_site_title', sanitize_text_field($_POST['site_title']));
        update_option('nav115_site_description', sanitize_textarea_field($_POST['site_description']));
        update_option('nav115_auto_fetch', isset($_POST['auto_fetch']));
        update_option('nav115_items_per_page', intval($_POST['items_per_page']));
        echo '<div class="notice notice-success"><p>设置已保存</p></div>';
    }
    
    $site_title = get_option('nav115_site_title', '115导航');
    $site_description = get_option('nav115_site_description', '精心收录各类优质网站，为您提供便捷的网址导航服务');
    $auto_fetch = get_option('nav115_auto_fetch', true);
    $items_per_page = get_option('nav115_items_per_page', 12);
    ?>
    
    <div class="wrap">
        <h1>导航设置</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">网站标题</th>
                    <td><input type="text" name="site_title" value="<?php echo esc_attr($site_title); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">网站描述</th>
                    <td><textarea name="site_description" rows="3" cols="50"><?php echo esc_textarea($site_description); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">自动获取网站信息</th>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_fetch" value="1" <?php checked($auto_fetch); ?> />
                            启用自动获取网站标题、描述、关键词和图标
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">每页显示数量</th>
                    <td><input type="number" name="items_per_page" value="<?php echo $items_per_page; ?>" min="1" max="100" /></td>
                </tr>
            </table>
            
            <?php submit_button('保存设置'); ?>
        </form>
    </div>
    <?php
}

/**
 * 添加自定义字段到网站编辑页面
 */
function nav115_add_meta_boxes() {
    add_meta_box(
        'nav115_site_info',
        '网站信息',
        'nav115_site_info_callback',
        'nav_site',
        'normal',
        'high'
    );
    
    add_meta_box(
        'nav115_site_fetch',
        '自动获取',
        'nav115_site_fetch_callback',
        'nav_site',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'nav115_add_meta_boxes');

/**
 * 网站信息meta box回调
 */
function nav115_site_info_callback($post) {
    wp_nonce_field('nav115_save_meta', 'nav115_meta_nonce');
    
    $site_url = get_post_meta($post->ID, '_nav115_site_url', true);
    $site_description = get_post_meta($post->ID, '_nav115_site_description', true);
    $site_keywords = get_post_meta($post->ID, '_nav115_site_keywords', true);
    $site_icon = get_post_meta($post->ID, '_nav115_site_icon', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="nav115_site_url">网站URL</label></th>
            <td><input type="url" id="nav115_site_url" name="nav115_site_url" value="<?php echo esc_url($site_url); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nav115_site_description">网站描述</label></th>
            <td><textarea id="nav115_site_description" name="nav115_site_description" rows="3" class="large-text"><?php echo esc_textarea($site_description); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="nav115_site_keywords">关键词</label></th>
            <td>
                <input type="text" id="nav115_site_keywords" name="nav115_site_keywords" value="<?php echo esc_attr($site_keywords); ?>" class="regular-text" />
                <p class="description">多个关键词用英文逗号分隔</p>
            </td>
        </tr>
        <tr>
            <th><label for="nav115_site_icon">网站图标URL</label></th>
            <td><input type="url" id="nav115_site_icon" name="nav115_site_icon" value="<?php echo esc_url($site_icon); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

/**
 * 自动获取meta box回调
 */
function nav115_site_fetch_callback($post) {
    ?>
    <div id="nav115-fetch-section">
        <p>
            <button type="button" id="nav115-fetch-btn" class="button button-primary">自动获取网站信息</button>
        </p>
        <div id="nav115-fetch-result" style="display:none;"></div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('#nav115-fetch-btn').click(function() {
            var url = $('#nav115_site_url').val();
            if (!url) {
                alert('请先输入网站URL');
                return;
            }
            
            var button = $(this);
            button.prop('disabled', true).text('获取中...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nav115_fetch_site_info',
                    url: url,
                    nonce: '<?php echo wp_create_nonce("nav115_fetch_nonce"); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#nav115_site_url').val(data.url);
                        $('input[name="post_title"]').val(data.title);
                        $('#nav115_site_description').val(data.description);
                        $('#nav115_site_keywords').val(data.keywords);
                        $('#nav115_site_icon').val(data.icon);
                        
                        $('#nav115-fetch-result').html('<div class="notice notice-success"><p>信息获取成功！</p></div>').show();
                    } else {
                        $('#nav115-fetch-result').html('<div class="notice notice-error"><p>获取失败：' + response.data + '</p></div>').show();
                    }
                },
                error: function() {
                    $('#nav115-fetch-result').html('<div class="notice notice-error"><p>获取失败，请检查网络连接</p></div>').show();
                },
                complete: function() {
                    button.prop('disabled', false).text('自动获取网站信息');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * 保存网站自定义字段
 */
function nav115_save_meta_data($post_id) {
    if (!isset($_POST['nav115_meta_nonce']) || !wp_verify_nonce($_POST['nav115_meta_nonce'], 'nav115_save_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('nav115_site_url', 'nav115_site_description', 'nav115_site_keywords', 'nav115_site_icon');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'nav115_save_meta_data');

/**
 * AJAX获取网站信息
 */
function nav115_ajax_fetch_site_info() {
    check_ajax_referer('nav115_fetch_nonce', 'nonce');
    
    $url = esc_url_raw($_POST['url']);
    if (!$url) {
        wp_die('无效的URL');
    }
    
    $site_info = nav115_fetch_site_info($url);
    
    if ($site_info) {
        wp_send_json_success($site_info);
    } else {
        wp_send_json_error('无法获取网站信息');
    }
}
add_action('wp_ajax_nav115_fetch_site_info', 'nav115_ajax_fetch_site_info');

/**
 * 获取网站信息函数
 */
function nav115_fetch_site_info($url) {
    $response = wp_remote_get($url, array('timeout' => 30));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    
    // 解析网页内容
    $title = '';
    $description = '';
    $keywords = '';
    $icon = '';
    
    // 获取标题
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $matches)) {
        $title = trim(html_entity_decode(strip_tags($matches[1])));
    }
    
    // 获取描述
    if (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/i', $body, $matches)) {
        $description = trim(html_entity_decode(strip_tags($matches[1])));
    }
    
    // 获取关键词
    if (preg_match('/<meta[^>]*name=["\']keywords["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/i', $body, $matches)) {
        $keywords = trim(html_entity_decode(strip_tags($matches[1])));
    }
    
    // 获取图标
    $parsed_url = parse_url($url);
    $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    
    // 尝试获取favicon
    if (preg_match('/<link[^>]*rel=["\'](?:shortcut )?icon["\'][^>]*href=["\']([^"\']*)["\'][^>]*>/i', $body, $matches)) {
        $icon_url = $matches[1];
        if (!filter_var($icon_url, FILTER_VALIDATE_URL)) {
            $icon_url = rtrim($base_url, '/') . '/' . ltrim($icon_url, '/');
        }
        $icon = $icon_url;
    } else {
        $icon = $base_url . '/favicon.ico';
    }
    
    // 下载并本地化图标
    if ($icon) {
        $local_icon = nav115_download_icon($icon, $parsed_url['host']);
        if ($local_icon) {
            $icon = $local_icon;
        }
    }
    
    return array(
        'url' => $url,
        'title' => $title,
        'description' => $description,
        'keywords' => $keywords,
        'icon' => $icon
    );
}

/**
 * 下载并本地化网站图标
 */
function nav115_download_icon($icon_url, $domain) {
    $upload_dir = wp_upload_dir();
    $icons_dir = $upload_dir['basedir'] . '/nav-icons/';
    
    if (!file_exists($icons_dir)) {
        wp_mkdir_p($icons_dir);
    }
    
    $filename = sanitize_file_name($domain . '.ico');
    $file_path = $icons_dir . $filename;
    $file_url = $upload_dir['baseurl'] . '/nav-icons/' . $filename;
    
    // 如果文件已存在，直接返回URL
    if (file_exists($file_path)) {
        return $file_url;
    }
    
    // 下载图标
    $response = wp_remote_get($icon_url, array('timeout' => 30));
    
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        if ($body) {
            file_put_contents($file_path, $body);
            return $file_url;
        }
    }
    
    return false;
}

/**
 * AJAX搜索功能
 */
function nav115_ajax_search() {
    check_ajax_referer('nav115_nonce', 'nonce');
    
    $search_term = sanitize_text_field($_POST['search']);
    $category = sanitize_text_field($_POST['category']);
    $page = intval($_POST['page']) ?: 1;
    
    $args = array(
        'post_type' => 'nav_site',
        'post_status' => 'publish',
        'posts_per_page' => get_option('nav115_items_per_page', 12),
        'paged' => $page
    );
    
    if ($search_term) {
        $args['s'] = $search_term;
    }
    
    if ($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'nav_category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('templates/site', 'card');
        }
    } else {
        echo '<div class="empty-state"><h3>未找到相关网站</h3><p>请尝试其他搜索词或浏览分类</p></div>';
    }
    
    $content = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(
        'content' => $content,
        'max_pages' => $query->max_num_pages,
        'found_posts' => $query->found_posts
    ));
}
add_action('wp_ajax_nav115_search', 'nav115_ajax_search');
add_action('wp_ajax_nopriv_nav115_search', 'nav115_ajax_search');

/**
 * 自定义分页函数
 */
function nav115_pagination($pages = '', $range = 4) {
    $showitems = ($range * 2) + 1;
    
    global $paged;
    if (empty($paged)) $paged = 1;
    
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    
    if (1 != $pages) {
        echo "<div class=\"nav-pagination\"><div class=\"pagination\">";
        
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
            echo "<a class=\"page-link\" href=\"".get_pagenum_link(1)."\">&laquo; 首页</a>";
        }
        if ($paged > 1 && $showitems < $pages) {
            echo "<a class=\"page-link\" href=\"".get_pagenum_link($paged - 1)."\">&lsaquo; 上一页</a>";
        }
        
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages &&(!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                echo ($paged == $i)? "<span class=\"page-link current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"page-link\">".$i."</a>";
            }
        }
        
        if ($paged < $pages && $showitems < $pages) {
            echo "<a class=\"page-link\" href=\"".get_pagenum_link($paged + 1)."\">下一页 &rsaquo;</a>";
        }
        if ($paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages) {
            echo "<a class=\"page-link\" href=\"".get_pagenum_link($pages)."\">尾页 &raquo;</a>";
        }
        
        echo "</div></div>\n";
    }
}