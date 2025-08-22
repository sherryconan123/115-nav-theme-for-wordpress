<?php
/**
 * 115导航主题 - 演示数据导入
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 创建演示数据
 */
function nav115_create_demo_data() {
    // 创建分类
    $categories = array(
        array(
            'name' => '常用推荐',
            'slug' => 'changyongtuijian',
            'description' => '精选的常用优质网站，提供各类便民服务和实用工具'
        ),
        array(
            'name' => '设计工具',
            'slug' => 'sheji-gongju',
            'description' => '专业的设计软件、在线设计平台和设计资源网站'
        ),
        array(
            'name' => '开发工具',
            'slug' => 'kaifa-gongju', 
            'description' => '程序员必备的开发工具、代码编辑器和技术文档'
        ),
        array(
            'name' => 'AI工具',
            'slug' => 'ai-gongju',
            'description' => '前沿的人工智能工具和AI应用平台'
        ),
        array(
            'name' => '在线办公',
            'slug' => 'zaixian-bangong',
            'description' => '高效的在线办公工具和协作平台'
        ),
        array(
            'name' => '学习资源',
            'slug' => 'xuexi-ziyuan',
            'description' => '优质的在线学习平台和教育资源'
        ),
        array(
            'name' => '图片处理',
            'slug' => 'tupian-chuli',
            'description' => '专业的图片编辑和处理工具'
        ),
        array(
            'name' => '数据分析',
            'slug' => 'shuju-fenxi',
            'description' => '强大的数据分析和可视化工具'
        )
    );

    foreach ($categories as $category) {
        if (!term_exists($category['slug'], 'nav_category')) {
            wp_insert_term($category['name'], 'nav_category', array(
                'slug' => $category['slug'],
                'description' => $category['description']
            ));
        }
    }

    // 创建标签
    $tags = array(
        '在线工具', '免费', '设计', '开发', 'AI', '效率', '创意', '学习', 
        '办公', '图片', '视频', '音频', '文档', '云服务', '社交', '购物',
        '新闻', '娱乐', '旅游', '美食', '健康', '金融', '教育', '科技'
    );

    foreach ($tags as $tag) {
        if (!term_exists($tag, 'nav_tag')) {
            wp_insert_term($tag, 'nav_tag');
        }
    }

    // 创建演示网站数据
    $demo_sites = array(
        array(
            'title' => '115la导航',
            'url' => 'https://www.115.la',
            'description' => '115la导航是一个精心收录各类优质网站的导航平台，为用户提供便捷的网址导航服务，包含设计工具、开发工具、AI工具等多个分类。',
            'keywords' => '网址导航,导航网站,115导航,网站收录,工具导航',
            'category' => 'changyongtuijian',
            'tags' => array('导航', '工具', '推荐', '效率')
        ),
        array(
            'title' => 'Figma',
            'url' => 'https://www.figma.com',
            'description' => 'Figma是一款基于浏览器的协作式UI设计工具，支持实时协作和原型设计，是现代设计师的首选工具。',
            'keywords' => 'UI设计,协作,原型,界面设计',
            'category' => 'sheji-gongju',
            'tags' => array('设计', '在线工具', '协作', '免费')
        ),
        array(
            'title' => 'GitHub',
            'url' => 'https://github.com',
            'description' => 'GitHub是全球最大的代码托管平台，为开发者提供Git仓库托管、项目管理和协作开发服务。',
            'keywords' => '代码托管,Git,开源,协作开发',
            'category' => 'kaifa-gongju',
            'tags' => array('开发', '代码', '开源', '协作')
        ),
        array(
            'title' => 'ChatGPT',
            'url' => 'https://chat.openai.com',
            'description' => 'OpenAI开发的强大AI对话模型，能够进行自然语言对话、回答问题、协助编程和创作等。',
            'keywords' => 'AI聊天,人工智能,对话模型,OpenAI',
            'category' => 'ai-gongju',
            'tags' => array('AI', '聊天', '智能', '效率')
        ),
        array(
            'title' => '腾讯文档',
            'url' => 'https://docs.qq.com',
            'description' => '腾讯文档是一款在线文档编辑工具，支持多人实时协作编辑Word、Excel、PPT等文档。',
            'keywords' => '在线文档,协作编辑,云文档,办公',
            'category' => 'zaixian-bangong',
            'tags' => array('办公', '文档', '协作', '免费')
        ),
        array(
            'title' => 'Coursera',
            'url' => 'https://www.coursera.org',
            'description' => 'Coursera是世界领先的在线学习平台，提供来自全球顶尖大学和公司的课程和学位项目。',
            'keywords' => '在线学习,大学课程,职业培训,学位',
            'category' => 'xuexi-ziyuan',
            'tags' => array('学习', '课程', '教育', '认证')
        ),
        array(
            'title' => 'Canva',
            'url' => 'https://www.canva.com',
            'description' => 'Canva是一个简单易用的在线设计平台，提供丰富的模板和设计元素，让每个人都能创作出专业的设计作品。',
            'keywords' => '在线设计,平面设计,模板,易用',
            'category' => 'sheji-gongju',
            'tags' => array('设计', '模板', '简单', '免费')
        ),
        array(
            'title' => 'VS Code',
            'url' => 'https://code.visualstudio.com',
            'description' => 'Visual Studio Code是微软开发的免费代码编辑器，支持多种编程语言和丰富的扩展生态。',
            'keywords' => '代码编辑器,IDE,微软,免费',
            'category' => 'kaifa-gongju',
            'tags' => array('开发', '编辑器', '免费', '微软')
        ),
        array(
            'title' => 'Notion',
            'url' => 'https://www.notion.so',
            'description' => 'Notion是一款多功能的工作空间工具，集成了笔记、文档、数据库、看板等功能于一体。',
            'keywords' => '笔记,文档,数据库,工作空间',
            'category' => 'zaixian-bangong',
            'tags' => array('笔记', '办公', '效率', '协作')
        ),
        array(
            'title' => 'Unsplash',
            'url' => 'https://unsplash.com',
            'description' => 'Unsplash提供高质量的免费图片资源，所有照片都可以用于商业和个人项目，无需版权担忧。',
            'keywords' => '免费图片,高清照片,商用图片,摄影',
            'category' => 'tupian-chuli',
            'tags' => array('图片', '免费', '高清', '商用')
        ),
        array(
            'title' => 'Midjourney',
            'url' => 'https://www.midjourney.com',
            'description' => 'Midjourney是一款AI图像生成工具，能够根据文本描述创造出令人惊叹的艺术作品和图像。',
            'keywords' => 'AI绘画,图像生成,艺术创作,人工智能',
            'category' => 'ai-gongju',
            'tags' => array('AI', '绘画', '创意', '艺术')
        ),
        array(
            'title' => 'Google Analytics',
            'url' => 'https://analytics.google.com',
            'description' => 'Google Analytics是免费的网站分析工具，提供详细的访问者数据和网站性能分析。',
            'keywords' => '网站分析,数据统计,流量分析,谷歌',
            'category' => 'shuju-fenxi',
            'tags' => array('数据', '分析', '统计', '免费')
        ),
        array(
            'title' => '石墨文档',
            'url' => 'https://shimo.im',
            'description' => '石墨文档是一款轻量化的在线协作文档工具，支持多人同时编辑，实时保存和同步。',
            'keywords' => '协作文档,在线编辑,实时同步,轻量化',
            'category' => 'zaixian-bangong',
            'tags' => array('文档', '协作', '在线', '效率')
        )
    );

    foreach ($demo_sites as $site_data) {
        // 检查是否已存在
        $existing = get_posts(array(
            'post_type' => 'nav_site',
            'title' => $site_data['title'],
            'post_status' => 'any',
            'numberposts' => 1
        ));

        if (!$existing) {
            // 创建网站文章
            $post_id = wp_insert_post(array(
                'post_title' => $site_data['title'],
                'post_content' => $site_data['description'],
                'post_status' => 'publish',
                'post_type' => 'nav_site',
                'meta_input' => array(
                    '_nav115_site_url' => $site_data['url'],
                    '_nav115_site_description' => $site_data['description'],
                    '_nav115_site_keywords' => $site_data['keywords']
                )
            ));

            if ($post_id && !is_wp_error($post_id)) {
                // 设置分类
                $category_term = get_term_by('slug', $site_data['category'], 'nav_category');
                if ($category_term) {
                    wp_set_object_terms($post_id, $category_term->term_id, 'nav_category');
                }

                // 设置标签
                wp_set_object_terms($post_id, $site_data['tags'], 'nav_tag');

                // 尝试获取网站图标
                nav115_fetch_and_save_icon($post_id, $site_data['url']);
            }
        }
    }

    // 设置默认主题选项
    update_option('nav115_site_title', '115导航');
    update_option('nav115_site_description', '精心收录各类优质网站，为您提供便捷的网址导航服务');
    update_option('nav115_auto_fetch', true);
    update_option('nav115_items_per_page', 12);

    return true;
}

