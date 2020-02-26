<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

$wxOAuth = new \Yurun\OAuthLogin\Weixin\OAuth2;
$wxOAuth->displayLoginAgent();
