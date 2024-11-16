<?php
// Iniciar a sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .login-container {
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
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .terms {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .terms input {
            margin-right: 10px;
        }
        .email-login {
            text-align: center;
            margin-top: 20px;
        }
        .email-login a {
            color: #007bff;
            text-decoration: none;
        }
        .email-login a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            var termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                alert('Você deve aceitar os termos e condições para continuar.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
    <!-- Formulário de Login -->
    <form action="verifica.php" method="POST" onsubmit="return validateForm()">
        <input type="email" name="email" class="input-field" placeholder="Digite seu e-mail" required>
        <input type="password" name="senha" class="input-field" placeholder="Digite sua senha" required>
        
        <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Aceito os <a href="termos.php">termos e condições</a></label>
        </div>
        
        <button type="submit" class="btn">Entrar</button>
    </form>

    <!-- Caso haja uma mensagem de erro -->
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'invalid_credentials') {
            echo '<p class="error-message">E-mail ou senha inválidos. Tente novamente.</p>';
        } elseif ($_GET['error'] == 'empty_fields') {
            echo '<p class="error-message">Por favor, preencha todos os campos.</p>';
        } elseif ($_GET['error'] == 'server_error') {
            echo '<p class="error-message">Erro do servidor. Tente novamente mais tarde.</p>';
        }
    }
    ?>

    <!-- Link para a página de cadastro -->
    <div class="register-link">
        <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>

    <!-- Link para a página de recuperação de senha -->
    <div class="forgot-password">
        <p><a href="esqueci_senha.php">Esqueci minha senha</a></p>
    </div>

    <!-- Link para a página de login de e-mail -->
    <div class="email-login">
        <p><a href="login_email.php">Acessar e-mail</a></p>
    </div>
</div>

</body>
</html>
