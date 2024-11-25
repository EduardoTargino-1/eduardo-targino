<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Notícia - Site de Notícias</title>

    <!-- CSS incorporado -->
    <style>
        /* Reseta o margin e padding padrão do navegador */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Corpo da página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo do corpo igual ao código anterior */
            color: #333; /* Cor de texto principal */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contêiner principal */
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        /* Título da página */
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50; /* Cor do título igual ao cabeçalho */
        }

        /* Formulário */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Campos de entrada de texto */
        .input-field,
        .textarea-field,
        .select-field {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        /* Área de texto */
        .textarea-field {
            resize: vertical;
        }

        /* Estilo do botão de envio */
        .btn,
        .btn-back {
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Botão de Inserir Notícia */
        .btn {
            background-color: #2c3e50; /* Cor do botão */
            color: #fff;
        }

        .btn:hover {
            background-color: #2980b9; /* Cor do botão ao passar o mouse */
        }

        /* Botão de Voltar */
        .btn-back {
            background-color: #f44336;
            color: #fff;
        }

        .btn-back:hover {
            background-color: #e53935;
        }

        /* Mensagens de erro e sucesso */
        .success-message,
        .error-message {
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        /* Estilo para mensagem de sucesso */
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        /* Estilo para mensagem de erro */
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        /* Ajustes de responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                width: 90%;
            }
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
        
        <!-- Campo de seleção para a categoria -->
        <select name="categoria" class="select-field" required>
            <option value="" disabled selected>Escolha a categoria</option>
            <option value="politica">Política</option>
            <option value="esportes">Esportes</option>
            <option value="tecnologia">Tecnologia</option>
            <option value="entreterimento">entreterimento</option>
            <option value="saude">Saúde</option>
        </select>
        
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
