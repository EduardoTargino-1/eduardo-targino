<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Token</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .input-field {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            width: 100%;
            max-width: 400px;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Iniciar a sessão
        session_start();

        // Incluir o arquivo de conexão com o banco de dados
        include('conexao.php');

        // Obter o ID do usuário logado
        $usuario_id = $_SESSION['usuario_id'];

        // Buscar o token no banco de dados de acordo com o ID do usuário
        $sql = "SELECT token FROM recuperacao_senha WHERE usuario_id = ? ORDER BY data_solicitacao DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $token = $row['token'] ?? 'Token não encontrado';

        // Exibir o token
        echo '<p>Token:</p>';
        echo '<input type="text" value="' . htmlspecialchars($token) . '" id="token" class="input-field" readonly>';
        ?>
        <button onclick="copyToken()">Copiar Token</button>
        <button onclick="deleteToken()">Deletar Token</button>
        <div class="navigation-buttons">
            <button onclick="goBack()">Voltar</button>
            <button onclick="goForward()">Avançar</button>
        </div>
    </div>

    <script>
        function copyToken() {
            var copyText = document.getElementById("token");
            navigator.clipboard.writeText(copyText.value).then(function() {
                alert("Token copiado: " + copyText.value);
            }, function(err) {
                alert("Falha ao copiar o token: ", err);
            });
        }

        function deleteToken() {
            var tokenField = document.getElementById("token");
            tokenField.value = "";
            alert("Token deletado.");
        }

        function goBack() {
            window.location.href = "login_email.php";
        }

        function goForward() {
            // Redirecione para outra tela, altere a URL conforme necessário
            window.location.href = "resetar_senha.php";
        }
    </script>
</body>
</html>
