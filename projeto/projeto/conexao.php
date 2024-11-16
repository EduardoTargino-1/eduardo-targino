<?php
// Configuração do banco de dados
$host = 'localhost';  // Se você estiver usando um servidor local
$dbname = 'projeto';  // Nome do seu banco de dados
$username_db = 'root'; // Nome do usuário do banco (geralmente 'root' no ambiente local)
$password_db = '';     // Senha do banco de dados (geralmente está vazio no localhost)

// Conexão com o banco de dados usando mysqli
$conn = mysqli_connect($host, $username_db, $password_db, $dbname);

if (!$conn) {
    die("Erro de conexão com o banco de dados: " . mysqli_connect_error());
}
?>
