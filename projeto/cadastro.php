<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php'); // Certifique-se de que o arquivo 'conexao.php' esteja configurado corretamente

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $email = $_POST['email'] ?? ''; // Usando null coalescing para evitar "undefined index"
    $senha = $_POST['senha'] ?? '';

    // Verificar se os campos estão preenchidos
    if (empty($email) || empty($senha)) {
        // Redireciona de volta para o cadastro com um erro se algum campo estiver vazio
        header('Location: cadastro.php?error=empty_fields');
        exit();
    }

    // Hash da senha antes de armazenar no banco de dados
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Preparar a consulta SQL para inserir o novo usuário
    $sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        error_log("Erro na preparação da declaração: " . mysqli_error($conn));
        header('Location: cadastro.php?error=server_error');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $email, $senha_hash);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Cadastro realizado com sucesso
        header('Location: cadastro.php?success=registered');
        exit();
    } else {
        error_log("Erro ao cadastrar o usuário: " . mysqli_error($conn));
        header('Location: cadastro.php?error=server_error');
        exit();
    }

    // Fechar a declaração
    mysqli_stmt_close($stmt);
}

// Fechar a conexão
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
        }
        .register-container {
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
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #2c3e50;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Cadastro</h2>
    
    <!-- Formulário de Cadastro -->
    <form action="cadastro.php" method="POST">
        <input type="email" name="email" class="input-field" placeholder="Digite seu e-mail" required>
        <input type="password" name="senha" class="input-field" placeholder="Digite sua senha" required>
        
        <button type="submit" class="btn">Cadastrar</button>
    </form>

    <!-- Caso haja uma mensagem de erro -->
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'empty_fields') {
            echo '<p class="error-message">Por favor, preencha todos os campos.</p>';
        } elseif ($_GET['error'] == 'server_error') {
            echo '<p class="error-message">Erro no servidor. Tente novamente mais tarde.</p>';
        }
    }

    // Exibir mensagem de sucesso se o cadastro for bem-sucedido
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'registered') {
            echo '<p class="success-message">Cadastro realizado com sucesso! Você pode fazer login agora.</p>';
        }
    }
    ?>

    <!-- Link para a página de login -->
    <div class="login-link">
        <p>Já tem uma conta? <a href="index.php">Faça login</a></p>
    </div>
</div>

</body>
</html>
