<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $titulo = $_POST['titulo'] ?? '';
    $conteudo = $_POST['conteudo'] ?? '';
    $imagem = $_FILES['imagem'] ?? null;
    $usuario_id = $_SESSION['id'] ?? null; // Pegando o ID do usuário logado

    // Verificar se os campos estão preenchidos
    if (empty($titulo) || empty($conteudo) || empty($imagem['name']) || empty($usuario_id)) {
        header('Location: insere_noticia.php?error=empty_fields');
        exit();
    }

    // Diretório de destino para o upload da imagem
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Criar diretório se não existir
    }
    $target_file = $target_dir . basename($imagem["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar se o arquivo é uma imagem real
    $check = getimagesize($imagem["tmp_name"]);
    if ($check === false) {
        header('Location: insere_noticia.php?error=invalid_image');
        exit();
    }

    // Verificar se o arquivo já existe
    if (file_exists($target_file)) {
        header('Location: insere_noticia.php?error=file_exists');
        exit();
    }

    // Limitar o tamanho do arquivo (por exemplo, 5MB)
    if ($imagem["size"] > 5000000) {
        header('Location: insere_noticia.php?error=file_too_large');
        exit();
    }

    // Permitir apenas certos formatos de imagem (jpg, png, jpeg, gif)
    $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_formats)) {
        header('Location: insere_noticia.php?error=invalid_format');
        exit();
    }

    // Tentar mover o arquivo para o diretório de destino
    if (!move_uploaded_file($imagem["tmp_name"], $target_file)) {
        header('Location: insere_noticia.php?error=upload_failed');
        exit();
    }

    // Verificar se o caminho do arquivo da imagem não é nulo
    if (empty($target_file)) {
        header('Location: insere_noticia.php?error=upload_failed');
        exit();
    }

    // Preparar a consulta SQL para inserir a notícia
    $sql = "INSERT INTO noticias (usuario_id, titulo, conteudo, imagem) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        error_log("Erro na preparação da declaração: " . mysqli_error($conn));
        header('Location: insere_noticia.php?error=server_error');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "isss", $usuario_id, $titulo, $conteudo, $target_file);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Notícia inserida com sucesso
        header('Location: insere_noticia.php?success=inserted');
        exit();
    } else {
        error_log("Erro ao inserir a notícia: " . mysqli_error($conn));
        header('Location: insere_noticia.php?error=server_error');
        exit();
    }

    // Fechar a declaração
    mysqli_stmt_close($stmt);
}

// Fechar a conexão
mysqli_close($conn);
?>
