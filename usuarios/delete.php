<?php

try {
    //verificar se o ID está vindo na URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "
        DELETE FROM usuarios
        WHERE id_usuario = :id
        "; // SQL para deletar o cliente com o ID fornecido

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Vincula o parâmetro ':id' à variável $id
        $stmt->execute(); // Executa a consulta SQL

    } else {
        throw new Exception('ID inválido ou não fornecido.');
    }

    $result = array(
        'status' => 'error',
        'message' => 'Usuário excluído com sucesso',
    );
} catch (Exception $e) {
    http_response_code(400); // Define o código de status HTTP como 400 (Bad Request)
    $result = array(
        'status' => 'error',
        'message' => $e->getMessage(),
    );
} finally {
    echo json_encode($result);
    $conn = null; // Fecha a conexão com o banco de dados
}
