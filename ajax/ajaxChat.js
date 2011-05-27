
//ajax variables
var ajaxReadRequest = false;
var ajaxWriteRequest = false;
var ajaxGetRequest = false;

//the time between each reading from the file
var waitTime = 1000;

//the time between each refresh of the logged users list
var waitReadUsers = 1000;
//var actual = setTimeout("ajaxLer('chat.txt')", waitTime);

//from time to time it reads from the file
var periodicCheck;// = setInterval("ajaxReadFromFile('../chat/chat.txt')", waitTime);
var checkUser;
/**
 * It writes the messagen when pressing the enter key
 */
function keyup(arg1, fileName) {
    if (arg1 == 13) {
        //alert("nome do ficheiro = " + fileName);
        ajaxWriteToFile(fileName);
    }
}

/**
 * Creates the object that allows ajax to work
 */
function criaObjXmlHttp(){

    var ajaxRequest;  // The variable that makes Ajax possible!
    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    return ajaxRequest;
}

//dpois de ler actualizo o ecr�
/*function afterWriting() {
    if(ajaxWriteRequest.readyState == 4) {
        //var texto = ajaxWriteRequest.responseText;
        //alert("texto = " + texto);
        //document.getElementById("conversa").innerHTML = texto;
        ajaxReadFromFile();
    }
}*/

/**
 * It is called when the ajaxCall to read is ready
 **/
function updateScreenFromFile() {
    if(ajaxReadRequest.readyState == 4) {
        var texto = ajaxReadRequest.responseText;
        //alert("texto = " + texto);
        document.getElementById("conversa").innerHTML = texto;
    }
}

/*
*Refreshes the div with the users authenticated
*/
function updateUser()
{ 
	console.log(ajaxGetRequest.readyState);
	 if(ajaxGetRequest.readyState == 4) {
		 
		
	        var texto = ajaxGetRequest.responseText;
	        //alert("texto = " + texto);
	        document.getElementById("aa").innerHTML = texto;
	    }
}

/**
 *By usig a ajax variable, it reads the contents from the file and puts them on the screen
 */
function ajaxReadFromFile(fileName) {
    //alert("Read From file");
    ajaxReadRequest = criaObjXmlHttp();
    ajaxReadRequest.onreadystatechange = updateScreenFromFile;
    var queryString = "?write=0" + "&fileName=" + fileName;
    //alert("query = " + queryString);
    ajaxReadRequest.open("GET", "./ajax/ajaxChat.php" + queryString, true);

    ajaxReadRequest.send(null);
}

/*
* Runs periodically to check which users are authenticated
*/
function ajaxReadUsers(user, logged,read)
{
	console.log("entrou");
	ajaxGetRequest = criaObjXmlHttp();
	ajaxGetRequest.onreadystatechange = function() 
	{
		updateUser();
	};
    var queryString = "?user="+ user+ "&logged=" + logged + "&read="+ read;
    ajaxGetRequest.open("GET", "./ajax/loadUser.php" + queryString, true);
    ajaxGetRequest.send(null);
}

/**
 * By using the ajax variable, it writes to a file
 */
function ajaxWriteToFile(fileName){

    nick = document.meuform.utilizador.value;
    msg = document.meuform.mensagem.value;

    ajaxWriteRequest = criaObjXmlHttp();
    //ajaxWriteRequest.onreadystatechange = afterWriting;

    //if no user or message, it does not write to the file
    if(nick == "" || msg == "") {
        document.getElementById("erro").innerHTML = "Por favor preencha todos os campos";
        return;
    }
    else {
        //clears the input used to send the message and the error div
        document.meuform.mensagem.value = "";        
        document.getElementById("erro").innerHTML = "";

        var queryString = "?message=" + msg + "&nick=" + nick + "&fileName=" + fileName + "&write=1";
        //alert("query = " + queryString);
        ajaxWriteRequest.open("GET", "./ajax/ajaxChat.php" + queryString, true);
        ajaxWriteRequest.send(null);
    }    
}

/**
 * Writes a specific message to the file, for instance, login, logout
 */
function ajaxWriteMessageToFile(message, fileName) {
    //alert("write: " + message);
    ajaxWriteRequest = criaObjXmlHttp();
    var queryString = "?specificMessage=" + message + "&fileName=" + fileName;
    ajaxWriteRequest.open("GET", "./ajax/ajaxChat.php" + queryString, true);
    ajaxWriteRequest.send(null);
}
/*
 * Stops the periodic function that reads from the file
 */
function stopPeriodicCheck() {
    clearInterval(periodicCheck);
}

/*
 * Starts the periodic function that reads from the file
 */
function startPeriodicCheck() {
    periodicCheck = setInterval("ajaxReadFromFile('../chat/chat.txt')", waitTime);
    checkUser = setInterval("ajaxReadUsers('', '','')", waitReadUsers);
}

function ajaxLogout() {
    //alert("Read From file");
    ajaxReadRequest = criaObjXmlHttp();
    ajaxReadRequest.open("GET", "./ajax/ajaxLogout.php", true);
    ajaxReadRequest.send(null);

}





