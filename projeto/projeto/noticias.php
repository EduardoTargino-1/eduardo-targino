<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Buscar todas as notícias do banco de dados
$sql = "SELECT noticias.id, noticias.titulo, noticias.conteudo, noticias.imagem, noticias.data_publicacao, usuarios.email AS autor_email 
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .news-box {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .news-box img {
            max-width: 100%;
            border-radius: 5px;
        }
        .news-box h2 {
            color: #007bff;
        }
        .news-box p {
            color: #333;
        }
        .news-box .author {
            color: #888;
            font-size: 0.9em;
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
    <h1>Últimas Notícias</h1>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="news-box">
        <h2><?= htmlspecialchars($row['titulo']) ?></h2>
        <p class="author">Publicado por: <?= htmlspecialchars($row['autor_email']) ?> em <?= htmlspecialchars($row['data_publicacao']) ?></p>
        <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem da notícia">
        <p><?= htmlspecialchars(substr($row['conteudo'], 0, 200)) ?>...</p>
    </div>
    <?php endwhile; ?>

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</div>

</body>
</html>
