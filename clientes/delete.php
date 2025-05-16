<?php

try {
    // VERIFICAR SE ESTA VINDO UM ID NA URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // Verifica se o parâmetro 'id' está definido e é numérico
        $id = $_GET['id']; // Atribui o valor do parâmetro 'id' à variável $id

        $sql = "
       DELETE FROM clientes
       WHERE id_cliente = :id
      ";

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Vincula o parâmetro ':id' ao valor da variável $id
        $stmt->execute(); // Executa a consulta SQL

    } else {
        throw new Exception('ID inválido ou não fornecido.'); // Lança uma exceção se o ID não foi fornecido
    }
} catch (Exception $e) {
    // Se ocorrer um erro, retorna uma mensagem de erro em formato JSON
    echo json_encode(
        array(
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage() // Mensagem de erro indicando que o método GET não está implementado,
        ) // Retorna uma mensagem em formato JSON informando que o método não é permitido 
    );
} finally {
    // Se tudo ocorrer bem, retorna uma mensagem de sucesso em formato JSON
    echo json_encode(
        array(
            'status' => 'success',
            'message' => 'Cliente deletado com sucesso .', // Mensagem de sucesso indicando que o cliente foi excluído com sucesso
        ) // Retorna uma mensagem em formato JSON informando que o método não é permitido 
    );
}
