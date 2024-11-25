<?php
// Iniciar a sessão
session_start();

// Verificar tempo de inatividade da sessão
$timeout = 1800; // Tempo em segundos (30 minutos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();     // Limpa a sessão
    session_destroy();   // Destrói a sessão
    header('Location: index.php'); // Redireciona para o login
    exit();
}
$_SESSION['last_activity'] = time(); // Atualiza o tempo de atividade

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: index.php');
    exit();
}

// Conectar ao banco de dados
include('conexao.php'); // Inclua seu arquivo de conexão

// ID do usuário logado
$usuario_id = $_SESSION['id'];

// Consultar o número de postagens (notícias) e comentários de forma otimizada
$sql = "
    SELECT 
        COUNT(DISTINCT n.id) AS total_postagens, 
        COUNT(DISTINCT c.id) AS total_comentarios_noticias
    FROM noticias n
    LEFT JOIN comentarios c ON c.noticia_id = n.id
    WHERE n.usuario_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_postagens = $row['total_postagens'];
$total_comentarios_noticias = $row['total_comentarios_noticias'];

// Dados do usuário
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Usuário desconhecido'; // Verifica se o email está definido
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <!-- Carregar Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Reset de margens e paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fontes e cores gerais */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
            font-size: 15px;
            margin: 0;
        }

        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            padding: 20px 15px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            color: white;
            font-size: 1.4em;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 12px 0;
            font-size: 1em;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2980b9;
        }

        .sidebar i {
            margin-right: 10px;
        }

        /* Container principal */
        .content {
            margin-left: 240px;
            padding: 20px;
            max-width: 100%;
        }

        /* Container das notícias */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Caixa de painel */
        .dashboard-box {
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-box h2 {
            font-size: 1.6em;
            margin-bottom: 10px;
            color: white;
        }

        .dashboard-box p {
            font-size: 1em;
            color: #7f8c8d;
        }

        /* Estatísticas */
        .statistics-box {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        /* Cartões de estatísticas */
        .stat-card {
            background: linear-gradient(135deg, #3498db, #9b59b6);
            color: #fff;
            padding: 20px;
            width: 48%;
            border-radius: 12px;
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #9b59b6, #3498db);
        }

        .stat-card h3 {
            font-size: 1.4em;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .stat-card p {
            font-size: 1.1em;
            color: #ecf0f1;
        }

        .stat-card strong {
            font-size: 1.2em;
            color: #f39c12;
            font-weight: bold;
        }

        /* Animação para números */
        .stat-card .counter {
            font-size: 1.8em;
            font-weight: 700;
            color: #ecf0f1;
            margin-top: 10px;
            animation: countAnimation 2s ease-in-out forwards;
        }

        @keyframes countAnimation {
            0% {
                transform: translateY(10px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .stat-card {
                width: 100%;
                min-height: 160px;
            }

            .dashboard-box h2 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Menu</h2>
    <a href="insere_noticia.php"><i class="fas fa-plus"></i> Inserir Notícias</a>
    <a href="altera_usuario.php"><i class="fas fa-user-edit"></i> Alterar Informações</a>
    <a href="noticias.php"><i class="fas fa-newspaper"></i> Notícias</a>
    <a href="minhas_noticias.php"><i class="fas fa-file-alt"></i> Minhas Notícias</a>
    <a href="index.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
</div>

<!-- Conteúdo principal -->
<div class="content">
    <div class="container">
        <div class="dashboard-box">
            <h2>Bem-vindo, <?php echo htmlspecialchars($email); ?>!</h2>
            <p>Você está logado e pode acessar o painel de controle para gerenciar suas notícias.</p>
        </div>

        <!-- Estatísticas -->
        <div class="statistics-box">
            <div class="stat-card">
                <h3>Estatísticas</h3>
                <p><strong>Total de Notícias:</strong> <?php echo $total_postagens; ?></p>
                <p><strong>Total de Comentários nas suas Notícias:</strong> <?php echo $total_comentarios_noticias; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
