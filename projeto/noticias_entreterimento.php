<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Definir a categoria a ser exibida (Entretenimento)
$categoria = 'entreterimento';

// Consulta para buscar as notícias da categoria "Entretenimento"
$sql = "SELECT * FROM noticias WHERE categoria = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);

// Verificar se a preparação da consulta foi bem-sucedida
if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $conn->error);
}

$stmt->bind_param("s", $categoria);
$stmt->execute();

// Obter o resultado da consulta
$result = $stmt->get_result();

// Exibir as notícias
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias - Entretenimento</title>
    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .nav {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
            margin-bottom: 20px;
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

        .noticia {
            background-color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .noticia-titulo {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .noticia-conteudo {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .noticia-imagem {
            max-width: 100%;
            width: 70%;  /* Ajuste para reduzir o tamanho da imagem */
            height: auto;
            margin-top: 15px;
            border-radius: 8px;
        }

        .comentarios {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .comentarios h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .comentario {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comentario p {
            font-size: 14px;
            color: #444;
        }

        .comentario em {
            font-size: 12px;
            color: #888;
        }

        .form-comentario {
            margin-top: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-comentario input, .form-comentario textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-comentario button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-comentario button:hover {
            background-color: #45a049;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .noticia-imagem {
                width: 100%;  /* Imagem ocupa toda a largura em telas menores */
            }
        }
    </style>
</head>
<body>
    <!-- Menu de navegação -->
    <div class="nav">
        <a href="index.php">Início</a>
        <a href="noticias_politica.php">Política</a>
        <a href="noticias_esportes.php">Esportes</a>
        <a href="noticias_tecnologia.php">Tecnologia</a>
        <a href="noticias_entreterimento.php">Entretenimento</a>
        <a href="noticias_saude.php">Saúde</a>
    </div>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $noticia_id = $row['id']; // ID da notícia
                
                // Exibir a notícia
                echo "<div class='noticia'>";
                echo "<h3 class='noticia-nome'>" . htmlspecialchars($row['nome']) . "</h3>";
                echo "<p class='noticia-conteudo'>" . nl2br(htmlspecialchars($row['conteudo'])) . "</p>";
                echo "<img src='" . htmlspecialchars($row['imagem']) . "' alt='Imagem da Notícia' class='noticia-imagem'>";
                echo "</div>";

                // Exibir os comentários da notícia
                echo "<div class='comentarios'>";
                echo "<h4>Comentários</h4>";

                // Consulta para buscar os comentários relacionados a esta notícia
                $comentarios_sql = "SELECT * FROM comentarios WHERE noticia_id = ? ORDER BY data DESC";
                $comentarios_stmt = $conn->prepare($comentarios_sql);
                $comentarios_stmt->bind_param("i", $noticia_id);
                $comentarios_stmt->execute();
                $comentarios_result = $comentarios_stmt->get_result();

                if ($comentarios_result->num_rows > 0) {
                    while ($comentario = $comentarios_result->fetch_assoc()) {
                        echo "<div class='comentario'>";
                        echo "<p><strong>" . htmlspecialchars($comentario['nome']) . ":</strong> " . nl2br(htmlspecialchars($comentario['comentario'])) . "</p>";
                        echo "<p><em>" . $comentario['data'] . "</em></p>";
                        echo "</div><hr>";
                    }
                } else {
                    echo "<p>Seja o primeiro a comentar!</p>";
                }

                // Formulário para adicionar um novo comentário
                echo "<h4>Deixe seu comentário</h4>";
                echo "<form action='adicionar_comentario_entreterimento.php' method='POST' class='form-comentario'>
                        <input type='hidden' name='noticia_id' value='" . $noticia_id . "'>
                        <input type='text' name='nome' placeholder='Seu nome' required><br>
                        <textarea name='comentario' placeholder='Escreva seu comentário...' required></textarea><br>
                        <button type='submit'>Enviar comentário</button>
                      </form>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "<p>Não há notícias de entretenimento disponíveis no momento.</p>";
        }

        // Fechar a conexão
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
