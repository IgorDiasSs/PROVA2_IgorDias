<?php
session_start();
require '../conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variáveis
$funcionario = null;

// Se o formulário for enviado, busca o usuário pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['busca'])) {
        $busca = trim($_GET['busca']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$funcionario) {
            echo "<script>alert('Funcionário não encontrado!');</script>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_funcionario'])) {
    $id_funcionario = $_POST['id_funcionario'];
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    try {
        // Atualiza os dados do funcionário no banco de dados
        $sql = "UPDATE funcionario 
                SET nome_funcionario = :nome, endereco = :endereco, email = :email, telefone = :telefone 
                WHERE id_funcionario = :id_funcionario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
        $stmt->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href='alterar_funcionario.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar funcionário!');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="../styles.css">
    
    <!-- Certifique-se de que o JavaScript está sendo carregado corretamente -->
    <script src="../scripts.js"></script>
</head>
<body>
    <div class="cabecalho">
        Por: Igor da Silva Dias
    </div>
    <h2>Alterar Funcionário</h2>

    <!-- Formulário para buscar usuário pelo ID ou Nome -->
    <form action="alterar_funcionario.php" method="GET">
        <label for="busca_funcionario">Digite o ID ou Nome do usuário:</label>
        <input type="text" id="busca_funcionario" name="busca" required onkeyup="buscarSugestoes()">
        
        <button type="submit">Buscar</button>
    </form>

    <?php if ($funcionario): ?>
        <!-- Formulário para alterar usuário -->
        <form action="alterar_funcionario.php" method="POST">
            <input type="hidden" name="id_funcionario" value="<?= htmlspecialchars($funcionario['id_funcionario']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco"value="<?= htmlspecialchars($funcionario['endereco']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>" required>

            <button type="submit" nmae="alterar">Alterar</button>
        </form>
    <?php endif; ?>

    <a href="../principal.php">Voltar</a>
</body>
</html>