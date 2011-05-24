<?
session_start();
require_once './config/config.php';

//se n estiver autenticado, mudo para a página de login
if(!isset($_SESSION['autenticacao'])) {
    //reencaminho para a página do chat
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: index.php" );
}


//var_dump($_POST);

if($_POST['logout']) {

    unset($_SESSION['autenticacao']);
    //reencaminho para a página de autenticação
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: index.php" );
}

?>

<html>
    <head>
        <title>MOSS Chat Room</title>
        <script type="text/javascript" src="./ajax/ajaxChat.js"></script>

    </head>
    <?
    //when loading this page, I set the timer that gets the contents from the file periodically and put a login message
    ?>
    <body onload="ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged in.', '<? echo TXTFILENAME; ?>');startPeriodicCheck();">

        <?
        //faço logout ao fechar a página
        ?>
        <script>
            window.onbeforeunload = logoutWhenClosingPage;

            function logoutWhenClosingPage()
            {
                ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged out.', '<? echo TXTFILENAME; ?>');
                //alert("página a fechar");
            }
        </script>
        <h1>MOSS Chat Room</h1>
        <div id="conversa" > </div>
        <form name="meuform">
            <input name="utilizador" id="utilizador" type="text" value="<? echo $_SESSION['autenticacao']; ?>" size="9" maxlength="9" READONLY>&nbsp;
            <input name="mensagem" id="mensagem" type="text" size="60" maxlength="80"
                   onkeyup="keyup(event.keyCode, '<? echo TXTFILENAME; ?>');">
            <input type="button" value="envia" onclick="ajaxWriteToFile('<? echo TXTFILENAME; ?>');"><br/><br/>
            <div id="erro"></div>
        </form>

        <form name="logout" action="" method="POST">
            <input name="logout" type="submit" value="Logout" onclick="ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged out.', '<? echo TXTFILENAME; ?>');"><br/><br/>
        </form>
        <br>
    </body>
</html>

