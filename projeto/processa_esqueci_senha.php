<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    // Verificar se o campo de e-mail está preenchido
    if (empty($email)) {
        header('Location: esqueci_senha.php?error=empty_email');
        exit();
    }

    // Verificar se o e-mail existe no banco de dados
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Gerar um token de recuperação de senha
        $token = bin2hex(random_bytes(50));
        $usuario_id = $row['id'];

        // Inserir o token no banco de dados
        $sql = "INSERT INTO recuperacao_senha (usuario_id, token) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die("Erro na preparação da declaração: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "is", $usuario_id, $token);
        mysqli_stmt_execute($stmt);

        // Enviar o e-mail de recuperação de senha
        $reset_link = "http://seusite.com/resetar_senha.php?token=$token";
        $mensagem = "Clique no link a seguir para resetar sua senha: $reset_link";
        mail($email, "Recuperação de Senha", $mensagem);

        header('Location: esqueci_senha.php?success=email_sent');
        exit();
    } else {
        header('Location: esqueci_senha.php?error=email_not_found');
        exit();
    }
}
?>
