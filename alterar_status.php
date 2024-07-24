<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "oficina");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se a solicitação foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Atualiza o status no banco de dados
    $stmt = $conn->prepare("UPDATE servicos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo 'Status atualizado com sucesso!';
    } else {
        echo 'Erro ao atualizar o status: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
