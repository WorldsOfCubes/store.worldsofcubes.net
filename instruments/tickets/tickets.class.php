<?php
class Ticket {
	public static function Send ($text, $account, $adm = false) {
		global $db, $tickets, $user;
		if (!$user) return 1;
		if (!$account->id()) return 2;
		if(!$adm)
			$type = 0;
		elseif ($user->lvl() >= $tickets['minimal_admin_lvl'])
			$type = 4;
		elseif ($user->lvl() >= $tickets['minimal_moderate_lvl'])
			$type = 3;
		else
			return 1;
		if(!strlen($text)) return 0;
		$db->execute("INSERT INTO `tickets` (`user`, `author`, `message`, `type`, `time`) VALUES (" . $account->id() . ", " . $user->id() . ", '{$db->safe($text)}', $type, NOW())");
		return 0;
	}
	public static function MarkAsViewed($id) {
		global $db;
		return ($db->execute("UPDATE `tickets` SET `type`=1 WHERE `id`=" . $db->safe($id)))? true:false;
	}
	public static function MarkAsClosed($id) {
		global $db;
		return ($db->execute("UPDATE `tickets` SET `type`=2 WHERE `id`=" . $db->safe($id)))? true:false;
	}
	public static function Delete($id) {
		global $db;
		return ($db->execute("DELETE FROM `tickets` WHERE `id`=" . $db->safe($id)))? true:false;
	}
	public static function SetUserComment($user, $comment) {
		global $db;
		if(!($user and $user->id())) return false;
		if(!strlen($comment)) return true;
		return ($db->execute("INSERT INTO `tickets_user_comments` ("
			. "`time`,"
			. "`message`,"
			. "`id`) "
			. "VALUES(NOW(),'".$db->safe($comment)."',". $user->id() .")"
			. "ON DUPLICATE KEY UPDATE `message`='".$db->safe($comment)."',`time`=NOW()"))? true:false;
	}
}