/**
 * 获取并保存网站图标
 */
function nav115_fetch_and_save_icon($post_id, $url) {
    $parsed_url = parse_url($url);
    if (!$parsed_url || !isset($parsed_url['host'])) {
        return false;
    }

    $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    $favicon_urls = array(
        $base_url . '/favicon.ico',
        $base_url . '/favicon.png', 
        $base_url . '/apple-touch-icon.png'
    );

    foreach ($favicon_urls as $favicon_url) {
        $response = wp_remote_get($favicon_url, array('timeout' => 10));
        
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = wp_remote_retrieve_body($response);
            if ($body) {
                // 保存到本地
                $upload_dir = wp_upload_dir();
                $icons_dir = $upload_dir['basedir'] . '/nav-icons/';
                
                if (!file_exists($icons_dir)) {
                    wp_mkdir_p($icons_dir);
                }
                
                $filename = sanitize_file_name($parsed_url['host'] . '.ico');
                $file_path = $icons_dir . $filename;
                $file_url = $upload_dir['baseurl'] . '/nav-icons/' . $filename;
                
                if (file_put_contents($file_path, $body)) {
                    update_post_meta($post_id, '_nav115_site_icon', $file_url);
                    return true;
                }
            }
        }
    }

    return false;
}

/**
 * 添加演示数据导入页面
 */
