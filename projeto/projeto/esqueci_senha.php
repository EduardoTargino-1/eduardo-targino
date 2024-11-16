<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    // Verificar se o campo de e-mail está preenchido
    if (empty($email)) {
        header('Location: esqueci_senha.php?error=empty_email');
        exit();
    }

    // Verificar se o e-mail existe no banco de dados
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Gerar um token de recuperação de senha
        $token = bin2hex(random_bytes(50));
        $usuario_id = $row['id'];

        // Inserir o token e o e-mail no banco de dados
        $sql = "INSERT INTO recuperacao_senha (usuario_id, email, token) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die("Erro na preparação da declaração: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $email, $token);
        mysqli_stmt_execute($stmt);

        // Enviar o e-mail de recuperação de senha
        $reset_link = "http://seusite.com/resetar_senha.php?token=$token";
        $mensagem = "Clique no link a seguir para resetar sua senha: $reset_link";
        mail($email, "Recuperação de Senha", $mensagem);

        header('Location: esqueci_senha.php?success=email_sent');
        exit();
    } else {
        header('Location: esqueci_senha.php?error=email_not_found');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Minha Senha - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
    <h2>Esqueci Minha Senha</h2>
    
    <!-- Formulário de Recuperação de Senha -->
    <form action="esqueci_senha.php" method="POST">
        <input type="email" name="email" class="input-field" placeholder="Digite seu e-mail" required>
        <button type="submit" class="btn">Enviar</button>
    </form>

    <!-- Mensagem de sucesso ou erro -->
    <?php
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'email_sent') {
            echo '<p class="success-message">Um e-mail de recuperação de senha foi enviado.</p>';
        }
    } elseif (isset($_GET['error'])) {
        if ($_GET['error'] == 'empty_email') {
            echo '<p class="error-message">Por favor, preencha o campo de e-mail.</p>';
        } elseif ($_GET['error'] == 'email_not_found') {
            echo '<p class="error-message">E-mail não encontrado.</p>';
        }
    }
    ?>

    <a href="index.php" class="btn-back">Voltar para o Login</a>
</div>

</body>
</html>
