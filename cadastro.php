<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente e Veículo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type=text], select {
            width: calc(100% - 16px); /* Ajusta para o tamanho do input */
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit], a.button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px; /* Espaço entre os botões */
        }
        input[type=submit]:hover, a.button:hover {
            background-color: #45a049;
        }
        .message {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Cliente e Veículo</h1>

        <!-- Mensagem de sucesso -->
        <?php if (!empty($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
        <script>
            alert("Cliente, veículo e serviço cadastrados com sucesso!");
        </script>
        <?php endif; ?>

        <!-- Formulário de Cadastro de Cliente e Veículo -->
        <form id="cadastroForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h2>Cadastro de Cliente</h2>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required><br>
            <label for="fone">Fone:</label>
            <input type="text" id="fone" name="fone"><br>
            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required><br>

            <h2>Cadastro de Veículo</h2>
            <label for="modelo">Modelo:</label>
            <input type="text" id="modelo" name="modelo" required><br>
            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" required><br>
            <label for="ano">Ano:</label>
            <input type="text" id="ano" name="ano" required><br>
            <label for="placa">Placa:</label>
            <input type="text" id="placa" name="placa" required><br>

            <h2>Tipo de Serviço</h2>
            <label for="tipo_servico">Tipo de Serviço:</label>
            <select id="tipo_servico" name="tipo_servico" required>
                <option value="orcamento">Orçamento</option>
                <option value="pedido">Pedido</option>
                <option value="recibo">Recibo</option>
                <option value="acao">Ação</option>
                <option value="concluido">Concluído</option>
            </select><br>

            <input type="submit" value="Cadastrar">
            <a href="index.html" class="button">Voltar</a>
        </form>
    </div>

    <?php
    // Função para formatar o número de telefone
    function formatarTelefone($numero) {
        if (strlen($numero) == 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $numero);
        } elseif (strlen($numero) == 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $numero);
        } else {
            return $numero; // Retorna o número sem formatação se não tiver o tamanho esperado
        }
    }

    // Processamento do formulário de cadastro
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $fone = formatarTelefone($_POST['fone']);
        $celular = formatarTelefone($_POST['celular']);

        $modelo = $_POST['modelo'];
        $marca = $_POST['marca'];
        $ano = $_POST['ano'];
        $placa = $_POST['placa'];

        $tipo_servico = $_POST['tipo_servico'];

        // Inserir cliente no banco de dados (exemplo básico, ajuste conforme seu código atual)
        include 'config.php'; // Inclua seu arquivo de conexão com o banco de dados aqui

        // Inserir cliente
        $sql_cliente = "INSERT INTO Clientes (nome, endereco, fone, celular) VALUES ('$nome', '$endereco', '$fone', '$celular')";
        if ($conn->query($sql_cliente) === TRUE) {
            $cliente_id = $conn->insert_id;  // Obtém o ID do cliente inserido com sucesso

            // Inserir veículo associado ao cliente
            $sql_veiculo = "INSERT INTO Veiculos (modelo, marca, ano, placa, cliente_id) VALUES ('$modelo', '$marca', '$ano', '$placa', '$cliente_id')";
            if ($conn->query($sql_veiculo) === TRUE) {
                // Inserir serviço associado ao cliente
                $sql_servico = "INSERT INTO Servicos (tipo, cliente_id) VALUES ('$tipo_servico', '$cliente_id')";
                if ($conn->query($sql_servico) === TRUE) {
                    $message = "Cliente, veículo e serviço cadastrados com sucesso!";
                } else {
                    $message = "Erro ao cadastrar serviço: " . $conn->error;
                }
            } else {
                $message = "Erro ao cadastrar veículo: " . $conn->error;
            }
        } else {
            $message = "Erro ao cadastrar cliente: " . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
