<?php

if(!submitcheck('settingsubmit')) {
	
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=grouppower&pmod=g_setting', 'enctype');

	showtableheader('设置');

	showsetting('开启圈子加强功能:', 'setting[gp_allow]', getViewPluginId(), 'radio', 0, 0, '是否启用圈子加强功能');
	
	
	showsubmit('settingsubmit');
	showtablefooter();

	showformfooter();

} else {
    
    
    updateViewPluginId(intval($_GET['setting']['gp_allow']));
    
	cpmsg('setting_update_succeed', 'action=plugins&operation=config&do='.$pluginid.'&identifier=grouppower&pmod=g_setting', 'succeed');
}

function getViewPluginId() {
    global $_G;
    return $_G['setting']['grouppowerpluginidisopen'];
}

function updateViewPluginId($value) {
    $settings = array('grouppowerpluginidisopen' => $value);
    C::t('common_setting')->update_batch($settings);
    updatecache('setting');
}

?>