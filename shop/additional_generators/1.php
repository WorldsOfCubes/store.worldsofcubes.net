<?php
if (file_exists(MCR_ROOT . "shop/build/" . $this->account->name() . "/{$this->item->id}/instruments/shop_mod/shop.class.php")) {
	$file = file_get_contents(MCR_ROOT . "shop/build/" . $this->account->name() . "/{$this->item->id}/instruments/shop_mod/shop.class.php");
	$file = str_replace('NTJjMjhhNDU2OWJmNTlmNmZhZDlmMzg3MGY3MGZmOTVjZTgwMzVmY2Y4MTE1MWFjMjI2NGE5MDVhOWU3NTAyOQ==', base64_encode($this->secucode), $file);
	file_put_contents(MCR_ROOT . "shop/build/" . $this->account->name() . "/{$this->item->id}/instruments/shop_mod/shop.class.php", $file);
}
