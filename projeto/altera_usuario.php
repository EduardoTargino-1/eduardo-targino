<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de segurança e conexão com o banco de dados
include('seguranca.php');
include('conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['id'];

// Buscar os dados atuais do usuário
$sql = "SELECT email FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("Erro na preparação da declaração: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $email_atual = $row['email'];
} else {
    die("Usuário não encontrado.");
}

// Fechar a declaração
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #2c3e50; /* Cor do título da página */
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc; /* Cor das bordas dos campos */
            font-size: 16px;
        }
        .btn {
            width: 100%;
            background-color: #2c3e50; /* Cor de fundo dos botões */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9; /* Cor de hover para o botão */
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
            text-align: center;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #2c3e50; /* Cor do botão de voltar */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #2980b9; /* Cor de hover para o botão de voltar */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Alterar Usuário</h2>
    
    <!-- Formulário de Alteração de Usuário -->
    <form action="processa_altera_usuario.php" method="POST">
        <input type="email" name="email" class="input-field" placeholder="Digite seu novo e-mail" value="<?= htmlspecialchars($email_atual) ?>" required>
        <input type="password" name="senha_atual" class="input-field" placeholder="Digite sua senha atual" required>
        <input type="password" name="nova_senha" class="input-field" placeholder="Digite sua nova senha (opcional)">
        
        <button type="submit" class="btn">Alterar</button>
    </form>

    <!-- Mensagem de sucesso ou erro -->
    <?php
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'updated') {
            echo '<p class="success-message">Informações atualizadas com sucesso!</p>';
        }
    } elseif (isset($_GET['error'])) {
        if ($_GET['error'] == 'invalid_password') {
            echo '<p class="error-message">Senha atual incorreta.</p>';
        } elseif ($_GET['error'] == 'server_error') {
            echo '<p class="error-message">Erro no servidor. Tente novamente mais tarde.</p>';
        }
    }
    ?>

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</div>

</body>
</html>
