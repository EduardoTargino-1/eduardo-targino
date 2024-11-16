<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Notícia - Site de Notícias</title>
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
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .input-field, .textarea-field {
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
        .btn-back {
            width: 100%;
            background-color: #6c757d;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Inserir Notícia</h2>
    
    <!-- Formulário de Inserção de Notícias -->
    <form action="processa_noticia.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" class="input-field" placeholder="Digite o título da notícia" required>
        <textarea name="conteudo" class="textarea-field" placeholder="Digite o conteúdo da notícia" rows="10" required></textarea>
        <input type="file" name="imagem" class="input-field" required>
        
        <button type="submit" class="btn">Inserir Notícia</button>
    </form>

    <!-- Botão de Voltar -->
    <form action="dashboard.php" method="get">
        <button type="submit" class="btn-back">Voltar para o Painel de Controle</button>
    </form>

    <!-- Mensagem de sucesso ou erro -->
    <?php
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'inserted') {
            echo '<p class="success-message">Notícia inserida com sucesso!</p>';
        }
    } elseif (isset($_GET['error'])) {
        if ($_GET['error'] == 'empty_fields') {
            echo '<p class="error-message">Por favor, preencha todos os campos.</p>';
        } elseif ($_GET['error'] == 'invalid_image') {
            echo '<p class="error-message">O arquivo não é uma imagem válida.</p>';
        } elseif ($_GET['error'] == 'file_exists') {
            echo '<p class="error-message">O arquivo já existe.</p>';
        } elseif ($_GET['error'] == 'file_too_large') {
            echo '<p class="error-message">O arquivo é muito grande. Tamanho máximo permitido é 5MB.</p>';
        } elseif ($_GET['error'] == 'invalid_format') {
            echo '<p class="error-message">Formato de arquivo não permitido. Apenas JPG, JPEG, PNG e GIF são permitidos.</p>';
        } elseif ($_GET['error'] == 'upload_failed') {
            echo '<p class="error-message">Falha no upload da imagem.</p>';
        } elseif ($_GET['error'] == 'server_error') {
            echo '<p class="error-message">Erro no servidor. Tente novamente mais tarde.</p>';
        }
    }
    ?>
</div>

</body>
</html>
