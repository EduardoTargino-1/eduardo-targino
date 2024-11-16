<?php
// Iniciar a sessão
session_start();

// Incluir o arquivo de conexão com o banco de dados
include('conexao.php'); // Certifique-se de que o arquivo 'conexao.php' esteja configurado corretamente

// Verificar se a conexão com o banco de dados está ativa
if (!$conn) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

// Receber os dados do formulário
$email = $_POST['email'] ?? ''; // Usando null coalescing para evitar "undefined index"
$senha = $_POST['senha'] ?? '';

// Verificar se os campos estão preenchidos
if (empty($email) || empty($senha)) {
    // Redireciona de volta para o login com um erro se algum campo estiver vazio
    header('Location: index.php?error=empty_fields');
    exit();
}

// Preparar a consulta SQL para verificar se o usuário existe
$sql = "SELECT id, email, senha FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    error_log("Erro na preparação da declaração: " . mysqli_error($conn));
    header('Location: index.php?error=server_error');
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Buscar o usuário
$usuario = mysqli_fetch_assoc($result);

// Adicionar mensagens de depuração
if (!$usuario) {
    error_log("Usuário não encontrado para o email: $email"); // Log para depuração
    header('Location: index.php?error=invalid_credentials');
    exit();
}

// Verificar se o usuário foi encontrado e se a senha fornecida é válida
if ($usuario && password_verify($senha, $usuario['senha'])) {
    // Se a senha for válida, iniciar a sessão
    session_regenerate_id(true); // Gera um novo ID de sessão para evitar ataques de fixação de sessão

    $_SESSION['id'] = $usuario['id']; // Salva o ID do usuário na sessão
    $_SESSION['email'] = $usuario['email']; // Salva o email do usuário na sessão

    // Armazenar o IP e o User-Agent do navegador para segurança adicional
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

    // Redirecionar para a página de controle (dashboard)
    header('Location: dashboard.php');
    exit();
} else {
    error_log("Senha inválida para o email: $email"); // Log para depuração
    // Caso as credenciais sejam inválidas
    header('Location: index.php?error=invalid_credentials');
    exit();
}

// Fechar a conexão
mysqli_close($conn);
?>
