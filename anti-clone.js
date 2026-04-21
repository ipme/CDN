/**
 * 代码来源：https://wuqishi.com/anti-mirror-defense-guide/
 */
(function() {
    'use strict';
    
    // ==================== 配置区（必须修改） ====================
    var CONFIG = {
        encodedHost: 'bW9jLnp6enRh',        
        // 允许的子域名（按需增删）
        allowedSubdomains: ['www', 'blog', 'cdn', 'img', 'static'],
        // 跳转延迟（毫秒，确保alert先显示）
        redirectDelay: 200,
        // 是否显示警告弹窗（true/false）
        showAlert: true,
        // 跳转后是否携带原路径和参数
        keepPath: true,
        keepSearch: true,
        keepHash: false  // 锚点通常不需要
    };
    
    // ==================== 核心逻辑（无需修改） ====================
    try {
        // 解码真实域名：Base64解码 → 字符串反转 → 原文
        var realHost = atob(CONFIG.encodedHost).split('').reverse().join('');
        var currentHost = window.location.hostname.toLowerCase();
        
        // 域名白名单检查
        var isValid = false;
        var checkList = [realHost, 'www.' + realHost];
        
        // 添加配置的子域名
        if (CONFIG.allowedSubdomains && CONFIG.allowedSubdomains.length > 0) {
            CONFIG.allowedSubdomains.forEach(function(sub) {
                if (sub) checkList.push(sub.toLowerCase() + '.' + realHost);
            });
        }
        
        // 检查完全匹配
        for (var i = 0; i < checkList.length; i++) {
            if (currentHost === checkList[i]) {
                isValid = true;
                break;
            }
        }
        
        // 检查多级子域名（如 blog.cdn.wuqishi.com）
        if (!isValid && currentHost.indexOf('.' + realHost) > -1) {
            isValid = true;
        }
        
        // 非法访问处理
        if (!isValid) {
            // 立即停止页面解析
            if (window.stop) {
                window.stop();
            } else if (document.execCommand) {
                document.execCommand('Stop'); // IE9-10兼容
            }
            
            // 清空页面内容（防止镜像站内容显示）
            if (document.body) document.body.innerHTML = '';
            if (document.documentElement) document.documentElement.innerHTML = '';
            
            // 构建跳转URL
            var targetUrl = 'https://' + realHost;
            if (CONFIG.keepPath) {
                targetUrl += window.location.pathname || '';
            }
            if (CONFIG.keepSearch && window.location.search) {
                targetUrl += window.location.search;
            }
            if (CONFIG.keepHash && window.location.hash) {
                targetUrl += window.location.hash;
            }
            
            // 显示警告（可选）
            if (CONFIG.showAlert && window.alert) {
                try {
                    window.alert('⚠️ 安全警告\n\n您正在访问盗版镜像网站，内容可能已被篡改或植入恶意代码！\n\n即将跳转至正版网站：' + realHost);
                } catch(e) {}
            }
            
            // 延迟跳转
            setTimeout(function() {
                if (window.location.replace) {
                    window.location.replace(targetUrl);  // replace不保留历史记录
                } else {
                    window.location.href = targetUrl;    // 兼容旧浏览器
                }
            }, CONFIG.redirectDelay);
            
            // 控制台日志（调试用）
            if (window.console && console.warn) {
                console.warn('[AntiMirror] 已拦截非法访问: ' + currentHost + ' → ' + realHost);
            }
        }
    } catch(e) {
        // 任何错误静默处理，确保不影响正常访问
        if (window.console && console.error) {
            console.error('[AntiMirror] 脚本错误（已忽略）:', e.message);
        }
    }
})();
