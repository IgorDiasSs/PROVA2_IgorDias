<?php

session_start();
require_once '../conexao.php';


//Verifica se o usuario tem permissao
//Supondo que o perfil seja o Administrador
if($_SESSION['perfil'] != 1 && $_SESSION['perfil']!=2){
    echo "<script>alert('ACESSO NEGADO');window.location.href='principal.php';</script>";
    exit();
}

$usuarios = []; //Inicializa variável para evitar erros
//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO PELO ID OU NOME

if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);
    //Verfica se a busca é um número(ID) ou um nome
    if(is_numeric($busca)){
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':busca', $busca, PDO::PARAM_INT);
    }
    else{
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);

    }
}else {
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js"></script>
</head>
<body>
    <h2>Lista de Usuárioa</h2>
    <!-- Formulário para buscar usuários -->
    <form action="buscar_usuario.php" method="POST">
        <label for="busca">Digite o ID ou nome (OPCIONAL):</label>
        <input type="text" id="busca" name="busca" required>

        <button type="submit">Pesquisar</button>
    </form>
    <?php if(!empty($usuarios)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']); ?></td>
                <td><?= htmlspecialchars($usuario['nome']); ?></td>
                <td><?= htmlspecialchars($usuario['email']); ?></td>
                <td><?= htmlspecialchars($usuario['id_perfil']); ?></td>
                <td>
                    <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario']); ?> ">Alterar</a>
                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario']); ?> " onclick="return confirm('Tem certeja que deseja excluiir esse usuário?')">Excluir</a>
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