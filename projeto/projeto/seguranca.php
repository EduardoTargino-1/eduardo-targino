<?php
// Iniciar a sessão, se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: index.php');
    exit();
}

// Verificação de segurança adicional: Comparar o agente de usuário (User-Agent) e o IP do cliente
if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
    // O IP do usuário foi alterado
    session_unset();
    session_destroy();
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    // O agente de usuário (navegador) foi alterado
    session_unset();
    session_destroy();
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Verifica se a última atividade foi mais de 30 minutos atrás (1800 segundos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    // Se a inatividade for maior que 30 minutos, destrói a sessão
    session_unset();
    session_destroy();
    header('Location: index.php'); // Redireciona para o login
    exit();
}

// Atualiza o tempo da última atividade
$_SESSION['last_activity'] = time();
?>
