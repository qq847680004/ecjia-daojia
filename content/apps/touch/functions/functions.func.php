<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * touch共用功能加载
 */
function touch_common_loading() {

	/* 商店关闭了，输出关闭的消息 */
	if (ecjia::config('wap_config') == 0) {
		RC_Hook::do_action('ecjia_shop_closed');
	}

    RC_Loader::load_theme('extras/class/touch/touch_page.class.php');

    RC_Lang::load('touch/common');
    RC_Lang::load('touch/user');
    
    //判断是否显示头部和底部
    if (!empty($_GET['hidenav']) && !empty($_GET['hidetab'])) {
        RC_Cookie::set('hideinfo', 1, array('expire' => 360000));
    }elseif (isset($_GET['hidenav']) && isset($_GET['hidetab']) && $_GET['hidenav'] == 0 && $_GET['hidetab'] == 0) {
        RC_Cookie::delete('hideinfo');
    }

    if (RC_Cookie::get('hideinfo')) {
        ecjia_front::$view_object->assign('hideinfo', 1);
    }

    if (!empty($_GET['hidenav'])) {
        ecjia_front::$view_object->assign('hidenav', intval($_GET['hidenav']));
    }
    if (!empty($_GET['hidetab'])) {
        ecjia_front::$view_object->assign('hidetab', intval($_GET['hidetab']));
    }
    $stylename_code = RC_Hook::apply_filters('ecjia_theme_stylename_code', 'stylename');
    $curr_style = ecjia::config($stylename_code) ? 'style_'.ecjia::config($stylename_code).'.css' : 'style.css';
    ecjia_front::$view_object->assign('curr_style', $curr_style);

    // 提供APP下载广告的配置项
    $shop_app_icon = ecjia::config('shop_app_icon');
    !empty($shop_app_icon) && ecjia_front::$controller->assign('shop_app_icon', RC_Upload::upload_url() . '/' . $shop_app_icon);
}



RC_Hook::add_action('ecjia_front_finish_launching', 'touch_common_loading');

RC_Hook::add_filter('ecjia_theme_template_code', function() {
    return ecjia_touch::STORAGEKEY_template;
});
RC_Hook::add_filter('ecjia_theme_stylename_code', function() {
    return ecjia_touch::STORAGEKEY_stylename;
});
RC_Hook::add_filter('page_title_suffix', function ($suffix) {
	return ;
});

/**
 * 设置api session id
 * @param string $session_id session id
 * @return session_id
 */
function set_touch_session_id($session_id) {
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        return $_GET['token'];
    }
    return ;
}
RC_Hook::add_filter('ecjia_front_session_id', 'set_touch_session_id');

/*
 * ========================================
 * API模型加载
 * ========================================
 */
collect(config('app-touch::class_autoload', []))->each(function($item, $key) {
    RC_Hook::add_action('class_'.$key, function () use ($item) {
        RC_Package::package('app::touch')->loadModel($item, false);
    });
});

// end
