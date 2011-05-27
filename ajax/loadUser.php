<?php

header("Content-Type: text/html; charset=iso-8859-1"); //isto serve para n haver problemas com acentos.

session_start();
require_once '../config/config.php';
require_once '../class/class.DBWrapper.php';

$user = $_REQUEST['user'];
$logged = $_REQUEST['logged'];

$read = $_REQUEST['read'];

$updateLoggedInOut = "";
$dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);

if ($user != "" && $logged != "")
    $updateLoggedInOut = $dbWrapper->UpdateTabela("users", "logged={$logged}", "UserName ='" . $user . "'");

//gets all the users from the DB
$isAuthenticaded = $dbWrapper->RetornaResultadoSelectTabela("Username, logged", "users", "1", "logged DESC");
echo "<table>";

//prints the user to the output, distinguishing between those online and those offline
foreach ($isAuthenticaded as $auth) {

    if ($auth['logged']) {
        echo "<tr><td><img id='on' src='./images/images.jpg' /></td><td>{$auth['Username']}</td></tr>";
?>

<?php } else {
        echo "<tr><td> <img id='off' src='./images/images_.jpg' /></td><td>{$auth['Username']}</td></tr>"; ?>

<?php

    }
}

echo "</table>";






