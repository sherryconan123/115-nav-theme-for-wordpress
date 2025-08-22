/**
 * 115导航主题 - 主要JavaScript功能
 */

(function($) {
    'use strict';
    
    // 全局变量
    let currentPage = 1;
    let isLoading = false;
    let searchTimeout;
    let currentCategory = '';
    let currentSearch = '';
    
    // 页面加载完成后初始化
    $(document).ready(function() {
        initSearch();
        initCategoryFilter();
        initInfiniteScroll();
        initBackToTop();
        initLoadMore();
        initMobileMenu();
        
        // 获取当前分类和搜索参数
        const urlParams = new URLSearchParams(window.location.search);
        currentCategory = urlParams.get('category') || '';
        currentSearch = urlParams.get('s') || '';
    });
    
    /**
     * 初始化搜索功能
     */
    function initSearch() {
        const searchInput = $('#search-input');
        const searchForm = $('#nav-search-form');
        
        // 实时搜索
        searchInput.on('input', function() {
            const searchTerm = $(this).val().trim();
            
            // 清除之前的延时
            clearTimeout(searchTimeout);
            
            // 延时搜索，避免过于频繁的请求
            searchTimeout = setTimeout(function() {
                if (searchTerm !== currentSearch) {
                    currentSearch = searchTerm;
                    currentPage = 1;
                    performSearch(searchTerm, currentCategory, 1, true);
                    
                    // 更新URL
                    updateURL();
                }
            }, 300);
        });
        
        // 表单提交
        searchForm.on('submit', function(e) {
            e.preventDefault();
            const searchTerm = searchInput.val().trim();
            currentSearch = searchTerm;
            currentPage = 1;
            performSearch(searchTerm, currentCategory, 1, true);
            updateURL();
        });
        
        // 搜索建议功能
        searchInput.on('focus', function() {
            showSearchSuggestions();
        });
        
        // 点击其他地方隐藏搜索建议
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.nav-search').length) {
                hideSearchSuggestions();
            }
        });
    }
    
    /**
     * 初始化分类过滤
     */
    function initCategoryFilter() {
        $('.category-item').on('click', function(e) {
            e.preventDefault();
            
            const category = $(this).data('category') || '';
            
            if (category !== currentCategory) {
                // 更新激活状态
                $('.category-item').removeClass('active');
                $(this).addClass('active');
                
                currentCategory = category;
                currentPage = 1;
                performSearch(currentSearch, category, 1, true);
                
                // 更新URL
                updateURL();
            }
        });
    }
    
    /**
     * 执行搜索
     */
    function performSearch(search, category, page, replace = false) {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();
        
        const data = {
            action: 'nav115_search',
            search: search,
            category: category,
            page: page,
            nonce: nav115_ajax.nonce
        };
        
        $.ajax({
            url: nav115_ajax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    const $container = $('#search-results');
                    
                    if (replace || page === 1) {
                        // 替换内容
                        $container.html(response.data.content);
                        
                        // 滚动到顶部
                        $('html, body').animate({
                            scrollTop: $container.offset().top - 100
                        }, 500);
                    } else {
                        // 追加内容
                        $container.append(response.data.content);
                    }
                    
                    // 更新加载更多按钮状态
                    updateLoadMoreButton(response.data.max_pages, page);
                    
                    // 更新页面状态
                    currentPage = page;
                    
                    // 更新结果统计
                    updateResultStats(response.data.found_posts, search, category);
                    
                    // 动画效果
                    animateNewResults();
                }
            },
            error: function() {
                showError('搜索失败，请稍后重试');
            },
            complete: function() {
                isLoading = false;
                hideLoading();
            }
        });
    }
    
    /**
     * 初始化无限滚动
     */
    function initInfiniteScroll() {
        let scrollTimeout;
        
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            
            scrollTimeout = setTimeout(function() {
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                const scrollTop = $(window).scrollTop();
                
                // 距离底部200px时触发加载
                if (scrollTop + windowHeight >= documentHeight - 200) {
                    loadMoreSites();
                }
            }, 100);
        });
    }
    
    /**
     * 初始化加载更多按钮
     */
    function initLoadMore() {
        $('#load-more-btn').on('click', function() {
            loadMoreSites();
        });
    }
    
    /**
     * 加载更多网站
     */
    function loadMoreSites() {
        if (isLoading) return;
        
        const nextPage = currentPage + 1;
        performSearch(currentSearch, currentCategory, nextPage, false);
    }
    
    /**
     * 显示搜索建议
     */
    function showSearchSuggestions() {
        // 获取热门搜索词
        const suggestions = [
            '设计工具', '开发工具', '在线编辑', '图片处理', 
            'AI工具', '数据分析', '云服务', '学习资源'
        ];
        
        let suggestionsHtml = '<div class="search-suggestions">';
        suggestions.forEach(function(suggestion) {
            suggestionsHtml += `<div class="suggestion-item" data-suggestion="${suggestion}">${suggestion}</div>`;
        });
        suggestionsHtml += '</div>';
        
        $('.nav-search').append(suggestionsHtml);
        
        // 绑定点击事件
        $('.suggestion-item').on('click', function() {
            const suggestion = $(this).data('suggestion');
            $('#search-input').val(suggestion);
            currentSearch = suggestion;
            currentPage = 1;
            performSearch(suggestion, currentCategory, 1, true);
            updateURL();
            hideSearchSuggestions();
        });
    }
    
    /**
     * 隐藏搜索建议
     */
    function hideSearchSuggestions() {
        $('.search-suggestions').remove();
    }
    
    /**
     * 更新URL
     */
    function updateURL() {
        const url = new URL(window.location);
        
        if (currentSearch) {
            url.searchParams.set('s', currentSearch);
        } else {
            url.searchParams.delete('s');
        }
        
        if (currentCategory) {
            url.searchParams.set('category', currentCategory);
        } else {
            url.searchParams.delete('category');
        }
        
        // 删除page参数
        url.searchParams.delete('paged');
        
        window.history.replaceState({}, '', url);
    }
    
    /**
     * 更新加载更多按钮状态
     */
    function updateLoadMoreButton(maxPages, currentPage) {
        const $loadMoreContainer = $('.load-more-container');
        const $loadMoreBtn = $('#load-more-btn');
        
        if (currentPage < maxPages) {
            $loadMoreContainer.show();
            $loadMoreBtn.prop('disabled', false).text('加载更多');
        } else {
            $loadMoreContainer.hide();
        }
    }
    
    /**
     * 更新结果统计
     */
    function updateResultStats(foundPosts, search, category) {
        let statsText = `共找到 ${foundPosts} 个网站`;
        
        if (search) {
            statsText += ` (搜索: "${search}")`;
        }
        
        if (category) {
            const categoryName = $(`.category-item[data-category="${category}"]`).text().replace(/\(\d+\)/, '').trim();
            if (categoryName) {
                statsText += ` (分类: ${categoryName})`;
            }
        }
        
        // 在搜索结果上方显示统计信息
        let $stats = $('.search-stats');
        if ($stats.length === 0) {
            $stats = $('<div class="search-stats"></div>');
            $('#search-results').before($stats);
        }
        
        $stats.html(statsText);
    }
    
    /**
     * 显示加载状态
     */
    function showLoading() {
        if ($('.loading').length === 0) {
            const loadingHtml = `
                <div class="loading">
                    <div class="spinner"></div>
                    <p>正在搜索...</p>
                </div>
            `;
            $('#search-results').append(loadingHtml);
        }
    }
    
    /**
     * 隐藏加载状态
     */
    function hideLoading() {
        $('.loading').remove();
    }
    
    /**
     * 显示错误信息
     */
    function showError(message) {
        const errorHtml = `
            <div class="error-message">
                <h3>出现错误</h3>
                <p>${message}</p>
            </div>
        `;
        $('#search-results').html(errorHtml);
    }
    
    /**
     * 动画效果
     */
    function animateNewResults() {
        $('.site-card').each(function(index) {
            $(this).css({
                'opacity': 0,
                'transform': 'translateY(20px)'
            }).delay(index * 50).animate({
                'opacity': 1
            }, 600).css('transform', 'translateY(0)');
        });
    }
    
    /**
     * 初始化返回顶部
     */
    function initBackToTop() {
        const $backToTop = $('#back-to-top');
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.fadeIn();
            } else {
                $backToTop.fadeOut();
            }
        });
    }
    
    /**
     * 初始化移动端菜单
     */
    function initMobileMenu() {
        // 移动端分类菜单滚动
        const $categoriesList = $('.categories-list');
        
        // 添加触摸滚动支持
        if ($categoriesList.length && window.innerWidth <= 768) {
            $categoriesList.addClass('mobile-scroll');
        }
        
        // 窗口大小改变时重新检查
        $(window).resize(function() {
            if (window.innerWidth <= 768) {
                $categoriesList.addClass('mobile-scroll');
            } else {
                $categoriesList.removeClass('mobile-scroll');
            }
        });
    }
    
    /**
     * 网站卡片交互效果
     */
    $(document).on('mouseenter', '.site-card', function() {
        $(this).addClass('hover-effect');
    }).on('mouseleave', '.site-card', function() {
        $(this).removeClass('hover-effect');
    });
    
    /**
     * 网站图标错误处理
     */
    $(document).on('error', '.site-icon img', function() {
        const $this = $(this);
        const siteName = $this.attr('alt') || '';
        const firstChar = siteName.charAt(0).toUpperCase();
        
        $this.hide().after(`
            <div class="site-icon-fallback">
                ${firstChar || '?'}
            </div>
        `);
    });
    
    /**
     * 键盘快捷键
     */
    $(document).keydown(function(e) {
        // Ctrl/Cmd + K 聚焦搜索框
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 75) {
            e.preventDefault();
            $('#search-input').focus();
        }
        
        // ESC 键清空搜索
        if (e.keyCode === 27) {
            $('#search-input').val('').focus();
            if (currentSearch) {
                currentSearch = '';
                currentPage = 1;
                performSearch('', currentCategory, 1, true);
                updateURL();
            }
        }
    });
    
})(jQuery);

// 额外的CSS样式
const additionalCSS = `
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 1000;
        margin-top: 5px;
    }
    
    .suggestion-item {
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-bottom: 1px solid var(--border-color);
    }
    
    .suggestion-item:hover {
        background: var(--bg-color);
    }
    
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    .search-stats {
        background: var(--bg-color);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        color: #666;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .error-message {
        text-align: center;
        padding: 50px 20px;
        color: #999;
    }
    
    .error-message h3 {
        color: var(--accent-color);
        margin-bottom: 10px;
    }
    
    .mobile-scroll {
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .mobile-scroll::-webkit-scrollbar {
        display: none;
    }
    
    .mobile-scroll .category-item {
        scroll-snap-align: start;
    }
    
    .site-icon-fallback {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-color);
        color: var(--text-color);
        font-weight: bold;
        font-size: 16px;
        border-radius: 10px;
    }
    
    .hover-effect {
        transform: translateY(-2px) !important;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .loading p {
        animation: pulse 1.5s infinite;
    }
`;

// 动态添加CSS
if (typeof document !== 'undefined') {
    const style = document.createElement('style');
    style.textContent = additionalCSS;
    document.head.appendChild(style);
}