<?php
if (!defined('MCR')) exit;
if (!defined('EX')) exit ("I'm sorry, but  this mod not compable with webMCR. Please, install its fork <a href=\"http://git.worldsofcubes.net/webmcrex\">webMCRex</a>");
if (empty($user) or $user->lvl() <= 0) { accss_deny(); }
$path = 'tickets/';
loadTool("ticketsmod.class.php", $path);
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
	else $action = 'list';
$ticket_mod = new TicketMod();
if($tickets['install']) $action = 'install';
$p = (isset($_REQUEST['page']))? (int) $_REQUEST['page']:1;
switch ($action) {
	case 'install':
		$ticket_mod->install();
		$content_main = View::ShowStaticPage('install.html', $path);
		$page = lng('INSTALLATION_COMPLETE');
		break;
	case 'list':
		if(isset($_POST['ticket'])) {
			loadTool("tickets.class.php", $path);
			Ticket::Send(TextBase::HTMLDestruct($_POST['ticket']), $user);
		}
		$first = ($p - 1) * $tickets['tickets_by_page'];
		$query = $db->execute("SELECT * FROM `{$bd_names['users']}`, `tickets` WHERE `tickets`.`user`=" . $user->id() . " AND `tickets`.`author`=`{$bd_names['users']}`.`{$bd_users['id']}` ORDER BY `time` DESC LIMIT $first, {$tickets['tickets_by_page']}");
		ob_start();
		include View::Get('user/tickets_head.html', $path);
		while ($temp_ticket = $db->fetch_array($query)) {
			switch($temp_ticket['type']) {
				case 4:
					$before = '<div class="label label-danger">' . lng('TICKET_ADMINISTRATORS') . '</div>';
					break;
				case 3:
					$before = '<div class="label label-success">' . lng('TICKET_MODERATORS') . '</div>';
					break;
				case 2:
					$before = lng('TICKET_CLOSED');
					break;
				case 1:
					$before = lng('TICKET_VIEWED');
					break;
				case 0:
					$before = lng('TICKET_UNVIEWED');
					break;
			}
			$temp_ticket['message'] = nl2br($temp_ticket['message']);
			include View::Get('user/ticket.html', $path);
		}
		$content_main = ob_get_clean();
		$result = $db->execute("SELECT COUNT(*) FROM `tickets` WHERE `user`=" . $user->id());
		$line = $db->fetch_array($result);
		$view = new View("tickets/");
		$content_main .= $view->arrowsGenerator('go/tickets/', $p, $line[0], $tickets['tickets_by_page'], "pagin");
		$page = lng('TICKETS');
		break;
	case 'admin':
		if((($tickets['minimal_moderate_lvl'] < $tickets['minimal_admin_lvl'])? $tickets['minimal_moderate_lvl'] : $tickets['minimal_admin_lvl']) > $user->lvl())
			accss_deny();
		if(isset($_GET['user'])) {
			$account = new User($_GET['user'], $bd_users['login']);
			if(!$account->id()) show_error('404', lng('USER_NOT_FOUND'));
			if(isset($_POST['ticket'])) {
				loadTool("tickets.class.php", $path);
				Ticket::Send(TextBase::HTMLDestruct($_POST['ticket']), $account, true);
			}
			if(isset($_POST['user_comment'])) {
				loadTool("tickets.class.php", $path);
				Ticket::SetUserComment($account, TextBase::HTMLDestruct($_POST['user_comment']));
			}
			loadTool("profile.class.php");
			$query = $db->execute("SELECT * FROM `tickets_user_comments` WHERE `id`=" . $account->id());
			$comment = ($temp = $db->fetch_array($query))? $temp['message']:'';
			if(isset($_POST['id'])) {
				loadTool("tickets.class.php", $path);
				if (isset($_POST['compete_ticket']))
					Ticket::MarkAsClosed($_POST['id']);
				elseif (isset($_POST['viewed_ticket']))
					Ticket::MarkAsViewed($_POST['id']);
				elseif (isset($_POST['delete_ticket']))
					Ticket::Delete($_POST['id']);
			}
			$query = $db->execute("SELECT * FROM `{$bd_names['users']}`, `tickets` WHERE `tickets`.`user`=" . $account->id() . " AND `tickets`.`author`=`{$bd_names['users']}`.`{$bd_users['id']}` ORDER BY `time` DESC");
			ob_start();
			include View::Get('admin/tickets_head.html', $path);
			while ($temp_ticket = $db->fetch_array($query)) {
				switch($temp_ticket['type']) {
					case 4:
						$before = '<div class="label label-danger">' . lng('TICKET_ADMINISTRATORS') . '</div>';
						break;
					case 3:
						$before = '<div class="label label-success">' . lng('TICKET_MODERATORS') . '</div>';
						break;
					case 2:
						$before = lng('TICKET_CLOSED');
						break;
					case 1:
						$before = lng('TICKET_VIEWED');
						break;
					case 0:
						$before = lng('TICKET_UNVIEWED');
						break;
				}
				$temp_ticket['message'] = nl2br($temp_ticket['message']);
				include View::Get('admin/ticket.html', $path);
			}
			$content_main = ob_get_clean();
			$page = lng('TICKETS') . lng('OF_USER') . ' ' . $account->name();
		} else {
			$query = $db->execute("SELECT * FROM `{$bd_names['users']}`, `tickets` WHERE `tickets`.`type`<2 AND `tickets`.`author`=`{$bd_names['users']}`.`{$bd_users['id']}` ORDER BY `time` ASC");
			ob_start();
			$count = $db->num_rows($query);
			while ($temp_ticket = $db->fetch_array($query)) {
				switch($temp_ticket['type']) {
					case 1:
						$after = lng('TICKET_VIEWED');
						break;
					case 0:
						$after = lng('TICKET_UNVIEWED');
						break;
					default:
						continue;
						break;
				}
				include View::Get('admin/ticket_preview.html', $path);
			}
			$content_main = ob_get_clean();
			ob_start();
			include View::Get('admin/index.html', $path);
			$content_main = ob_get_clean();
			$page = lng('TICKETS') . " ($count)";
		}
		break;
	default:
		show_error('404', lng('PAGE_NOT_FOUND'));
		break;
}