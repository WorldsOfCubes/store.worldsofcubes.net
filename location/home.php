<?php
if (!defined('MCR'))
	exit;

$page = 'Домашняя страница';
$sub_dir = 'home/';
$path = "shop/";
$additional_where = '';
ob_start();
if (!$user or $user->lvl() < 15)
	$additional_where .= ' AND `published`=1';


loadTool("shop.class.php", "shop_mod/");
loadTool("shopcommon.class.php", "shop_mod/");
$shop_mod = new ShopMod();
include View::Get("row_begin.html", $sub_dir);

$server = new ShopServer(1);
$howb = 8;
$query = $db->execute("SELECT *, `date` > NOW() - INTERVAL 1 MONTH as `new`
								FROM `shop_items`
								WHERE 1$additional_where
								ORDER BY `shop_items`.`date` DESC
								LIMIT 1");
$title = "Новинка";
include View::Get("sblock_begin.html", $sub_dir);
$how = 12;
for ($i = 0; $item = $db->fetch_array($query); $i = ($i + 1) % 1) {
	if ($i == 0) include View::Get("row_begin.html", $path);
	include View::Get("item.html", $path);
	if ($i == 0) include View::Get("row_end.html", $path);
}
if ($i != 0) include View::Get("row_end.html", $path);
include View::Get("sblock_end.html", $sub_dir);

$howb = 4;
$query = $db->execute("SELECT *, `date` > NOW() - INTERVAL 1 MONTH as `new`
								FROM `shop_items`
								WHERE `discount` > 0$additional_where
								ORDER BY RAND()
								LIMIT 1");
if($db->num_rows($query)) {
	$title = "Скидка";
	include View::Get("sblock_begin.html", $sub_dir);
	$how = 12;
	for ($i = 0; $item = $db->fetch_array($query); $i = ($i + 1) % 1) {
		if ($i == 0) include View::Get("row_begin.html", $path);
		include View::Get("item.html", $path);
		if ($i == 0) include View::Get("row_end.html", $path);
	}
	if ($i != 0) include View::Get("row_end.html", $path);
	include View::Get("sblock_end.html", $sub_dir);
}

include View::Get("row_end.html", $sub_dir);


$query = $db->execute("SELECT *, `date` > NOW() - INTERVAL 1 MONTH as `new`
								FROM `shop_items`
								WHERE 1$additional_where
								ORDER BY `shop_items`.`earned` DESC
								LIMIT 4");
$title = "Популярные";
include View::Get("block_begin.html", $sub_dir);
$how = 3;
for ($i = 0; $item = $db->fetch_array($query); $i = ($i + 1) % 4) {
	if ($i == 0) include View::Get("row_begin.html", $path);
	include View::Get("item.html", $path);
	if ($i == 3) include View::Get("row_end.html", $path);
}
if ($i != 0) include View::Get("row_end.html", $path);
include View::Get("block_end.html", $sub_dir);
$content_main = ob_get_clean();