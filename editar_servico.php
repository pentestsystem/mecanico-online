<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Serviço</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Serviço</h1>

    <?php
    include 'config.php';

    // Verifica se foi passado o parâmetro 'id' na URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Query para buscar os dados do serviço pelo ID
        $sql = "SELECT * FROM Servicos WHERE id = $id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $servico = $result->fetch_assoc();  // Obter dados do serviço
        } else {
            echo "Serviço não encontrado.";
            exit;  // Encerrar o script se o serviço não for encontrado
        }
    } else {
        echo "Parâmetro ID não encontrado na URL.";
        exit;  // Encerrar o script se não houver parâmetro 'id' na URL
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>

    <!-- Formulário para editar serviço -->
    <form action="atualizar_servico.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo">
            <option value="pedido" <?php if ($servico['tipo'] == 'pedido') echo 'selected'; ?>>Pedido</option>
            <option value="orçamento" <?php if ($servico['tipo'] == 'orçamento') echo 'selected'; ?>>Orçamento</option>
            <option value="recibo" <?php if ($servico['tipo'] == 'recibo') echo 'selected'; ?>>Recibo</option>
        </select><br><br>
        <label for="cliente_id">Cliente:</label>
        <input type="text" id="cliente_id" name="cliente_id" value="<?php echo $servico['cliente_id']; ?>"><br><br>
        <label for="veiculo_id">Veículo:</label>
        <input type="text" id="veiculo_id" name="veiculo_id" value="<?php echo $servico['veiculo_id']; ?>"><br><br>
        <input type="submit" value="Atualizar">
    </form>

    <a href="listar_servicos.php"><button>Voltar</button></a>
</body>
</html>
