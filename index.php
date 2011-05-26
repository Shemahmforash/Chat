<?
session_start();

require_once './config/config.php';
require_once './class/class.DBWrapper.php';

//ini_set("display_errors", 1);
//error_reporting(E_ALL & ~E_NOTICE);

$errors = array();

//var_dump($_POST);

//se voltar a esta página dpois de estar autenticado, mudo de novo para a página do chat
if(isset($_SESSION['autenticacao'])) {
    //reencaminho para a página do chat
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: chat.php" );
}

if(!empty($_POST)) {
    //it connects to the DB to check if the user and password correspond
    $dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);
    $isAuthenticaded = $dbWrapper->RetornaResultadoSelectTabela
            ("*", "users", "UserName = '".mysql_real_escape_string($_POST['user'])."'
            AND Password = '".mysql_real_escape_string(sha1($_POST['password']))."'");

    //if they correspond, we set the session and move to the chat page
    if(count($isAuthenticaded) > 0) {
        $_SESSION['autenticacao'] = $_POST['user'];

        //reencaminho para a página do chat
        Header( "HTTP/1.1 301 Moved Permanently" );
        Header( "Location: chat.php" );
        //echo "OK";
    } else {
        unset($_SESSION['autenticacao']);
        $errors[] = "O username e a password não batem certo.";
        //echo "NOT OK";
    }
}
?>

<html>
    <head>
        <title>MOSS Chat Room</title>
        <script type="text/javascript" src="./ajax/ajaxChat.js"></script>

    </head>
    <body>
        <h1 align="center">MOSS Chat Room</h1>        

        <h2 align="center">Autentique-se:</h2>

        <form name="meuform" action="" method="POST">
            <table align="center">
                <tr align="center">
                    <td align="center">
                        <input name="user" id="user" type="text" size="20" maxlength="15" >
                    </td>
                </tr>

                <tr align="center">
                    <td align="center">
                        <input name="password" id="password" type="password" size="20" maxlength="15"/>
                    </td>
                </tr>
                <tr align="center">
                    <td align="center">
                        <input type="submit" value="Login" name="submit"/>
                    </td>
                </tr>

            </table>
            <br/>
            <br/>
            <table align="center">
                <tr align="center">
                    <td align="center">
                        <input type="button" value="Registar Novo Utilizador" onclick="window.location='registar.php'" />
                    </td>
                </tr>
            </table>
            

        </form>
        <br>
        <div id="error">
            <?
            if(count($errors) > 0) {
                foreach ($errors as $error) {
                    echo $error."<br/>";
                }
            }
            ?>
        </div>
    </body>
</html>
