<?php
class DBWrapper
{

        var $conection = "";

	/**
 	 * CONSTRUCTOR DA CLASS
 	 * @param mysql_host {string} <p>
	 * 		Endereço do host </p>
	 * @param mysql_user {string} <p>
	 * 		Nome de utilizador MySQL
	 * @param mysql_pw {string} <p>
	 *      Password do Nome de Utilizador MySQL
	 * @param mysql_db
	 * 		Base de Dados onde se irão efectuar os queries
	 * @return {null} <p>
	 */
	public function DBWrapper($mysql_host, $mysql_user, $mysql_pw, $mysql_db)//constructor da class, recebe os dados da ligaï¿½ï¿½oo e o nome da DB
	{
		$this->connection = mysql_connect($mysql_host, $mysql_user, $mysql_pw) or die ("couldn't connect to server");
		$db = mysql_select_db($mysql_db, $this->connection) or die ("Couldn't select database");
	}
	
	 /**
	 * Retorna os valores da tabela numa matriz da forma result[$i][coluna]
	 * @param seleccao {string} <p>
	 * Selecção da tabela
	 * </p>
	 * @param tabela {string} <p>
	 * Nome da tabela
	 * </p>
	 * @param condicao {string} <p>
	 * Condiçãoo a seguir ao WHERE
	 * </p>
	 * @param ordenarpor {string} <p>
	 * Campo de ordenaïção
	 * </p>
	 * @param direccao {string} <p>
	 * Direcção de ordenação (ASC/DESC)
	 * </p>
	 * @param limite {string} <p>
	 * Limite de rows a mostrar
	 * </p>
	 * @return array[int $i][string $coluna]
	 */
	public function RetornaResultadoSelectTabela($seleccao, $tabela, $condicao = "", $ordenarpor="", $direccao="", $limite="")
	{
                //echo "Dentro da class: condicao = $condicao <br />";
		$query = "SELECT ".$seleccao." FROM ".$tabela;
		
		if($condicao != '')
			$query .= " WHERE $condicao";
		
		if($ordenarpor != '')
		{
			$query .= " ORDER BY $ordenarpor";
			if($direccao != '')
				$query .= " $direccao";
		}
		if($limite != '')
			$query .= " LIMIT $limite";
		//echo "query = $query<br />";
		$result = mysql_query($query) or die("Nao deu para executar o query ".$query." pq ".mysql_error());
		
		$j = 0;
		while($row = mysql_fetch_array($result))
		{
			foreach ($row as $colname => $value)
			{
				$resultado[$j][$colname] = $value;
			}
			$j++;
		}
		
		mysql_free_result($result);
		return $resultado;	
	}
	
	/**
 	 * FAZ UPDATE DA TABELA DA BASE DE DADOS
 	 * @param tabela {string} <p>
	 * 		Nome da tabela a actualizar </p>
	 * @param set {string} <p>
	 * 		valor da parte SET do query SQL
	 * @param condicao {string} <p>
	 *      Condição a seguir ao WHERE do query SQL
	 * @return {null} <p>
	 */
	public function UpdateTabela($tabela, $set, $condicao)
	{
		$query = "UPDATE ".$tabela." SET ".$set." WHERE ".$condicao;
		//echo "query = $query<br />";
		$result = mysql_query($query) or die("Nao deu para executar o query ".$query." pq ".mysql_error());
	}
	
	/**
 	 * FUNÇÃO Q INSERE DADOS NA TABELA
 	 * @param tabela {string} <p>
	 * 		Nome da tabela onde os dados serï¿½o inseridos </p>
	 * @param campos {string} <p>
	 * 		nome dos campos separado por virgulas
	 * @param valores {string} <p>
	 *      valores separados por virgulas e entre plicas
	 * @return {null} <p>
	 */
	public function InsertIntoTable($tabela, $campos, $valores)
	{
		$query = "INSERT INTO ".$tabela." (".$campos.") VALUES (".$valores.") ";
		#echo "query = $query<br />";
		$result = mysql_query($query) or die("Nao deu para executar o query ".$query." pq ".mysql_error());
                return $result;
	}
	
	/**
 	 * APAGA ENTRADAS DA TABELA DA BASE DE DADOS
 	 * @param tabela {string} <p>
	 * 		Nome da tabela</p>
	 * @param condicao {string} <p>
	 *      Condiï¿½ï¿½o a seguir ao WHERE do query SQL
	 * @return numero {int} <p>
	 * 		Numero de linhas afectadas
	 */
	public function ApagaEntradasTabela($tabela, $condicao)
	{
		if(isset($tabela))
			$query = "DELETE FROM ".$tabela." ";
		if(isset($condicao))
			$query .= "WHERE ".$condicao;
		//echo "query = $query<br />";
		if($query != '')
			$result = mysql_query($query) or die("Nao deu para executar o query ".$query." pq ".mysql_error());
			
		return $result;
	}
	
}
?>
