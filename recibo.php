<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100%;
            height: auto;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group select {
            width: calc(50% - 10px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: inline-block;
            margin-right: 10px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        .button:hover {
            background-color: #45a049;
        }
        .total {
            margin-top: 20px;
            padding: 10px;
            background-color: #f2f2f2;
            border-radius: 4px;
            overflow: hidden;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .checkbox-group {
            margin-bottom: 20px;
        }
        .checkbox-group label {
            display: inline-block;
            margin-right: 10px;
        }
        .signature {
            margin-top: 50px;
        }
        .signature-line {
            width: 100%;
            border-top: 1px solid #000;
            text-align: center;
            margin-top: 20px;
            padding-top: 5px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="caminho/para/sua/logo.png" alt="Logo da Empresa">
        </div>
        <h3>Recibo de Produtos</h3>

        <!-- Caixa de seleção de clientes -->
        <form id="reciboForm" onsubmit="return false;">
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <select id="cliente" name="cliente" required>
                    <option value="">Selecione um cliente</option>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "oficina");
                    if ($conn->connect_error) {
                        die("Falha na conexão: " . $conn->connect_error);
                    }
                    $result = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum cliente encontrado</option>";
                    }
                    $conn->close();
                    ?>
                </select>
            </div>

            <!-- Formulário para adicionar produtos -->
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" required>
            </div>
            <div class="form-group">
                <label for="valorUnitario">Valor Unitário:</label>
                <input type="number" id="valorUnitario" name="valorUnitario" step="0.01" required>
            </div>
            <div class="form-group">
                <button type="button" onclick="adicionarProduto()" class="button">Adicionar Produto</button>
            </div>
        </form>

        <!-- Tabela para exibir os produtos inseridos -->
        <table>
            <thead>
                <tr>
                    <th>Quantidade</th>
                    <th>Descrição</th>
                    <th>Valor Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="tabelaProdutos">
                <!-- Aqui serão adicionadas as linhas dinamicamente -->
            </tbody>
        </table>

        <!-- Total dos produtos -->
        <div class="total">
            <strong>Total Geral dos Produtos Registrados: R$ <span id="totalGeral">0.00</span></strong>
        </div>

        <!-- Checkboxes -->
        <div class="checkbox-group">
            <label><input type="checkbox" id="pago" name="status" value="PAGO"> PAGO</label>
            <label><input type="checkbox" id="orcamento" name="status" value="ORÇAMENTO"> ORÇAMENTO</label>
            <label><input type="checkbox" id="recibo" name="status" value="RECIBO"> RECIBO</label>
        </div>

        <!-- Botões de ações -->
        <div class="form-group clearfix">
            <button type="button" onclick="imprimirRecibo()" class="button" style="margin-right: 10px;">Imprimir Recibo</button>
            <button type="button" onclick="gerarPDF()" class="button" style="margin-right: 10px;">Gerar PDF</button>
            <button onclick="window.location.href = 'index.html';" class="button" style="margin-right: 10px;">VOLTAR</button>
        </div>

        <!-- Linha para assinatura ou carimbo -->
        <div class="signature">
            <div class="signature-line">Assinatura do Técnico</div>
        </div>
    </div>

    <script>
        let totalGeral = 0;

        function adicionarProduto() {
            // Obter valores dos campos do formulário
            var quantidade = parseFloat(document.getElementById('quantidade').value);
            var descricao = document.getElementById('descricao').value;
            var valorUnitario = parseFloat(document.getElementById('valorUnitario').value);

            // Verificar se os campos são válidos
            if (isNaN(quantidade) || quantidade <= 0) {
                alert("Quantidade inválida.");
                return;
            }
            if (!descricao.trim()) {
                alert("Descrição não pode estar vazia.");
                return;
            }
            if (isNaN(valorUnitario) || valorUnitario <= 0) {
                alert("Valor Unitário inválido.");
                return;
            }

            var total = quantidade * valorUnitario;

            var tableBody = document.getElementById('tabelaProdutos');
            var newRow = tableBody.insertRow();

            var cellQuantidade = newRow.insertCell(0);
            var cellDescricao = newRow.insertCell(1);
            var cellValorUnitario = newRow.insertCell(2);
            var cellTotal = newRow.insertCell(3);
            var cellAction = newRow.insertCell(4);

            cellQuantidade.textContent = quantidade;
            cellDescricao.textContent = descricao;
            cellValorUnitario.textContent = valorUnitario.toFixed(2);
            cellTotal.textContent = total.toFixed(2);

            var deleteButton = document.createElement('button');
            deleteButton.textContent = 'Excluir';
            deleteButton.className = 'button';
            deleteButton.style.backgroundColor = '#f44336';
            deleteButton.style.margin = '0';
            deleteButton.onclick = function() {
                removerProduto(newRow, total);
            };
            cellAction.appendChild(deleteButton);

            totalGeral += total;
            document.getElementById('totalGeral').textContent = totalGeral.toFixed(2);

            document.getElementById('quantidade').value = '';
            document.getElementById('descricao').value = '';
            document.getElementById('valorUnitario').value = '';
            document.getElementById('quantidade').focus();
        }

        function removerProduto(row, total) {
            var tableBody = document.getElementById('tabelaProdutos');
            tableBody.deleteRow(row.rowIndex - 1);

            totalGeral -= total;
            document.getElementById('totalGeral').textContent = totalGeral.toFixed(2);
        }

        function obterStatusSelecionado() {
            var status = [];
            document.querySelectorAll('input[name="status"]:checked').forEach((checkbox) => {
                status.push(checkbox.value);
            });
            return status.join(', ');
        }

        function imprimirRecibo() {
            var cliente = document.getElementById('cliente').options[document.getElementById('cliente').selectedIndex].text;
            var status = obterStatusSelecionado();

            var reciboContent = `
                <h2>Recibo</h2>
                <p>Cliente: ${cliente}</p>
                <table border="1" width="100%">
                    <tr>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                        <th>Valor Unitário</th>
                        <th>Total</th>
                    </tr>
            `;

            document.querySelectorAll('#tabelaProdutos tr').forEach((row) => {
                var quantidade = row.cells[0].textContent;
                var descricao = row.cells[1].textContent;
                var valorUnitario = row.cells[2].textContent;
                var total = row.cells[3].textContent;
                reciboContent += `
                    <tr>
                        <td>${quantidade}</td>
                        <td>${descricao}</td>
                        <td>${valorUnitario}</td>
                        <td>${total}</td>
                    </tr>
                `;
            });

            reciboContent += `
                </table>
                <p>Total Geral: R$ ${document.getElementById('totalGeral').textContent}</p>
                <p>Status: ${status}</p>
                <p class="signature-line">Assinatura do Técnico</p>
            `;

            var reciboWindow = window.open('', '', 'width=800,height=600');
            reciboWindow.document.write('<html><head><title>Recibo</title></head><body>');
            reciboWindow.document.write(reciboContent);
            reciboWindow.document.write('</body></html>');
            reciboWindow.document.close();
            reciboWindow.print();
        }

        function gerarPDF() {
            var { jsPDF } = window.jspdf;
            var doc = new jsPDF();

            var cliente = document.getElementById('cliente').options[document.getElementById('cliente').selectedIndex].text;
            var status = obterStatusSelecionado();

            doc.setFontSize(18);
            doc.text("Recibo", 14, 22);
            doc.setFontSize(12);
            doc.text(`Cliente: ${cliente}`, 14, 32);

            var headers = [["Quantidade", "Descrição", "Valor Unitário", "Total"]];
            var data = [];

            document.querySelectorAll('#tabelaProdutos tr').forEach((row) => {
                var rowData = [
                    row.cells[0].textContent,
                    row.cells[1].textContent,
                    row.cells[2].textContent,
                    row.cells[3].textContent
                ];
                data.push(rowData);
            });

            doc.autoTable({
                head: headers,
                body: data,
                startY: 40
            });

            doc.text(`Total Geral: R$ ${document.getElementById('totalGeral').textContent}`, 14, doc.lastAutoTable.finalY + 10);
            doc.text(`Status: ${status}`, 14, doc.lastAutoTable.finalY + 20);

            doc.save('recibo.pdf');
        }
    </script>
</body>
</html>
