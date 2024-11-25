<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de segurança
include('seguranca.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
            color: #007bff;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
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
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Alterar Senha</h2>
    
    <!-- Formulário de Alteração de Senha -->
    <form action="processa_altera_senha.php" method="POST">
        <input type="password" name="senha_atual" class="input-field" placeholder="Digite sua senha atual" required>
        <input type="password" name="nova_senha" class="input-field" placeholder="Digite sua nova senha" required>
        <input type="password" name="confirma_senha" class="input-field" placeholder="Confirme sua nova senha" required>
        
        <button type="submit" class="btn">Alterar Senha</button>
    </form>

    <!-- Mensagem de sucesso ou erro -->
    <?php
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'updated') {
            echo '<p class="success-message">Senha alterada com sucesso!</p>';
        }
    } elseif (isset($_GET['error'])) {
        if ($_GET['error'] == 'empty_fields') {
            echo '<p class="error-message">Por favor, preencha todos os campos.</p>';
        } elseif ($_GET['error'] == 'incorrect_password') {
            echo '<p class="error-message">Senha atual incorreta.</p>';
        } elseif ($_GET['error'] == 'password_mismatch') {
            echo '<p class="error-message">As novas senhas não coincidem.</p>';
        } elseif ($_GET['error'] == 'server_error') {
            echo '<p class="error-message">Erro no servidor. Tente novamente mais tarde.</p>';
        }
    }
    ?>

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</div>

</body>
</html>
