<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Listagem de Clientes</h1>

        <?php
        // Incluir arquivo de conexão com o banco de dados
        include 'config.php';

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

        // Query SQL para listar clientes
        $sql = "SELECT * FROM Clientes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Tabela para exibir os resultados
            echo '<table>';
            echo '<tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Telefone</th><th>Celular</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $telefone_formatado = formatarTelefone($row['fone']);
                $celular_formatado = formatarTelefone($row['celular']);
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . $row['endereco'] . '</td>';
                echo '<td>' . $telefone_formatado . '</td>';
                echo '<td>' . $celular_formatado . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Nenhum cliente cadastrado.</p>';
        }

        // Fechar conexão com o banco de dados
        $conn->close();
        ?>

        <!-- Botão de voltar -->
        <a href="index.html" class="button">Voltar</a>
    </div>
</body>
</html>
