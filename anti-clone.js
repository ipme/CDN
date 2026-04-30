/**
 * 代码来源：https://wuqishi.com/anti-mirror-defense-guide/
 */
(function() {
    'use strict';

    var CONFIG = {
        encodedHost1: 'bW9jLnp6enRh',   // atzzz.com
        encodedHost2: 'bW9jLnNzcnFx',   // qqrss.com

        allowedSubdomains: ['www', 'blog', 'cdn', 'img', 'static'],
        redirectDelay: 200,
        showAlert: true,
        keepPath: true,
        keepSearch: true,
        keepHash: false
    };

    try {
        var realHost1 = atob(CONFIG.encodedHost1).split('').reverse().join('');
        var realHost2 = atob(CONFIG.encodedHost2).split('').reverse().join('');
        var currentHost = window.location.hostname.toLowerCase().split(':')[0];

        var isValid = false;
        var allowedDomains = [];

        allowedDomains.push(realHost1, 'www.' + realHost1);
        allowedDomains.push(realHost2, 'www.' + realHost2);

        if (CONFIG.allowedSubdomains) {
            CONFIG.allowedSubdomains.forEach(function(sub) {
                if (sub && sub !== 'www') {
                    allowedDomains.push(sub + '.' + realHost1);
                    allowedDomains.push(sub + '.' + realHost2);
                }
            });
        }

        for (var i = 0; i < allowedDomains.length; i++) {
            if (currentHost === allowedDomains[i].toLowerCase()) {
                isValid = true;
                break;
            }
        }

        if (!isValid) {
            if (currentHost.endsWith('.' + realHost1) || currentHost.endsWith('.' + realHost2)) {
                isValid = true;
            }
        }

        if (!isValid) {
            if (window.stop) window.stop();
            document.body.innerHTML = '';

            var targetUrl = 'https://' + realHost1;
            if (CONFIG.keepPath) targetUrl += window.location.pathname;
            if (CONFIG.keepSearch && window.location.search) targetUrl += window.location.search;

            if (CONFIG.showAlert) {
                try {
                    alert('⚠️ 安全警告\n\n您正在访问非法镜像网站，已自动拦截！\n即将跳转到官方网站。');
                } catch(e){}
            }

            setTimeout(function() {
                window.location.replace(targetUrl);
            }, CONFIG.redirectDelay);
        }

    } catch (e) {
        console.debug('[AntiMirror] 已忽略错误:', e.message);
    }
})();
