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
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        .button.excluir {
            background-color: #f44336; /* Vermelho para botão Excluir */
            color: white;
        }
        .button.excluir:hover {
            background-color: #cc0000; /* Cor mais escura ao passar o mouse */
        }
        .button.feito {
            background-color: #4CAF50; /* Verde para botão Feito */
            color: white;
        }
        .button.feito.vermelho {
            background-color: #f44336; /* Vermelho para botão Feito após clicar */
        }
        .button.feito:hover {
            background-color: #45a049;
        }
        .concluido {
            text-decoration: line-through;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Listagem de Veículos</h1>

        <?php
        // Incluir arquivo de conexão com o banco de dados
        include 'config.php';

        // Função para formatar a placa com o traço
        function formatPlaca($placa) {
            // Adiciona um traço entre os 3 primeiros caracteres e os 4 últimos
            return preg_replace('/([A-Z]{3})([0-9]{4})/', '$1-$2', $placa);
        }

        // Verificar se a conexão foi estabelecida corretamente
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Query SQL para listar veículos ativos com informações do cliente associado
        $sql = "SELECT v.id, v.modelo, v.placa, c.nome AS nome_cliente, v.concluido
                FROM veiculos v
                INNER JOIN clientes c ON v.cliente_id = c.id
                WHERE v.ativo = TRUE";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            // Tabela para exibir os resultados
            echo '<table>';
            echo '<tr><th>Cliente</th><th>Modelo</th><th>Placa</th><th>Ação</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $placaFormatada = formatPlaca($row['placa']);
                $concluidoClass = $row['concluido'] ? 'concluido' : '';
                $botaoTexto = $row['concluido'] ? 'Reabrir' : 'Concluir';
                $botaoClasse = $row['concluido'] ? 'feito vermelho' : 'feito';
                $acao = $row['concluido'] ? 'reabrirServico' : 'concluirServico';
                echo '<tr id="row_' . $row['id'] . '" class="' . $concluidoClass . '">';
                echo '<td>' . htmlspecialchars($row['nome_cliente']) . '</td>';
                echo '<td>' . htmlspecialchars($row['modelo']) . '</td>';
                echo '<td>' . htmlspecialchars($placaFormatada) . '</td>';
                echo '<td><button class="button ' . $botaoClasse . '" onclick="' . $acao . '(' . $row['id'] . ')">' . $botaoTexto . '</button></td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Nenhum veículo cadastrado.</p>';
        }

        // Fechar conexão com o banco de dados
        $conn->close();
        ?>


        <!-- Botão de voltar -->
        <a href="index.html" class="button">Voltar</a>

    </div>

    <script>
        function concluirServico(veiculo_id) {
            var confirmacao = confirm('Tem certeza que deseja concluir o serviço para o veículo com ID ' + veiculo_id + '?');
            
            if (confirmacao) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "concluir_servico.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        console.log("Status:", xhr.status); // Adicionado para depuração
                        console.log("Resposta:", xhr.responseText); // Adicionado para depuração
                        if (xhr.status == 200 && xhr.responseText.trim() == "success") {
                            // Altera o botão para vermelho e marca visualmente como concluído
                            var button = document.querySelector('#row_' + veiculo_id + ' .feito');
                            if (button) {
                                button.classList.add('vermelho');
                                button.textContent = 'Reabrir';
                                button.setAttribute('onclick', 'reabrirServico(' + veiculo_id + ')');
                            }
                            var row = document.getElementById('row_' + veiculo_id);
                            if (row) {
                                row.classList.add('concluido');
                            }
                        } else {
                            alert("Erro ao concluir o serviço. Tente novamente.");
                        }
                    }
                };

                xhr.send("id=" + veiculo_id);
            }
        }

        function reabrirServico(veiculo_id) {
            var confirmacao = confirm('Tem certeza que deseja reabrir o serviço para o veículo com ID ' + veiculo_id + '?');
            
            if (confirmacao) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "reabrir_servico.php", true); // Certifique-se de que o caminho está correto
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        console.log("Status:", xhr.status); // Adicionado para depuração
                        console.log("Resposta:", xhr.responseText); // Adicionado para depuração
                        if (xhr.status == 200 && xhr.responseText.trim() == "success") {
                            // Remove o risco e volta a cor verde no botão
                            var button = document.querySelector('#row_' + veiculo_id + ' .feito');
                            if (button) {
                                button.classList.remove('vermelho');
                                button.classList.add('feito');
                                button.textContent = 'Concluir';
                                button.setAttribute('onclick', 'concluirServico(' + veiculo_id + ')');
                            }
                            var row = document.getElementById('row_' + veiculo_id);
                            if (row) {
                                row.classList.remove('concluido');
                            }
                        } else {
                            alert("Erro ao reabrir o serviço. Tente novamente.");
                        }
                    }
                };

                xhr.send("id=" + veiculo_id);
            }
        }
    </script>
</body>
</html>
