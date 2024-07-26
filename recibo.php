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
        .form-group select, 
        .form-group textarea {
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

            <!-- Observações -->
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea id="observacoes" name="observacoes" rows="4" style="width: 100%;"></textarea>
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
                var rowIndex = this.parentNode.parentNode.rowIndex;
                tableBody.deleteRow(rowIndex - 1);
                atualizarTotalGeral(-total);
            };
            cellAction.appendChild(deleteButton);

            atualizarTotalGeral(total);

            // Limpar os campos do formulário
            document.getElementById('quantidade').value = '';
            document.getElementById('descricao').value = '';
            document.getElementById('valorUnitario').value = '';
        }

        function atualizarTotalGeral(valor) {
            totalGeral += valor;
            document.getElementById('totalGeral').textContent = totalGeral.toFixed(2);
        }

        function imprimirRecibo() {
            var cliente = document.getElementById('cliente').selectedOptions[0].text;
            var observacoes = document.getElementById('observacoes').value;
            var status = "";
            if (document.getElementById('pago').checked) status = "PAGO";
            else if (document.getElementById('orcamento').checked) status = "ORÇAMENTO";
            else if (document.getElementById('recibo').checked) status = "RECIBO";

            var popup = window.open('', '_blank');
            popup.document.write('<html><head><title>Recibo</title></head><body>');
            popup.document.write('<div class="container">');
            popup.document.write('<div class="logo"><img src="caminho/para/sua/logo.png" alt="Logo da Empresa"></div>');
            popup.document.write('<h3>Recibo de Produtos</h3>');
            popup.document.write('<strong>Cliente:</strong> ' + cliente + '<br>');
            popup.document.write('<table><thead><tr><th>Quantidade</th><th>Descrição</th><th>Valor Unitário</th><th>Total</th></tr></thead><tbody>');
            var rows = document.getElementById('tabelaProdutos').rows;
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].cells;
                popup.document.write('<tr>');
                popup.document.write('<td>' + cells[0].textContent + '</td>');
                popup.document.write('<td>' + cells[1].textContent + '</td>');
                popup.document.write('<td>' + cells[2].textContent + '</td>');
                popup.document.write('<td>' + cells[3].textContent + '</td>');
                popup.document.write('</tr>');
            }
            popup.document.write('</tbody></table>');
            popup.document.write('<strong>Total Geral dos Produtos Registrados: R$ ' + totalGeral.toFixed(2) + '</strong><br>');
            popup.document.write('<strong>Observações:</strong> ' + observacoes + '<br>');
            popup.document.write('<strong>Status:</strong> ' + status + '<br>');
            popup.document.write('<div class="signature-line">Assinatura do Técnico</div>');
            popup.document.write('</div>');
            popup.document.write('</body></html>');
            popup.document.close();
            popup.print();
        }

        function gerarPDF() {
            var { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.autoTable({ html: 'table' });

            var cliente = document.getElementById('cliente').selectedOptions[0].text;
            var observacoes = document.getElementById('observacoes').value;
            var status = "";
            if (document.getElementById('pago').checked) status = "PAGO";
            else if (document.getElementById('orcamento').checked) status = "ORÇAMENTO";
            else if (document.getElementById('recibo').checked) status = "RECIBO";

            doc.setFontSize(12);
            doc.text(20, 20, 'Recibo de Produtos');
            doc.text(20, 30, 'Cliente: ' + cliente);
            doc.autoTable({
                startY: 40,
                html: 'table',
                headStyles: { fillColor: [255, 0, 0] },
                bodyStyles: { fillColor: [255, 255, 255] },
            });
            doc.text(20, doc.autoTable.previous.finalY + 10, 'Total Geral dos Produtos Registrados: R$ ' + totalGeral.toFixed(2));
            doc.text(20, doc.autoTable.previous.finalY + 20, 'Observações: ' + observacoes);
            doc.text(20, doc.autoTable.previous.finalY + 30, 'Status: ' + status);
            doc.text(20, doc.autoTable.previous.finalY + 40, 'Assinatura do Técnico:');
            doc.line(20, doc.autoTable.previous.finalY + 45, 190, doc.autoTable.previous.finalY + 45);

            doc.save('recibo.pdf');
        }
    </script>
</body>
</html>
