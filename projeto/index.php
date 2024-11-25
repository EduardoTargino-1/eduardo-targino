<?php

session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Notícias</title>
    <!-- Carregar a fonte e o ícone via CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset de margens e paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fontes e cores gerais */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        /* Cabeçalho */
        .header {
            background-color: #24292f;
            color: white;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header .logo {
            font-size: 2em;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }

        /* Menu de navegação */
        .nav {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
        }

        .nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1em;
            text-transform: uppercase;
            transition: background-color 0.3s;
        }

        .nav a:hover {
            background-color: #444;
        }

        /* Container principal (notícias + login) */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        /* Seção de login */
        .login-container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-container h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        .input-field:focus {
            border-color: #2c3e50;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .btn:hover {
            background-color: #3498db;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            color: #f44336;
            font-size: 0.9em;
            text-align: center;
            margin-top: 10px;
        }

        /* Links adicionais */
        .register-link, .forgot-password, .email-login {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a, .forgot-password a, .email-login a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 0.95em;
            display: inline-block;
            padding: 8px;
            transition: color 0.3s, background-color 0.3s, text-decoration 0.3s;
        }

        .register-link a:hover, .forgot-password a:hover, .email-login a:hover {
            color: white;
            background-color: #2c3e50;
            border-radius: 5px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .nav {
                flex-direction: column;
                
               
               
            }

            .nav a {
                padding: 10px;
                font-size: 1em;
               
            }

            .login-container {
                margin-top: 20px;
                
            }
        }
    </style>
</head>
<body>

<!-- Cabeçalho -->
<div class="header">
    <a href="/" class="logo">Notícias 24h</a>
</div>

<!-- Menu de navegação -->
<div class="nav">
    <a href="index.php" aria-label="Página inicial">Início</a>
    <a href="noticias_politica.php" aria-label="Notícias de Política">Política</a>
    <a href="noticias_esportes.php" aria-label="Notícias de Esportes">Esportes</a>
    <a href="noticias_tecnologia.php" aria-label="Notícias de Tecnologia">Tecnologia</a>
    <a href="noticias_entreterimento.php" aria-label="Notícias de Entreterimento">Entreterimento</a>
    <a href="noticias_saude.php" aria-label="Notícias de Saúde">Saúde</a>
</div>

<!-- Container principal -->
<div class="container">
    <!-- Seção de Login -->
    <div class="login-container">
        <h2>Login</h2>
        <form action="verifica.php" method="POST">
            <input type="email" name="email" class="input-field" placeholder="Digite seu e-mail" required aria-label="E-mail">
            <input type="password" name="senha" class="input-field" placeholder="Digite sua senha" required aria-label="Senha">
            
            <!-- Caixa de aceitação dos termos -->
            <div class="terms-checkbox">
                <label>
                    <input type="checkbox" name="terms" required>
                    Eu aceito os <a href="termos.php" target="_blank">termos de uso</a>.
                </label>
            </div>

            <button type="submit" class="btn" aria-label="Entrar">Entrar</button>
        </form>

        <!-- Mensagens de erro -->
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

        <!-- Links adicionais -->
        <div class="register-link">
            <p>Ainda não tem uma conta? <a href="cadastro.php" aria-label="Cadastrar-se">Cadastre-se</a></p>
        </div>

        <div class="forgot-password">
            <p><a href="esqueci_senha.php" aria-label="Esqueci minha senha">Esqueci minha senha</a></p>
        </div>

        <div class="email-login">
            <p><a href="login_email.php" aria-label="Acessar e-mail">Acessar e-mail</a></p>
        </div>
    </div>
</div>

</body>
</html>
