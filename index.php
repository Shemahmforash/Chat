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

//Processment of the Login form
if(!empty($_POST) and $_POST['submit'] == 'Login') {
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

//Processment of the Register form
if(!empty($_POST) and $_POST['submit'] == 'Registar') {
    //it connects to the DB to check if the user and password correspond
    $dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);


    $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_STRING);

    $password =   mysql_real_escape_string($_POST['password']);

    //validacao do user, aceita unicamente strings
    if(!filter_input(INPUT_POST, 'user',FILTER_SANITIZE_STRING)) {
        $errors[] = "Username não é válido";
    }
    /*
    */
    if($user !="" && $password!="") {
        if(empty($errors)) {

            $password = sha1($password);


            $alreadyExistsCheck = $dbWrapper->RetornaResultadoSelectTabela
                    ("*", "users", "UserName = '".$user."'");


            //Verifica se o utilizador já existe se existir não insere e devolve mensagem de erro
            if($alreadyExistsCheck[0]['UserName'] !="") {
                $errors[] = "Este Utilizador já existe, tente outro";

            }else {

                $insert_user = $dbWrapper->InsertIntoTable("users", "Username, Password, CreationDate,Activo", "'{$user}','{$password}',Now(),1");
            }

            if($insert_user != false) {
                $errors[] = "Utilizador inserido correctamente. Clique <a href=\"index.php\">aqui</a> para se autenticar";
            }

        }
    }else {
        $errors[] = "Todos os campos são obrigatórios";
    }
}
?>

<html>
    <head>
        <title>MOSS Chat Room</title>
        <script type="text/javascript" src="./ajax/ajaxChat.js"></script>
        <script type="text/javascript" src="jquery.min.js"></script>
        <script type="application/javascript">
            $(document).ready(function()
            {

                $(".tab").click(function()
                {
                    var X=$(this).attr('id');

                    if(X=='signup')
                    {
                        $("#login").removeClass('select');
                        $("#signup").addClass('select');
                        $("#loginbox").slideUp();
                        $("#signupbox").slideDown();
                    }
                    else
                    {
                        $("#signup").removeClass('select');
                        $("#login").addClass('select');
                        $("#signupbox").slideUp();
                        $("#loginbox").slideDown();
                    }

                });

            });
        </script>
        <style>
            body
            {
                font-family:Arial, Helvetica, sans-serif;
                font-size:12px; background-color:#333;
            }
            #container
            {
                width:350px
            }
            #tabbox
            {
                height:40px
            }
            #panel
            {
                background-color:#FFF;
                height:300px;

            }
            .tab
            {
                background: #dedede;
                display: block;
                height: 40px;
                line-height: 40px;
                text-align: center;
                width: 80px;
                float: right;
                font-weight: bold;
                -webkit-border-top-left-radius: 4px;
                -webkit-border-top-right-radius: 4px;
                -moz-border-radius: 4px 4px 0px 0px;
            }
            a
            {
                color: #000;
                margin: 0;
                padding: 0;
                text-decoration: none;
            }
            .signup
            {
                margin-left:8px;

            }
            .select
            {
                background-color:#FFF;

            }
            #loginbox
            {
                height:300px;
                padding:10px;
            }
            #signupbox
            {
                height:300px;
                padding:10px;
                display:none;
            }

            #error {
                color: red;
                text-transform: uppercase;
            }


        </style>
    </head>
    <body>
        <h1 align="center">MOSS Chat Room</h1>        

        <?
        /*
        ?>

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
        <?
         * 
        */
        ?>

        <div style="margin:40px">
            <div id="container">
                <div id="tabbox">
                    <a href="#" id="signup" class="tab signup">Registar</a>
                    <a href="#" id="login" class="tab select">Login</a>
                </div>
                <div id="panel">
                    <div id="loginbox"><h1>Autentique-se:</h1>

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
                        </form>
                        <br>
                    </div>
                    <div id="signupbox"><h1>Registe-se:</h1>

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
                                        <input type="submit" value="Registar" name="submit"/>
                                        <input type="button" value="voltar" onclick="window.location='index.php'" />
                                    </td>
                                </tr>

                            </table>

                        </form>
                    </div>
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
                </div>

            </div>
        </div>
    </body>
</html>
