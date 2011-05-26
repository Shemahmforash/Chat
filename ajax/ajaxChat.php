<?php

$message = $_REQUEST['message'];
$message = htmlspecialchars(stripslashes($message));
$nick = $_REQUEST['nick'];
$nick = htmlspecialchars(stripslashes($nick));
$fileName = $_REQUEST['fileName'];
$fileName = htmlspecialchars(stripslashes($fileName));

$write = $_REQUEST['write'];

//qdo faço logout ou login, meto essa info no texto
$specificMessage = $_REQUEST['specificMessage'];

//NUMERO MAXIMO DE LINHAS A MOSTRAR NO ECRÃ (ISTO DEVIA MUDAR PARA O CONFIG)
$MAXLINES = 10;

//escrevo mensagem de logout/login
if($specificMessage != "") {

    $now = date("Y-m-d H:i:s");

    $toWrite = "($now)$specificMessage\r\n";

    echo $toWrite;

    if (is_writable($fileName)) {
        if (!$handle = fopen($fileName, 'a')) {
            echo "Cannot open file ($fileName)";
            exit;
        }
        // Write $somecontent to our opened file.
        if (fwrite($handle, $toWrite) === FALSE) {
            echo "Cannot write to file ($fileName)";
            exit;
        }
        fclose($handle);
    } else {
        echo "The file $fileName is not writable";
    }
}

/*if($write)
    echo "Write = ".$_REQUEST['write']."<br/>";*/

//echo "message = $message ; nick = $nick ; fileName = $fileName<br/>";

if($write == 1) {
    $now = date("Y-m-d H:i:s");

    $toWrite = "($now)$nick: $message\r\n";

    echo $toWrite;

    define("MAXLINES", 18);

    if (is_writable($fileName)) {
        if (!$handle = fopen($fileName, 'a')) {
            echo "Cannot open file ($fileName)";
            exit;
        }
        // Write $somecontent to our opened file.
        if (fwrite($handle, $toWrite) === FALSE) {
            echo "Cannot write to file ($fileName)";
            exit;
        }
        fclose($handle);
    } else {
        echo "The file $fileName is not writable";
    }
} else {
    //reads the file line by line
    if (!$handle = fopen($fileName, 'r')) {
        echo "Cannot read file ($fileName)";
        exit;
    }

    if ($handle) {
        $contents = array();
        //$lineCounter = 0;
        //reads line by line

        //TODO: ESTOU A LER O FILE TODO, MAS DEVIA LER SÓ AS ULTIMAS XX ENTRADAS PRA SER MAIS RÁPIDO
        //TALVEZ DEVESSE ESCREVER O FILE AO CONTRÁRIO, I.E., AS ENTRADAS MAIS RECENTES EM CIMA, PARA SER FÁCIL LER SÓ AS Q INTERESSAM
        while (($buffer = fgets($handle, 4096)) !== false/* && $lineCounter < $MAXLINES*/) {
            //echo $buffer;
            $contents[] = $buffer;
            //$lineCounter++;
        }
        fclose($handle);

        //vou buscar apenas as ultimas entradas no ficheiro
        if(count($contents) > $MAXLINES) {
            $sliced = array_slice($contents, count($contents) - $MAXLINES, $MAXLINES);
        }
        else {
            $sliced = $contents;
        }

        //converte o array numa string, separando cada entrada do array por uma linha diferente
        $contentsStr = implode("<br/>", $sliced);
        echo $contentsStr;
    }

}



?>
