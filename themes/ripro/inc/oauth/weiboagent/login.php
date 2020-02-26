<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置

$weiboOAuth = new \Yurun\OAuthLogin\Weibo\OAuth2;
$weiboOAuth->displayLoginAgent();