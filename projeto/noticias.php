<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Buscar todas as notícias do banco de dados
$sql = "SELECT noticias.id, noticias.nome, noticias.conteudo, noticias.imagem, noticias.data_publicacao, usuarios.email AS autor_email 
        FROM noticias
        JOIN usuarios ON noticias.usuario_id = usuarios.id
        ORDER BY noticias.data_publicacao DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias - Site de Notícias</title>
    <style>
        /* Reset de margens e paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fontes e cores gerais */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            font-family: 'Helvetica', sans-serif;
            color: #2c3e50;
        }

        /* Container principal */
        .container {
            width: 80%;
            margin: 20px auto;
        }

        /* Título da página */
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Caixa de notícia */
        .news-box {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .news-box:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        /* Imagem da notícia */
        .news-box img {
            max-width: 50%; /* Diminuir o tamanho da imagem */
            height: auto;
            border-radius: 5px;
            display: block;
            margin: 0 auto 10px; /* Centralizar a imagem */
        }

        /* Título da notícia */
        .news-box h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        /* Resumo e autor */
        .news-box p {
            font-size: 1em;
            color: #555;
        }

        .author {
            font-size: 0.9em;
            color: #777;
            margin-top: 10px;
        }

        /* Botão de voltar */
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Últimas Notícias</h1>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="news-box">
        <h2><?= htmlspecialchars($row['nome']) ?></h2>
        <p class="author">Publicado por: <?= htmlspecialchars($row['autor_email']) ?> em <?= htmlspecialchars($row['data_publicacao']) ?></p>
        <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem da notícia">
        <p><?= htmlspecialchars(substr($row['conteudo'], 0, 200)) ?>...</p>
    </div>
    <?php endwhile; ?>

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</div>

</body>
</html>
