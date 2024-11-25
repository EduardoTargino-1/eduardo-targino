<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de segurança e conexão com o banco de dados
include('seguranca.php');
include('conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['id'];

// Buscar todas as notícias do usuário logado
$sql = "SELECT id, nome, conteudo, imagem, data_publicacao FROM noticias WHERE usuario_id = ? ORDER BY data_publicacao DESC";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("Erro na preparação da declaração: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Notícias - Site de Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h1 {
            color: #2c3e50; /* Cor do título da página */
            font-size: 2em;
            margin-bottom: 20px;
        }
        .news-box {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .news-box:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .news-box img {
            max-width: 100%;
            border-radius: 5px;
        }
        .news-box h2 {
            color: #2c3e50; /* Cor do título da notícia */
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .news-box p {
            color: #555; /* Cor do conteúdo da notícia */
            font-size: 1em;
        }
        .news-box .author {
            color: #777; /* Cor do autor da notícia */
            font-size: 0.9em;
            margin-top: 10px;
        }
        .btn {
            background-color: #2c3e50; /* Cor do botão padrão */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9; /* Cor do botão ao passar o mouse */
        }
        .btn-delete {
            background-color: #ff4d4d; /* Cor do botão de deletar */
        }
        .btn-delete:hover {
            background-color: #cc0000; /* Cor do botão de deletar ao passar o mouse */
        }
        .success-message {
            color: green;
            text-align: center;
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #2c3e50; /* Cor do botão de voltar */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #2980b9; /* Cor do botão de voltar ao passar o mouse */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Minhas Notícias</h1>

    <!-- Mensagem de sucesso ou erro -->
    <?php
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'deleted') {
            echo '<p class="success-message">Notícia deletada com sucesso!</p>';
        }
    } elseif (isset($_GET['error'])) {
        echo '<p class="error-message">Erro ao deletar a notícia. Tente novamente.</p>';
    }
    ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="news-box">
        <h2><?= htmlspecialchars($row['nome']) ?></h2>
        <p class="author">Publicado em <?= htmlspecialchars($row['data_publicacao']) ?></p>
        <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem da notícia">
        <p><?= htmlspecialchars(substr($row['conteudo'], 0, 200)) ?>...</p>
        <a href="deleta_noticia.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja deletar esta notícia?');">Deletar</a>
    </div>
    <?php endwhile; ?>

    <a href="dashboard.php" class="btn-back">Voltar para o Dashboard</a>
</div>

</body>
</html>
