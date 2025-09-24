<?php
//===========================================
//ShopEX Main Mod Classes
//Created by Zhirov Sergey in September 2014
//===========================================
class ShopMod {
	public $modName =    "ShopEX";
	public $author =     "Sergey Zhiov";
	public $version =    "1.0";
	public $year =       "2014-2015";
	private $beta =      true;

	public function __construct() {
		global $shop;

		//TODO проверка лицензии

		if(!file_exists(MCR_ROOT.'shop.cfg.php')) require (MCR_ROOT . 'instruments/shop_mod/default.cfg.php');
			else include (MCR_ROOT.'shop.cfg.php');
	}
	public function install() {
		global $shop, $db;
		$db->execute("CREATE TABLE IF NOT EXISTS `shop_items` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`type` VARCHAR(80) NOT NULL,
				`item` VARCHAR(80) NOT NULL,
				`extra` TEXT NULL ,
				`title` VARCHAR(255) NOT NULL,
				`cid` BIGINT(20) NOT NULL DEFAULT '1',
				`pic` VARCHAR(255) NOT NULL DEFAULT '/style/shop/img/missing_texture.png',
				`description` TEXT NOT NULL,
				`price` DOUBLE(64,2) NOT NULL,
				`realprice` TINYINT(1) NOT NULL DEFAULT '0',
				`discount` DOUBLE(64,2) NOT NULL DEFAULT '0.00',
				`num` INT(10) NOT NULL DEFAULT '1',
				`server` BIGINT(20) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$db->execute("CREATE TABLE IF NOT EXISTS `shop_servers` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) NOT NULL,
				`pic` VARCHAR(255) NOT NULL DEFAULT '/style/shop/img/missing_texture.png',
				`url` VARCHAR(255) NOT NULL,
				`description` TEXT NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `Url` (`url`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$db->execute("CREATE TABLE IF NOT EXISTS `shop_cats` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) NOT NULL,
				`url` VARCHAR(255) NOT NULL,
				`priority` BIGINT(20) NOT NULL DEFAULT 0,
				`description` TEXT NOT NULL,
				`system` TINYINT(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE KEY `Url` (`url`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$db->execute("INSERT INTO `shop_cats` (`id`, `title`, `priority`, `url`, `description`, `system`) VALUES (1, 'Без категории', 0, 'unsorted', 'Некатегоризированные товары', 1);");
		$db->execute("CREATE TABLE IF NOT EXISTS `shop_keys` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`key` VARCHAR(80) NOT NULL,
				`amount` BIGINT(20) NOT NULL,
				`price` DOUBLE(64,2) NOT NULL,
				`realprice` TINYINT(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE KEY `Url` (`key`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$db->execute("CREATE TABLE IF NOT EXISTS `shop_keys_log` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`kid` BIGINT(20) NOT NULL,
				`pid` BIGINT(20) NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$shop['install'] = false;
		$this->UpdateConfig();
	}

	public function UpdateConfig()
	{
		global $shop;

		$txt = '<?php' . PHP_EOL;
		$txt .= '$shop = ' . var_export($shop, true) . ';' . PHP_EOL;
		$txt .= '/* Этот файл сгенерирован модулем ' . $this->modName . ' ' . $this->version . ' */' . PHP_EOL;
		$txt .= '?>';

		if (file_put_contents(MCR_ROOT . 'shop.cfg.php', $txt) === false) return false;
		return true;
	}

	public function update() {
//Задел под будущие версии
	}

	public function CheckForUpdates() {

	}

	public function IsLicensed () {

	}
	public function ShowProtection () {
		$bIn6 = "ZWNobyAoIjwhLS0gd2";
		$bInb = "ViTUNSZXggU2hvcEVY";
		$blhb = "WVdaMFMyOWlZV3gwVF";
		$b7h6 = "IEJFVEEgVEVTVElORy";
		$b7hb = "dmNEQmsiIC4gIiAtLT4iKTsgICAg";
		$blnb = "ZKcWFXODRhR3BrYnpo";
		$blh6 = "AiIC4gIlRHOXlaRU55";
		$b7hb = "Mk4zbHDvu78iIC4gIiAtLT4iKTsgICAg";
		$blhb = "UjJGdFpWUkxhR2wxTj";
		$blh6 = "AiIC4gIlIyRnRaVlJM";
		$bIn6 = "ZWNobyAoIjwhLS0gd2";
		$bInb = "ViTUNSZXggU2hvcEVY";
		$blnb = "NsbmVXZDFObkptTlhV";
		$b7h6 = "IEJFVEEgVEVTVElORy";
		$b7hb = "NVozVTIiIC4gIiAtLT4iKTsgICAg";
		$blh6 = "AiIC4gImJXbHphR0V4";
		$bInb = "ViTUNSZXggU2hvcEVY";
		$b7h6 = "IEJFVEEgVEVTVElORy";
		$blnb = "RRd09ESm9hWFUzZVdk";
		$blhb = "TkRBNE1tMXBjMmhoTV";
		$bIn6 = "ZWNobyAoIjwhLS0gd2";
		$b7hb = "cU4yaHBjdz09IiAuICIgLS0+Iik7ICAgICAg";
		$bIn6 = "ZWNobyAoIjwhLS0gd2";
		$bInb = "ViTUNSZXggU2hvcEVY";
		$b7h6 = "IEJFVEEgVEVTVElORy";
		$blh6 = "AiIC4gIlJHRnlhMEYy";
		$blhb = "Wlc1blpYSjJZV1oxYT";
		$blnb = "JFMGQzUTVaMjl6Tldk";
		eval (base64_decode($bIn6 . $bInb . $b7h6 . $blh6 . $blhb . $blnb . $b7hb));
	}
} 