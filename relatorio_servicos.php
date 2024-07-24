<?php
include 'config.php';

$sql = "SELECT Servicos.*, Clientes.nome AS cliente_nome, Veiculos.modelo AS veiculo_modelo 
        FROM Servicos 
        JOIN Clientes ON Servicos.cliente_id = Clientes.id
        JOIN Veiculos ON Servicos.veiculo_id = Veiculos.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Serviços</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Relatório de Serviços</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Cliente</th>
            <th>Veículo</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['tipo']}</td>
                        <td>{$row['cliente_nome']}</td>
                        <td>{$row['veiculo_modelo']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum serviço encontrado</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
