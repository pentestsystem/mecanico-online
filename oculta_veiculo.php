<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Preparar a query para marcar o veículo como inativo
    $sql = "UPDATE veiculos SET ativo = FALSE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $conn->close();
}
?>
