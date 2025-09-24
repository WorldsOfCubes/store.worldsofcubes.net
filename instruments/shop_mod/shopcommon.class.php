<?php
//===========================================
//ShopEX Common Classes
//Created by Zhirov Sergey in September 2014
//===========================================
class ShopItem {
	public $id;
	public $title;
	public $description;
	public $down_url;
	public $git;
	public $infinite;
	public $published;
	public $cid;
	public $author;
	public $author_verified;
	public $author_name;
	public $pic;
	public $price;
	public $discount;
	public $num;
	public $server;
	public $secucode;
	public $earned;
	public function __construct($id = null) {
		global $db, $bd_names, $bd_users;
		$query = $db->execute("SELECT `shop_items`.*, `{$bd_names['users']}`.`verified`, `{$bd_names['users']}`.`{$bd_users['login']}` as `author_name` FROM `shop_items`, `{$bd_names['users']}` WHERE `shop_items`.`id`='$id' AND `shop_items`.`author`=`{$bd_names['users']}`.{$bd_users['id']};");
		if($db->num_rows($query) != 1){
			$this->id = -1;
		} else {
			$query = $db->fetch_assoc($query);
			$this->id = $query['id'];
			$this->title = $query['title'];
			$this->description = $query['description'];
			$this->down_url = $query['down_url'];
			$this->git = $query['git'];
			$this->infinite = $query['infinite'];
			$this->author = $query['author'];
			$this->author_verified = $query['verified'];
			$this->author_name = $query['author_name'];
			$this->cid = $query['cid'];
			$this->pic = $query['pic'];
			$this->secucode = $query['secucode'];
			$this->price = $query['price'];
			$this->discount = $query['discount'];
			$this->num = $query['num'];
			$this->server = $query['server'];
			$this->earned = $query['earned'];
			$this->published = $query['published'];
		}
	}
	public function create($title, $description, $down_url, $git, $infinite, $cid, $pic, $price, $discount, $num, $server, $published) {
		global $db, $user;
		if (!$user->id()) {
//			echo 1;
			return 3;
		}
		$check = new ShopServer($server);
		if(!($check->id) or ($check->id < 2 and $user->lvl() < 15)) {
//			echo 2;
			return 1;
		}
		$check = new ShopCat($cid);
		if(!$check->id) {
//			echo 3;
			return 2;
		}
		$query = $db->execute("INSERT INTO `shop_items` (`title`, `description`, `down_url`, `git`, `infinite`, `cid`, `pic`, `price`, `discount`, `num`, `server`, `author`, `published`)"
			. " VALUES ('{$db->safe($title)}', '{$db->safe($description)}', '{$db->safe($down_url)}', '{$db->safe($git)}', '{$db->safe($infinite)}', '{$db->safe($cid)}', '{$db->safe($pic)}', '{$db->safe($price)}', '{$db->safe($discount)}', '{$db->safe($num)}', '{$db->safe($server)}','" . $user->id() . "', '{$db->safe($published)}');");
		if(!$query) {
//			echo 4;
			return 4;
		}
		$id = $db->insert_id();
		$this->__construct($id);
		if ($git) {
			$down_url = escapeshellcmd(escapeshellarg($down_url));
			if(!$down_url) {
				$this->update($title, $description, $infinite, '', 0, $cid, $pic, $price, $discount, $num, $server);
//				echo 5;
				return 5;
			}
			exec("mkdir " . MCR_ROOT . "shop/repos/{$this->id} && cd " . MCR_ROOT . "shop/repos/{$this->id} && git clone $down_url ./", $result);
			$log = "Console output [mkdir " . MCR_ROOT . "shop/repos/{$this->id} && cd " . MCR_ROOT . "shop/repos/{$this->id} && git clone $down_url ./]:\n";
			if (is_array($result))
				foreach($result as $line)
					$log .= $line . "\n";
			vtxtlog($log);
			if ($price) {
				exec("mkdir " . MCR_ROOT . "shop/build/" . $this->id);
				exec("cp -R " . MCR_ROOT . "shop/repos/{$this->id} " . MCR_ROOT . "shop/build/" . $user->name() . "/");
				exec("rm -rf " . MCR_ROOT . "shop/build/" . $this->id . "/{$this->id}/.git");
				exec("cd " . MCR_ROOT . "shop/build/" . $this->id . " && zip -r -9 " . MCR_ROOT . "shop/files/free/wocstore_{$this->id}.zip  ./{$this->id}");
				exec("rm -rf " . MCR_ROOT . "shop/build/" . $this->id);
			}
		}
		return 0;
	}
	public function update($title, $description, $infinite, $down_url, $git, $cid, $pic, $price, $discount, $num, $server, $published) {
		global $db, $user;
		if(!filter_var($down_url, FILTER_VALIDATE_URL))
			if (!$user->id()) return 3;
		$check = new ShopServer($server);
		if(!$check->id and $server != 0) return 1;
		$check = new ShopCat($cid);
		if(!$check->id) return 2;
//		die($git . $this->git . (($git and !$this->git)?1:0) . $_POST['git']);
		if ($git and !$this->git) {
			$down_url = escapeshellcmd(escapeshellarg($down_url));
			if(!$down_url) {
				$this->update($title, $description, $infinite, '', 0, $cid, $pic, $price, $discount, $num, $server);
				return 5;
			}
			exec("mkdir " . MCR_ROOT . "shop/repos/{$this->id} && cd " . MCR_ROOT . "shop/repos/{$this->id} && git clone $down_url ./", $result);
			$log = "Console output [mkdir " . MCR_ROOT . "shop/repos/{$this->id} && cd " . MCR_ROOT . "shop/repos/{$this->id} && git clone $down_url ./]:\n";
			if (is_array($result))
				foreach($result as $line)
					$log .= $line . "\n";
			vtxtlog($log);
		}
		if (!$git and $this->git)
			exec("rm -rf " . MCR_ROOT . "shop/repos/{$this->id}");
		if (!$db->execute("UPDATE `shop_items` SET `title`='{$db->safe($title)}',
												   `description` = '{$db->safe($description)}',
												   `down_url` = '{$db->safe($down_url)}',
												   `git` = '{$db->safe($git)}',
												   `published` = '{$db->safe($published)}',
												   `infinite` = '{$db->safe($infinite)}',
												   `cid` = '{$db->safe($cid)}',
												   `pic` = '{$db->safe($pic)}',
												   `price` = '{$db->safe($price)}',
												   `discount` = '{$db->safe($discount)}',
												   `num` = '{$db->safe($num)}',
												   `server` = {$db->safe($server)} WHERE `id`={$this->id};")) return 4;
		return false;
	}
	private function generate_key() {
		$allchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$string = "";

		mt_srand( (double) microtime() * 1000000 );

		for ($a = 0; $a <= 5; $a++) {
			for ($b = 0; $b <= 5; $b++)
				$string .= $allchars{mt_rand(0, strlen($allchars) - 1)};
			if ($a != 5) $string .= '-';
		}

		return $string;
	}
	public function buy($project) {
		global $db, $user;
		$price = ($this->price - $this->discount);
		if($user->getMoney() < $price)
			return 2;
		$result = $db->execute("INSERT INTO `shop_cart` (`title`, `user`, `project`,`iid`, `key`, `secucode`, `date`, `valid_until`, `paid`)"
			. " VALUES ('{$this->title}', '" . $user->name() . "', '{$db->safe($project)}', {$this->id}, '{$this->generate_key()}', '" . hash('SHA256', $this->generate_key()) . "', NOW(), NOW() + INTERVAL 1 YEAR, $price);");
		if(!$result) return 3;
		$db->execute("UPDATE `shop_items` SET `earned`=`earned`+$price WHERE `id`={$this->id}");
		if ($this->discount and $this->num) {
			$db->execute("UPDATE `shop_items` SET `num`=`num`-1 WHERE `id`={$this->id}");
			$this->num -= 1;
		}
		$pl = new User($this->author);
		$pl->addEcon($price*0,9);
		$user->addMoney(-$price);
		return 0;
	}
	public function delete() {
		global $db;
		if ($this->id < 1) return;
		$db->execute("DELETE FROM `shop_items` WHERE `id`='".$this->id."';");
		if ($this->git)
			exec("rm -rf " . MCR_ROOT . "shop/repos/{$this->id}");
	}
}

class ShopCartItem {
	public $id;
	public $key;
	public $url;
	public $item;
	public $user;
	public $date;
	public $paid;
	public $account;
	public $expired;
	public $project;
	public $canceled;
	public $secucode;
	public $valid_until;
	public function __construct($arg, $what = 'id') {
		global $db, $bd_users;
		if($what == 'mysql') {
			if (!$arg or !$db->num_rows($arg)) {
				return 1;
			}
			$this->mysql = $arg;
			if (!$query = $db->fetch_array($this->mysql))
				return 2;
		} else {
			$query = $db->execute("SELECT *, `valid_until`<NOW() as `expired` FROM `shop_cart` WHERE `{$db->safe($what)}`='{$db->safe($arg)}';");
			if (!$query or ($db->num_rows($query) != 1))
				return 3;
			$query = $db->fetch_array($query);
		}
		$this->id = $query['id'];
		$this->key = $query['key'];
		$this->user = $query['user'];
		$this->date = $query['date'];
		$this->item = new ShopItem($query['iid']);
		$this->paid = $query['paid'];
		$this->account = new User($query['user'], $bd_users['login']);
		$this->expired = $query['expired'];
		$this->project = $query['project'];
		$this->updated = $query['updated'];
		$this->canceled = $query['canceled'];
		$this->secucode = $query['secucode'];
		$this->valid_until = $query['valid_until'];
		$this->url = ($query['updated'] == null or $this->expired)? null: "shop/files/paid/" . $this->account->name() . "_" . $this->project . "_{$this->item->id}.zip";
		if ($this->expired and $this->updated != null)
			$this->cleanup();
		return 0;
	}
	public function cleanup () {
		global $db;
		$this->updated = null;
		unlink(MCR_ROOT . $this->url);
		$db->execute("UPDATE `shop_cart` SET `updated`=NULL WHERE `id`='{$this->id}';");
	}
	public function mysql_next () {
		return ($this->__construct($this->mysql, 'mysql'))?false:true;
	}
	public function change_domain ($new_domain) {
		global $db;
		if (!$db->execute("UPDATE `shop_cart` SET `project`='{$db->safe($new_domain)}' WHERE `id`='{$this->id}';")) {
			return 1;
		}
		if ($this->updated != NULL) $this->cleanup();
		$this->project = $db->safe($new_domain);
		if ($this->updated != NULL) $this->upgrade();
		return 0;
	}
	public function upgrade() {
		global $db;
		if(!$this->item->infinite and $this->expired)
			return 1;
		$db->execute("UPDATE `shop_cart` SET `updated`=NOW() WHERE `id`='{$this->id}';");
		if ($this->item->git) {
			exec("mkdir " . MCR_ROOT . "shop/build/" . $this->account->name());
			exec("cp -R " . MCR_ROOT . "shop/repos/{$this->item->id} " . MCR_ROOT . "shop/build/" . $this->account->name() . "/");
			exec("rm -rf " . MCR_ROOT . "shop/build/" . $this->account->name() . "/{$this->item->id}/.git");
			if(file_exists(MCR_ROOT . "shop/additional_generators/{$this->item->id}.php"))
				include MCR_ROOT . "shop/additional_generators/{$this->item->id}.php";
			exec("cd " . MCR_ROOT . "shop/build/" . $this->account->name() . " && zip -r -9 " . MCR_ROOT . "shop/files/paid/" . $this->account->name() . "_" . escapeshellcmd(escapeshellarg($this->project)) . "_{$this->item->id}.zip  ./{$this->item->id}");
			exec("rm -rf " . MCR_ROOT . "shop/build/" . $this->account->name() . "/{$this->item->id}");
		} else {
//			ex
			return 2;
		}
		return 0;
	}
	public function take () {
		header('Content-Type: application/zip');
		header('Cache-Control:no-cache, must-revalidate');
		header('Expires:0');
		header('Pragma:no-cache');
		header('Content-Length:' . filesize(MCR_ROOT.$this->url));
		header('Content-Disposition: attachment; filename="' . $this->account->name() . '_' . $this->project . '_' . $this->item->id . '.zip"');
		header('Content-Transfer-Encoding:binary');
		readfile(MCR_ROOT . $this->url);
		exit;
	}
	public function renew () {
		global $db;
		$pay = $this->item->price + $this->item->discount;
		if ($this->account->getMoney() < $pay)
			return 1;
		$this->account->addMoney(-$pay);
		$author = new User($this->item->author);
		$author->addEcon($pay * 0.9);
		$db->execute("UPDATE `shop_cart` SET `earned`=`earned` + $pay, `valid_until` = `valid_until` + INTERVAL 1 YEAR WHERE `id`={$this->id}");
		return 0;
	}
	public function cancel() {
		global $db;
		if($this->canceled)
			return true;
		if ($this->updated == null) {
			$author = new User($this->item->author);
			if ($author->getEcon() >= -0.9 * $this->paid) {
				$db->execute("UPDATE `shop_cart` SET `canceled`=1 WHERE `id`='{$this->id}';");
				$author->addEcon(-0.9 * $this->paid);
				$this->account->addMoney($this->paid);
				return true;
			}
		}
		return false;
	}

}

class ShopServer {
	public $id;
	public $name;
	public $description;
	public $url;
	public $pic;
	public function __construct($someth = null, $what = "id") {
		global $db;
		$query = $db->execute("SELECT * FROM `shop_servers` WHERE `$what`='{$db->safe($someth)}';");
		if($db->num_rows($query) != 1){
			$this->id = -1;
		} else {
			$query = $db->fetch_assoc($query);
			$this->id = $query['id'];
			$this->name = $query['title'];
			$this->url = $query['url'];
			$this->description = $query['description'];
			$this->pic = $query['pic'];
		}
	}
	public function create($name, $url, $description, $pic) {
		global $db;
		$query = $db->execute("INSERT INTO `shop_servers` (`title`, `url`, `description`, `pic`) VALUES ('{$db->safe($name)}', '{$db->safe($url)}', '{$db->safe($description)}', '{$db->safe($pic)}');");
		if(!$query) return 1;
		$id = $db->insert_id();
		$query = $db->execute("CREATE TABLE `shop_cart_$id` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) NOT NULL,
				`item` VARCHAR(80) NOT NULL,
				`extra` TEXT NULL ,
				`type` VARCHAR(80) NOT NULL,
				`player` VARCHAR(255) NOT NULL,
				`amount` BIGINT(20) NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		if(!$query) {
			$this->id = $id;
			$this->delete();
			return 2;
		}
		$this->__construct($id);
		return 0;
	}
	public function update($name, $url, $description, $pic) {
		global $db;
		return ($db->execute("UPDATE `shop_servers` SET  `title`='{$db->safe($name)}',
														 `url`='{$db->safe($url)}',
														 `description` = '{$db->safe($description)}',
														 `pic` = '{$db->safe($pic)}' WHERE `id`={$this->id};"))? true : false;
	}
	public function delete() {
		global $db;
		$db->execute("DELETE FROM `shop_servers` WHERE `id`='".$this->id."';");
		$db->execute("DELETE FROM `shop_items` WHERE `server`='".$this->id."';");
		$db->execute("DROP TABLE IF EXISTS `shop_cart_{$this->id}`;");
	}
}

class ShopCat {
	/* Meow */
	public $id;
	public $name;
	public $description;
	public $url;
	public $priority;
	public function __construct($someth = null, $what = "id") {
		global $db;
		$query = $db->execute("SELECT * FROM `shop_cats` WHERE `$what`='" . $someth . "';");
		if($db->num_rows($query) != 1){
			$this->id = -1;
		} else {
			$query = $db->fetch_assoc($query);
			$this->id = $query['id'];
			$this->name = $query['title'];
			$this->url = $query['url'];
			$this->description = $query['description'];
			$this->priority = $query['priority'];
		}
	}
	public function create($name, $url, $description, $priority) {
		global $db;
		if(!preg_match("/^[0-9]+$/", $priority)) return 3;
		if(is_int($url)) return 2;
		$query = $db->execute("INSERT INTO `shop_cats` (`title`, `url`, `description`, `priority`) VALUES ('{$db->safe($name)}', '{$db->safe($url)}', '{$db->safe($description)}', {$db->safe($priority)});");
		if(!$query) return 1;
		$this->id = $db->insert_id();
		$this->__construct($this->id);
		return 0;
	}
	public function update($name, $url, $description, $priority) {
		global $db;
		if(!preg_match("/^[0-9-]+$/", $priority)) return 3;
		if(is_int($url)) return 2;
		return ($db->execute("UPDATE `shop_cats` SET `title`='{$db->safe($name)}',
														`url`='{$db->safe($url)}',
														 `description` = '{$db->safe($description)}',
														 `priority` = {$db->safe($priority)} WHERE `id`={$this->id};"))? 0 : 1;
	}
	public function delete() {
		global $db;
		if ($this->id < 1) return;
		$db->execute("UPDATE `shop_items` SET `cid`=1 WHERE `cid`={$this->id};");
		$db->execute("DELETE FROM `shop_cats` WHERE `id`='".$this->id."';");
	}
}

class ShopKey {
	public $amount;
	public $price;
	public $is_real;
	public $key;
	public $id;
	public function __construct($key) {
		global $db;
		$query = $db->execute("SELECT * FROM `shop_keys` WHERE `key`='" . $db->safe($key) . "';");
		if($db->num_rows($query) != 1){
			$this->amount = -1;
			$this->key = $key;
		} else {
			$query = $db->fetch_assoc($query);
			$this->amount = $query['amount'];
			$this->price = $query['price'];
			$this->is_real = $query['realprice'];
			$this->key = $query['key'];
			$this->id = $query['id'];
		}
	}
	public function create($amount, $price, $is_real) {
		global $db;
		if(!$this->check()) return 2;
		if($db->execute("INSERT INTO `shop_keys` (`amount`, `key`, `realprice`, `price`) VALUES ({$db->safe($amount)}, '{$this->key}', {$db->safe($is_real)}, {$db->safe($price)})")) {
			$this->__construct($this->key);
			return 0;
		} else return 1;
	}
	public function update($amount, $price, $is_real)
	{
		global $db;
		return ($db->execute("UPDATE `shop_keys` SET `amount`='{$db->safe($amount)}',
													 `price`='{$db->safe($price)}',
													 `realprice` = '{$db->safe($is_real)}'
													  WHERE `id`={$this->id};")) ? 0 : 1;
	}
	private function check() {
		$key = explode("-", $this->key);
		if(is_array($key) and (count($key) == 4)) {
			for($i = 0; $i <= 3; $i++){
				if((!preg_match("/^[A-Z0-9]+$/", $key[$i])) or (strlen($key[$i]) != 5))
					return false;
			}
			return true;
		}

		return false;
	}
	public function redeem() {
		global $user, $db;
		if($user and ($this->amount > 0) and $this->check()) {
			$db->execute("UPDATE `shop_keys` SET `amount`=`amount`-1;");
			$db->execute("INSERT INTO `shop_keys_log` (`pid`, `kid`) VALUES (" . $user->id() . ", {$this->id})");
			($this->is_real)? $user->addMoney($this->price) : $user->addEcon($this->price);
			return true;
		}
		return false;
	}
	public function delete () {
		global $db;
		$db->execute("DELETE FROM `shop_keys` WHERE `id`='".$this->id."';");
	}
}