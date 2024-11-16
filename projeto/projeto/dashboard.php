<?php

// Incluir o arquivo de segurança
include('seguranca.php');

// Verificar se a sessão não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado (se a variável de sessão 'id' estiver definida)
if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: index.php');
    exit();
}

// Dados do usuário
$email = $_SESSION['email']; // Email do usuário salvo na sessão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        .dashboard-box {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left;
            margin-bottom: 20px;
        }
        .dashboard-box h2 {
            margin-top: 0;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0; /* Adicionado margin para espaçar os botões */
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .dashboard-box ul {
            list-style: none;
            padding: 0;
        }
        .dashboard-box ul li {
            margin-bottom: 10px; /* Adicionado margin-bottom para espaçar os itens da lista */
        }
    </style>
</head>
<body>

<header>
    <h1>Painel de Controle - Notícias</h1>
</header>

<div class="container">
    <div class="dashboard-box">
        <h2>Bem-vindo, <?= htmlspecialchars($email) ?>!</h2>
        <p>Você está logado e pode acessar o painel de controle para gerenciar suas notícias.</p>
    </div>

    <div class="dashboard-box">
        <h3>Opções:</h3>
        <ul>
            <li><a href="insere_noticia.php" class="btn">inserir noticias</a></li>
            <li><a href="altera_usuario.php" class="btn">altera informações</a></li>
            <li><a href="noticias.php" class="btn">noticias</a></li>
            <li><a href="minhas_noticias.php" class="btn">minhas noticias</a></li>
            <li><a href="index.php" class="btn">sair</a></li>
        </ul>
    </div>
</div>

</body>
</html>
