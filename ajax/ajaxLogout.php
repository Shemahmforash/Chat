<?php

session_start();

require_once '../config/config.php';
require_once '../class/class.DBWrapper.php';

//refreshes the state of the user in the DB
$dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);
$isAuthenticaded = $dbWrapper->UpdateTabela("users", "logged=0", "UserName ='" . $_SESSION['autenticacao'] . "'");

unset($_SESSION['autenticacao']);


?>
