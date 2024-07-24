<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Preparar a query para excluir o veículo
    $sql = "DELETE FROM veiculos WHERE id = ?";
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
