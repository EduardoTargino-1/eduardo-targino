<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $noticia_id = $_POST['noticia_id']; // ID da notícia
    $nome = htmlspecialchars($_POST['nome']); // Nome do usuário
    $comentario = htmlspecialchars($_POST['comentario']); // Texto do comentário

    // Verificar se os campos estão preenchidos corretamente
    if (empty($nome) || empty($comentario)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Inserir o comentário no banco de dados
    $sql = "INSERT INTO comentarios (noticia_id, nome, comentario, data, status) 
            VALUES (?, ?, ?, NOW(), 'aprovado')";  // O status é 'aprovado' para que o comentário apareça imediatamente

    // Preparar a consulta SQL
    $stmt = $conn->prepare($sql);
    
    // Vincular os parâmetros
    $stmt->bind_param("iss", $noticia_id, $nome, $comentario);  // 'i' para inteiro, 's' para string

    // Executar a consulta
    if ($stmt->execute()) {
        // Comentário inserido com sucesso
    } else {
        echo "Erro ao enviar o comentário: " . $stmt->error;
    }

    // Fechar a conexão com o banco de dados
    $stmt->close();
    $conn->close();

    // Redirecionar de volta para a página da notícia específica
    header("Location: noticias_saude.php"); // Redireciona para a página de notícias de Saúde
    exit();
}
?>
