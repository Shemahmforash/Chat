<?php
require_once './config/config.php';
require_once './class/class.DBWrapper.php';


ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
$errors = array();

if(!empty($_POST)) {
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

    </head>
    <body>
        <h1 align="center">MOSS Chat Room</h1>



        <h2 align="center">Registe-se:</h2>

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
