<?
session_start();
require_once './config/config.php';
require_once './class/class.DBWrapper.php';

//var_dump($_POST);


//se n estiver autenticado, mudo para a p�gina de login
if(!isset($_SESSION['autenticacao'])) {
    //reencaminho para a p�gina do chat
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: index.php" );
}

//echo "autenticacao = ".$_SESSION['autenticacao']."<br/>";

//var_dump($_POST);

//if(count($_POST['logout']) > 0) {
if($_POST['logout']!=""){
	$dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);
	 $isAuthenticaded = $dbWrapper->UpdateTabela("users", "logged=0", "UserName ='".$_SESSION['autenticacao']."'");
    unset($_SESSION['autenticacao']);
    
    //reencaminho para a página de autenticação
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: index.php" );
}

//if the user is logged, then it refreshes its state in the DB
if(isset($_SESSION['autenticacao'])) {
	 $dbWrapper = new DBWrapper(dbHOST, dbUSER, dbPASSWORD, dbNAME);
   	 $isAuthenticaded = $dbWrapper->UpdateTabela("users", "logged=1", "UserName ='".$_SESSION['autenticacao']."'");
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
    <body onload="ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged in.', '<? echo TXTFILENAME; ?>');startPeriodicCheck();ajaxReadUsers('','', 2);">

        <?
        //It does the logout when going away from this page (except when the logout button is pressed)
        ?>
        <script>
            var logout = false;
            window.onbeforeunload = logoutWhenClosingPage;

            function logoutWhenClosingPage() {
                if(!logout) {
                    //alert("Fecho a janela sem ser por logout.");

                    //actualizo o file com info de logout
                    ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged out.', '<? echo TXTFILENAME; ?>');
                    
                    //faço unset da sessão
                    ajaxLogout();
                }

            }

            function setLogout() {
                logout = true;
            }
        </script>

        <h1>MOSS Chat Room</h1>
       
       
      <div style="width:800px;">
        
   <div style="float:left;">     
        <div id="conversa" > </div>
      </div> 
      <div style="float:right;">
	       <div id="aa" style=" width:100px; height:100px;">
			
			</div> 
		</div><div style="clear:both;"></div>
        <form name="meuform">
            <input name="utilizador" id="utilizador" type="text" value="<? echo $_SESSION['autenticacao']; ?>" size="9" maxlength="9" READONLY>&nbsp;
           
         
            <input name="mensagem" id="mensagem" type="text" size="60" maxlength="80"
                   onkeyup="keyup(event.keyCode, '<? echo TXTFILENAME; ?>');" style="float:left; width:200px;">
                   
            
            <input type="button" value="envia" onclick="ajaxWriteToFile('<? echo TXTFILENAME; ?>');"><br/><br/>
            <div id="erro"></div>
        </form>
		     
        <form name="logout" action="" method="POST">
            <input name="logout" type="submit" value="Logout" onclick="setLogout();ajaxWriteMessageToFile('<? echo $_SESSION['autenticacao'];?> has logged out.', '<? echo TXTFILENAME; ?>');"><br/><br/>
        </form>
      </div> 
        <br/>
    </body>
</html>

