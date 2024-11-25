<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verificar se os campos estão preenchidos
    if (empty($email) || empty($senha)) {
        header('Location: login_email.php?error=empty_fields');
        exit();
    }

    // Verificar se o e-mail e senha existem no banco de dados
    $sql = "SELECT id, senha FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'])) {
            // Credenciais corretas, salvar ID do usuário na sessão
            $_SESSION['usuario_id'] = $row['id'];

            // Redirecionar para a página principal ou painel
            header('Location: url.php');
            exit();
        } else {
            header('Location: login_email.php?error=invalid_credentials');
            exit();
        }
    } else {
        header('Location: login_email.php?error=email_not_found');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login no E-mail - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 300px;
            margin: 100px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #2c3e50; /* Cor do título atualizada */
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px; /* Aumentei a margem inferior para melhor espaçamento */
            border-radius: 5px;
            border: 1px solid #ccc; /* Cor das bordas atualizada */
            font-size: 16px; /* Ajuste no tamanho da fonte */
        }
        .btn {
            width: 100%;
            background-color: #2c3e50; /* Cor do botão atualizada */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9; /* Cor de hover atualizada */
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
    <h2>Login no E-mail</h2>
    
    <!-- Formulário de Login no E-mail -->
    <form action="login_email.php" method="POST">
        <input type="email" name="email" class="input-field" placeholder="Digite seu e-mail" required>
        <input type="password" name="senha" class="input-field" placeholder="Digite sua senha" required>
        <button type="submit" class="btn">Acessar</button>
    </form>

    <!-- Mensagem de erro -->
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'empty_fields') {
            echo '<p class="error-message">Por favor, preencha todos os campos.</p>';
        } elseif ($_GET['error'] == 'invalid_credentials') {
            echo '<p class="error-message">E-mail ou senha inválidos.</p>';
        } elseif ($_GET['error'] == 'email_not_found') {
            echo '<p class="error-message">E-mail não encontrado.</p>';
        }
    }
    ?>

    <a href="index.php" class="btn-back">Voltar para o Login</a>
</div>

</body>
</html>
