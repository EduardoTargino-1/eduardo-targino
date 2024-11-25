<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetar Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }

        h2 {
            color: #2c3e50; /* Cor do título */
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #2c3e50; /* Cor do botão */
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9; /* Cor de hover */
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        .navigation-buttons button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
        }

        .navigation-buttons button:hover {
            background-color: #0056b3;
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
        <input type="email" name="email" placeholder="Insira o e-mail" class="input-field" required>
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
                echo '<div class="message error">Token ou e-mail inválido.</div>';
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
