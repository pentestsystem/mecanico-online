<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fechamento Diário</title>
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
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <div class="container">
        <h3>Fechamento Diário</h3>
        <form id="formFechamento">
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label for="totalReceitas">Total de Receitas:</label>
                <input type="number" id="totalReceitas" name="totalReceitas" step="0.01" required>
            </div>
            <div class="form-group">
                <button type="button" onclick="salvarFechamento()" class="button">Salvar Fechamento</button>
            </div>
        </form>
        <div class="total">
            <strong>Total de Lucro do Dia: R$ <span id="totalLucro">0.00</span></strong>
        </div>
    </div>

    <script>
        function salvarFechamento() {
            var data = document.getElementById('data').value;
            var totalReceitas = parseFloat(document.getElementById('totalReceitas').value);

            if (!data || isNaN(totalReceitas)) {
                alert('Por favor, preencha todos os campos corretamente.');
                return;
            }

            // Simular salvamento dos dados (aqui você pode adicionar a lógica para salvar no banco de dados)
            console.log('Data:', data);
            console.log('Total de Receitas:', totalReceitas);

            // Atualizar o total de lucro (aqui você deve pegar o valor atual do lucro diário do banco de dados e somar com o total de receitas)
            var totalLucroElement = document.getElementById('totalLucro');
            var currentLucro = parseFloat(totalLucroElement.textContent.replace(',', '.'));
            var novoLucro = currentLucro + totalReceitas;
            totalLucroElement.textContent = novoLucro.toFixed(2);
        }
    </script>
</body>
</html>
