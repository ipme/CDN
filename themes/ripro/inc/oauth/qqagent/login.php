<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置

$qqOAuth = new \Yurun\OAuthLogin\QQ\OAuth2;
$qqOAuth->displayLoginAgent();
