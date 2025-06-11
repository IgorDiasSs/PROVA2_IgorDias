<?php

session_start();
require_once '../conexao.php';


//Verifica se o usuario tem permissao
//Supondo que o perfil seja o Administrador
if($_SESSION['perfil'] != 1 && $_SESSION['perfil']!=2){
    echo "<script>alert('ACESSO NEGADO');window.location.href='principal.php';</script>";
    exit();
}

$funcionarios = []; //Inicializa variável para evitar erros
//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO PELO ID OU NOME

if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);
    //Verfica se a busca é um número(ID) ou um nome
    if(is_numeric($busca)){
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':busca', $busca, PDO::PARAM_INT);
    }
    else{
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);

    }
}else {
    $sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="scripts.js"></script>
</head>
<body>
    <h2>Lista de Usuárioa</h2>
    <!-- Formulário para buscar usuários -->
    <form action="buscar_funcionario.php" method="POST">
        <label for="busca">Digite o ID ou nome (OPCIONAL):</label>
        <input type="text" id="busca" name="busca" required>

        <button type="submit">Pesquisar</button>
    </form>
    <?php if(!empty($funcionarios)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        <?php foreach($funcionarios as $funcionario): ?>
            <tr>
                <td><?= htmlspecialchars($funcionario['id_funcionario']); ?></td>
                <td><?= htmlspecialchars($funcionario['nome_funcionario']); ?></td>
                <td><?= htmlspecialchars($funcionario['email']); ?></td>
                <td>
                    <a style="color:black;" href="alterar_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario']); ?> ">Alterar</a>
                    <a style="color:red;" href="excluir_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario']); ?> " onclick="return confirm('Tem certeja que deseja excluiir esse usuário?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum Usuário Encontrado</p>
    <?php endif; ?>
    <a href="../principal.php">Voltar</a>
</body>
</html>