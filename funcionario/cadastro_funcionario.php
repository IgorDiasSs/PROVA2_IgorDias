<?php

session_start();
require_once '../conexao.php';


//Verifica se o usuario tem permissao
//Supondo que o perfil seja o Administrador
if($_SESSION['perfil'] != 1){
    echo "Acesso Negado";
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['fone'];
    $endereco = $_POST['endereco'];

    $sql = "INSERT INTO funcionario (nome_funcionario, endereco, telefone, email ) VALUES (:nome, :endereco, :telefone, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if($stmt->execute()){
        echo "<script>alert('Funcionário Cadastrado com sucesso! '); window.location.href='cadastro_funcionario.php'; </script>";

    } else {
        echo "<script>alert('ERR0 ao cadastrar Funcionário! '); </script>";
    }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js"></script>
</head>
<body>
    <div class="cabecalho">
        Por: Igor da Silva Dias
    </div>
    <h2>Cadastro de Funcionário</h2>
    <form class="form-funcionario" action="cadastro_funcionario.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <label for="email">Telefone:</label>
        <input type="text" id="fone" name="fone" required>
        <div class="btns">
            <button type="reset">Cancelar</button>
            <button type="submit">Salvar</button>
        </div>

    </form>
    <a href="../principal.php">Voltar</a>
</body>
</html>