<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nomeHino = (isset($_POST["nomeHino"]) && $_POST["nomeHino"] != null) ? $_POST["nomeHino"] : "";
    $cantor = (isset($_POST["cantor"]) && $_POST["cantor"] != null) ? $_POST["cantor"] : "";
    $pasta = (isset($_POST["pasta"]) && $_POST["pasta"] != null) ? $_POST["pasta"] : "";
	$qtdeCopias = (isset($_POST["qtdeCopias"]) && $_POST["qtdeCopias"] != null) ? $_POST["qtdeCopias"] : "";
} else if (!isset($id)) {
    // Se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nomeHino = NULL;
    $cantor = NULL;
    $pasta = NULL;
	$qtdeCopias = NULL;
}
try {

	$conexao = new PDO("mysql:host=herokuapp.com; dbname=bdhinos", "root", "@dmmysqlifp4tuc");
	$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conexao->exec("set names utf8");

} catch (PDOException $erro) {
	echo "Erro na conexão com o banco:" . $erro->getMessage();
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nomeHino != "") {
    try {
        $stmt = $conexao->prepare("INSERT INTO hinos (nomeHino, cantor, pasta, qtdeCopias) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $nomeHino);
        $stmt->bindParam(2, $cantor);
        $stmt->bindParam(3, $pasta);
		$stmt->bindParam(4, $qtdeCopias);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nomeHino = null;
                $cantor = null;
                $pasta = null;
				$qtdeCopias = null;
            } else {
                echo "Erro ao cadastrar!";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Lista de hinos do coral de adolescentes e jovens da congregação Monte Horebe</title>
    </head>
    <body>
        <form action="?act=save" method="POST" name="form1" >
          <h1>HINOS</h1>
          <hr>
          <input type="hidden" name="id" 
		  <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?>
		  />
          Nome do hino:
          <input type="text" name="nomeHino"
		  <?php
            // Preenche o nomeHino no campo nomeHino com um valor "value"
            if (isset($nomeHino) && $nomeHino != null || $nomeHino != "") {
                echo "value=\"{$nomeHino}\"";
            }
            ?>
		  />
          Cantor:
          <input type="text" name="cantor" 
		  <?php
            // Preenche o cantor no campo cantor com um valor "value"
            if (isset($cantor) && $cantor != null || $cantor != "") {
                echo "value=\"{$cantor}\"";
            }
            ?>
		  />
          Pasta:
         <input type="text" name="pasta" 
		 <?php
            // Preenche o pasta no campo pasta com um valor "value"
            if (isset($pasta) && $pasta != null || $pasta != "") {
                echo "value=\"{$pasta}\"";
            }
            ?>
		 />
			N° de cópias: 
		 <input type="number" name="qtdeCopias" 
		 <?php
            // Preenche o qtdeCopias no campo qtdeCopias com um valor "value"
            if (isset($qtdeCopias) && $qtdeCopias != null || $qtdeCopias != "") {
                echo "value=\"{$qtdeCopias}\"";
            }
            ?>
		 />
		 
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>
       </form>
	   <br>
	   
	   <table border="2" width="100%">
    <tr>
        <th>Nome do hino</th>
        <th>Cantor</th>
        <th>Pasta</th>
		<th>N° de cópias</th>
        <th>Ações</th>
    </tr>
	<?php
	 // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
try {
 
    $stmt = $conexao->prepare("SELECT * FROM hinos");
 
        if ($stmt->execute()) {
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>".$rs->nomeHino."</td><td>".$rs->cantor."</td><td>".$rs->pasta."</td><td>".$rs->qtdeCopias
                           ."</td><td><center><a href=\"?act=upd&id=" . $rs->id . "\">[Alterar]</a>"
                           ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                           ."<a href=\"?act=del&id=" . $rs->id . "\">[Excluir]</a></center></td>";
                echo "</tr>";
            }
        } else {
            echo "Erro: Não foi possível recuperar os dados do banco de dados";
        }
} catch (PDOException $erro) {
    echo "Erro: ".$erro->getMessage();
}
?>
</table>

    </body>
</html>