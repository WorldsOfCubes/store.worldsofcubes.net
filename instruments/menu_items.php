<?php if (!defined('MCR')) exit;
$menu_items = array (
	0 => 
	array (
		'main' => 
		array (
			'name' => '<i class="glyphicon glyphicon-home"></i> ' . lng('HOME'),
			'url' => '',
			'parent_id' => -1,
			'lvl' => -1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'admin' => 
		array (
			'name' => '<i class="glyphicon glyphicon-wrench"></i> ' . lng('ADM'),
			'url' => '',
			'parent_id' => -1,
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'add_news' => 
		array (
			'name' => lng('ADM_NEW'),
			'url' => Rewrite::GetURL('news_add'),
			'parent_id' => 'admin',
			'lvl' => 1,
			'permission' => 'add_news',
			'active' => false,
			'inner_html' => '',
		),
		'category_news' => 
		array (
			'name' => lng('ADM_CAT'),
			'url' => Rewrite::GetURL(array('control', 'category')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'file_edit' => 
		array (
			'name' => lng('ADM_FILES'),
			'url' => Rewrite::GetURL(array('control', 'filelist')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'control' => 
		array (
			'name' => lng('ADM_USER'),
			'url' => Rewrite::GetURL(array('control', 'user')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'reqests' => 
		array (
			'name' => lng('ADM_REQ'),
			'url' => Rewrite::GetURL('reqests'),
			'parent_id' => 'admin',
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'reg_edit' => 
		array (
			'name' => lng('ADM_REG'),
			'url' => Rewrite::GetURL(array('control', 'ipbans')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'group_edit' => 
		array (
			'name' => lng('ADM_GROUP'),
			'url' => Rewrite::GetURL(array('control', 'group')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'site_edit' => 
		array (
			'name' => lng('ADM_SITE'),
			'url' => Rewrite::GetURL(array('control', 'constants')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
        'forum_edit' =>
            array (
                'name' => lng('FORUM_EDIT'),
                'url' => Rewrite::GetURL(array('control', 'forum')),
                'parent_id' => 'admin',
                'lvl' => 15,
                'permission' => -1,
                'active' => false,
                'inner_html' => '',
            ),
		'donate_edit' => 
		array (
			'name' => lng('ADM_DONATE'),
			'url' => Rewrite::GetURL(array('control', 'donate')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'rcon' => 
		array (
			'name' => lng('ADM_RCON'),
			'url' => Rewrite::GetURL(array('control', 'rcon')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'game_edit' => 
		array (
			'name' => lng('ADM_LAUNCH'),
			'url' => Rewrite::GetURL(array('control', 'update')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'serv_edit' => 
		array (
			'name' => lng('ADM_SRV'),
			'url' => Rewrite::GetURL(array('control', 'servers')),
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'shop_cats_admin' =>
		array (
			'name' => "Категории товаров",
			'url' => "go/shop/cat_adm",
			'parent_id' => 'admin',
			'lvl' => 15,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'shop_keys_admin' =>
			array (
				'name' => "Подарочные ключи",
				'url' => "go/shop/key_adm",
				'parent_id' => 'admin',
				'lvl' => 15,
				'permission' => -1,
				'active' => false,
				'inner_html' => '',
			),
		'tickets_admin' =>
			array (
				'name' => "Управление тикетами",
				'url' => '/go/tickets/admin',
				'parent_id' => 'admin',
				'lvl' => 15,
				'permission' => -1,
				'active' => false,
				'inner_html' => '',
			),
		'shop' =>
			array (
				'name' => '<i class="glyphicon glyphicon-shopping-cart"></i> Все товары',
				'url' => Rewrite::GetURL('shop'),
				'parent_id' => -1,
				'lvl' => 0,
				'permission' => -1,
				'active' => false,
				'inner_html' => '',
			),
	),
	1 => 
	array (
		'tickets' =>
		array (
			'name' => '<i class="fa fa-info-circle"></i> Тикеты',
			'url' => '/go/tickets',
			'parent_id' => -1,
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'settings' =>
		array (
			'name' => '<i class="glyphicon glyphicon-cog"></i> Опции',
			'url' => Rewrite::GetURL('options'),
			'parent_id' => -1,
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'options' =>
		array (
			'name' => 'Настройки аккаунта',
			'url' => Rewrite::GetURL('options'),
			'parent_id' => 'settings',
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'shop_cart' =>
		array (
			'name' => 'Покупки',
			'url' => Rewrite::GetURL('shop/cart'),
			'parent_id' => 'settings',
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
		'exit' => 
		array (
			'name' => '<i class="fa fa-sign-out"></i> ' . lng('EXIT'),
			'url' => 'login.php?out=1',
			'parent_id' => -1,
			'lvl' => 1,
			'permission' => -1,
			'active' => false,
			'inner_html' => '',
		),
	),
);
