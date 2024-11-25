<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Token</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Cor de fundo atualizada */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #2c3e50; /* Cor do título atualizada */
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #2c3e50; /* Cor do botão atualizada */
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9; /* Cor de hover atualizada */
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        .navigation-buttons button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .navigation-buttons button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Gerar Token</h2>
    
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
