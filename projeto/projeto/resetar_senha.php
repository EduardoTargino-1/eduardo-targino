<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetar Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .input-field {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            width: 100%;
            max-width: 400px;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            display: block;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Resetar Senha</h2>
        <form action="resetar_senha.php" method="POST">
            <input type="email" name="email" placeholder="Insira o email" class="input-field" required>
            <input type="text" name="token" placeholder="Insira o token" class="input-field" required>
            <input type="password" name="new_password" placeholder="Nova senha" class="input-field" required>
            <input type="password" name="confirm_password" placeholder="Confirmar nova senha" class="input-field" required>
            <button type="submit">Alterar Senha</button>
        </form>
        <div class="navigation-buttons">
            <button onclick="window.location.href='login_email.php'">Voltar para Login</button>
            <button onclick="window.location.href='index.php'">Voltar para Home</button>
        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Incluir a conexão com o banco de dados
            include 'conexao.php';

            // Obter o email, token e as senhas do formulário
            $email = $_POST['email'];
            $token = $_POST['token'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Verificar se as senhas coincidem
            if ($new_password !== $confirm_password) {
                echo '<div class="message error">As senhas não coincidem.</div>';
            } else {
                // Verificar se o token e o email são válidos na tabela recuperacao_senha
                $sql = "SELECT * FROM recuperacao_senha WHERE token = ? AND email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $token, $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    echo '<div class="message error">Token ou email inválido.</div>';
                } else {
                    // Atualizar a senha do usuário na tabela usuarios
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE usuarios SET senha = ? WHERE email = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param('ss', $hashed_password, $email);

                    if ($update_stmt->execute()) {
                        // Remover o token da tabela recuperacao_senha
                        $delete_sql = "DELETE FROM recuperacao_senha WHERE token = ? AND email = ?";
                        $delete_stmt = $conn->prepare($delete_sql);
                        $delete_stmt->bind_param('ss', $token, $email);
                        $delete_stmt->execute();

                        echo '<div class="message success">Senha alterada com sucesso.</div>';
                    } else {
                        echo '<div class="message error">Erro ao alterar a senha.</div>';
                    }
                }

                $stmt->close();
                $conn->close();
            }
        }
        ?>
    </div>
</body>
</html>
