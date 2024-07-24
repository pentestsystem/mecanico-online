<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Veículos</title>
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
        <h1>Listagem de Veículos</h1>

        <?php
        include 'config.php';

        function formatPlaca($placa) {
            // Adiciona um traço entre os 3 primeiros caracteres e os 4 últimos
            return preg_replace('/([A-Z]{3})([0-9]{4})/', '$1-$2', $placa);
        }

        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        $sql = "SELECT v.id, v.modelo, v.placa, c.nome AS nome_cliente
                FROM veiculos v
                INNER JOIN clientes c ON v.cliente_id = c.id
                WHERE v.ativo = TRUE";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Cliente</th><th>Modelo</th><th>Placa</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $placaFormatada = formatPlaca($row['placa']);
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['nome_cliente']) . '</td>';
                echo '<td>' . htmlspecialchars($row['modelo']) . '</td>';
                echo '<td>' . htmlspecialchars($placaFormatada) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Nenhum veículo cadastrado.</p>';
        }

        $conn->close();
        ?>

        <!-- Botão de voltar -->
        <a href="index.html" class="button">Voltar</a>
    </div>

    <script>
        // Funções JavaScript removidas
    </script>
</body>
</html>
