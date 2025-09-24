<?php

include('../system.php');
$db = new DB();
$db->connect('pay');

loadTool('log.class.php');

$notification_type = $_POST['notification_type'];
$operation_id = $_POST['operation_id'];
$amount = $_POST['amount'];
$currency = $_POST['currency'];
$datetime = $_POST['datetime'];
$sender = $_POST['sender'];
$codepro = $_POST['codepro'];
$label = $_POST['label'];
$sha1_hash = $_POST['sha1_hash'];

if ($sha1_hash != hash('sha1', $notification_type . '&' . $operation_id . '&' . $amount . '&' . $currency . '&' . $datetime . '&' . $sender . '&' . $codepro . '&' . $donate['up_secret_key'] . '&' . $label)) {
	header("HTTP/1.0 481 BadHash");
	exit('bad hash');
}

loadTool('user.class.php');
$user = new User($label, $bd_users['login']);

if ($user->id() == -1) {
	header("HTTP/1.0 481 BadHash");
	exit('bad user');
}
$user->addMoney($amount);
//$db->execute("UPDATE `{$bd_names['iconomy']}` SET `{$bd_money['bank']}`=`{$bd_money['bank']}`+$summ WHERE `{$bd_money['login']}`='$paymentId'");


vtxtlog($ik_payment_timestamp."\t$paymentId произвел платеж на $amount руб");
header("HTTP/1.0 200 OK");
echo "ok";