<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "oficina");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebe os dados do POST
$data = json_decode(file_get_contents('php://input'), true);
$clienteId = $data['clienteId'];
$totalGeral = $data['totalGeral'];

// Insere o recibo no banco de dados
$stmt = $conn->prepare("INSERT INTO recibos (cliente_id, total_geral, data) VALUES (?, ?, NOW())");
$stmt->bind_param("id", $clienteId, $totalGeral);
$stmt->execute();
$reciboId = $stmt->insert_id;

// Insere os produtos relacionados ao recibo
$stmt_produto = $conn->prepare("INSERT INTO produtos_recibo (recibo_id, quantidade, descricao, valor_unitario, total) VALUES (?, ?, ?, ?, ?)");
foreach ($data['produtos'] as $produto) {
    $stmt_produto->bind_param("iisdd", $reciboId, $produto['quantidade'], $produto['descricao'], $produto['valorUnitario'], $produto['total']);
    $stmt_produto->execute();
}

// Fecha a conexão
$stmt_produto->close();
$stmt->close();
$conn->close();

echo json_encode(["success" => true]);
?>