function nav115_demo_data_page() {
    ?>
    <div class="wrap">
        <h1>导入演示数据</h1>
        
        <div class="nav115-demo-import">
            <div class="demo-info">
                <h3>关于演示数据</h3>
                <p>演示数据包含以下内容：</p>
                <ul>
                    <li>8个网站分类（常用推荐、设计工具、开发工具等）</li>
                    <li>24个常用标签</li>
                    <li>12个精选网站</li>
                    <li>默认主题设置</li>
                </ul>
                
                <div class="demo-notice">
                    <strong>注意：</strong>导入演示数据将创建新的内容，不会覆盖现有内容。如果您已经添加了网站和分类，可能会出现重复。
                </div>
            </div>
            
            <div class="demo-actions">
                <button id="import-demo-btn" class="button button-primary button-large">
                    导入演示数据
                </button>
                
                <div id="import-progress" style="display: none;">
                    <p>正在导入数据，请稍候...</p>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
                
                <div id="import-result" style="display: none;"></div>
            </div>
        </div>
    </div>
    
    <style>
    .nav115-demo-import {
        max-width: 800px;
        background: #fff;
        padding: 30px;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        margin-top: 20px;
    }
    
    .demo-info h3 {
        margin-top: 0;
        color: #23282d;
    }
    
    .demo-info ul {
        padding-left: 20px;
        margin: 15px 0;
    }
    
    .demo-info li {
        margin: 5px 0;
    }
    
    .demo-notice {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 4px;
        padding: 15px;
        margin: 20px 0;
        color: #856404;
    }
    
    .demo-actions {
        margin-top: 30px;
        text-align: center;
    }
    
    .progress-bar {
        width: 100%;
        height: 20px;
        background: #f1f1f1;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #0073aa, #00a0d2);
        width: 0%;
        transition: width 0.3s ease;
        animation: progress 2s ease-in-out;
    }
    
    @keyframes progress {
        0% { width: 0%; }
        100% { width: 100%; }
    }
    
    .import-success {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
        padding: 15px;
        border-radius: 4px;
        margin: 20px 0;
    }
    
    .import-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
        margin: 20px 0;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        $('#import-demo-btn').on('click', function() {
            var button = $(this);
            var progress = $('#import-progress');
            var result = $('#import-result');
            
            button.prop('disabled', true);
            progress.show();
            result.hide();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nav115_import_demo_data',
                    nonce: '<?php echo wp_create_nonce('nav115_demo_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        result.html('<div class="import-success"><h3>导入成功！</h3><p>演示数据已成功导入，您现在可以查看网站效果。</p><p><a href="' + response.data.home_url + '" target="_blank">查看网站首页</a> | <a href="' + response.data.admin_url + '">管理网站</a></p></div>');
                    } else {
                        result.html('<div class="import-error"><h3>导入失败</h3><p>' + response.data + '</p></div>');
                    }
                },
                error: function() {
                    result.html('<div class="import-error"><h3>导入失败</h3><p>网络错误，请稍后重试。</p></div>');
                },
                complete: function() {
                    button.prop('disabled', false);
                    progress.hide();
                    result.show();
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX处理演示数据导入
 */
function nav115_ajax_import_demo_data() {
    check_ajax_referer('nav115_demo_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('权限不足');
    }
    
    try {
        $result = nav115_create_demo_data();
        
        if ($result) {
            wp_send_json_success(array(
                'message' => '演示数据导入成功',
                'home_url' => home_url(),
                'admin_url' => admin_url('admin.php?page=nav115_admin')
            ));
        } else {
            wp_send_json_error('导入过程中发生错误');
        }
    } catch (Exception $e) {
        wp_send_json_error('导入失败：' . $e->getMessage());
    }
}
add_action('wp_ajax_nav115_import_demo_data', 'nav115_ajax_import_demo_data');