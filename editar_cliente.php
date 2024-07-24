<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Cliente</h1>

    <?php
    include 'config.php';

    // Verifica se foi passado o parâmetro 'id' na URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Query para buscar os dados do cliente pelo ID
        $sql = "SELECT * FROM Clientes WHERE id = $id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $cliente = $result->fetch_assoc();  // Obter dados do cliente
        } else {
            echo "Cliente não encontrado.";
            exit;  // Encerrar o script se o cliente não for encontrado
        }
    } else {
        echo "Parâmetro ID não encontrado na URL.";
        exit;  // Encerrar o script se não houver parâmetro 'id' na URL
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>

    <!-- Formulário para editar cliente -->
    <form action="atualizar_cliente.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $cliente['nome']; ?>"><br><br>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="<?php echo $cliente['endereco']; ?>"><br><br>
        <label for="fone">Fone:</label>
        <input type="text" id="fone" name="fone" value="<?php echo $cliente['fone']; ?>"><br><br>
        <label for="celular">Celular:</label>
        <input type="text" id="celular" name="celular" value="<?php echo $cliente['celular']; ?>"><br><br>
        <input type="submit" value="Atualizar">
    </form>

    <a href="listar_clientes.php"><button>Voltar</button></a>
</body>
</html>
