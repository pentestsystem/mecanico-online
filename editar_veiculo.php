<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Veículo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Veículo</h1>

    <?php
    include 'config.php';

    // Verifica se foi passado o parâmetro 'id' na URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Query para buscar os dados do veículo pelo ID
        $sql = "SELECT * FROM Veiculos WHERE id = $id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $veiculo = $result->fetch_assoc();  // Obter dados do veículo
        } else {
            echo "Veículo não encontrado.";
            exit;  // Encerrar o script se o veículo não for encontrado
        }
    } else {
        echo "Parâmetro ID não encontrado na URL.";
        exit;  // Encerrar o script se não houver parâmetro 'id' na URL
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>

    <!-- Formulário para editar veículo -->
    <form action="atualizar_veiculo.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $veiculo['id']; ?>">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?php echo $veiculo['modelo']; ?>"><br><br>
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?php echo $veiculo['marca']; ?>"><br><br>
        <label for="ano">Ano:</label>
        <input type="text" id="ano" name="ano" value="<?php echo $veiculo['ano']; ?>"><br><br>
        <input type="submit" value="Atualizar">
    </form>

    <a href="listar_veiculos.php"><button>Voltar</button></a>
</body>
</html>
