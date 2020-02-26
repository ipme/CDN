<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置
$wxConfig = _cao('oauth_mpweixin');

if (empty($_SESSION['CAO_mpweixin_scene_str'])) {
    wp_die('该功能暂未正式上线');
}
