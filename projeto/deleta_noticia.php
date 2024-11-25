<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['id'];

// Verificar se o ID da notícia foi passado na URL
if (!isset($_GET['id'])) {
    die("Notícia não encontrada.");
}

$id = intval($_GET['id']);

// Preparar a consulta SQL para deletar a notícia
$sql = "DELETE FROM noticias WHERE id = ? AND usuario_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("Erro na preparação da declaração: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ii", $id, $usuario_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    // Notícia deletada com sucesso
    header('Location: minhas_noticias.php?success=deleted');
    exit();
} else {
    die("Erro ao deletar a notícia: " . mysqli_error($conn));
}

// Fechar a declaração
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
