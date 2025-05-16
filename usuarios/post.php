<?php


try {
    // Recuperar informações do cliente do corpo da requisição POST
    $postfields = json_decode(file_get_contents("php://input"), true); // Lê os dados do corpo da requisição POST e os decodifica em um array associativo
    // var_dump($postfields); // Exibe os dados recebidos para depuração

    // Verifica se os dados foram recebidos corretamente
    if (!empty($postfields)) {

        $nome = $postfields['nome'] ?? null; // Atribui o valor do campo 'name' se existir, caso contrário, atribui null
        $email = $postfields['email'] ?? null; // Atribui o valor do campo 'age' se existir, caso contrário, atribui null
        $senha = sha1($postfields['senha'])?? null; // Atribui o valor do campo 'address' se existir, caso contrário, atribui null
      


        // Verifica se todos os campos obrigatórios foram preenchidos
        if (empty($nome) || empty($email) || empty($senha)) { // Verifica se o campo 'name' ou 'address' estão vazios
            http_response_code(400); // Define o código de resposta HTTP como 400 (solicitação inválida)
            throw new Exception('Nome,E-mail e Senha são obrigatórios'); // Lança uma exceção se os campos obrigatórios não forem preenchidos
        }

        $sql = "
        INSERT INTO usuarios (nome,email,senha) VALUES
         (  
            :nome,
            :email,
            :senha
        )"; // Define a consulta SQL para inserir um novo cliente na tabela 'clientes'

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // Vincula o parâmetro ':name' à variável $name
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Vincula o parâmetro ':age' à variável $age
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR); // Vincula o parâmetro ':address' à variável $address
     

        $stmt->execute(); // Executa a consulta SQL

        $result = array(
            'status' => 'success', // Indica que a operação foi bem-sucedida
            'message' => 'Usuario cadastrado com sucesso', // Mensagem de sucesso
        );

        

    } else {
        http_response_code(400); // Define o código de resposta HTTP como 400 (solicitação inválida)
        throw new Exception("Nenhum dado foi enviado !"); // Lança uma exceção se os dados não foram recebidos corretamente
    }
} catch (Exception $e) {
    // Se ocorrer um erro, retorna uma mensagem de erro em formato JSON
   
    $result = array(
        'status' => 'error', // Indica que ocorreu um erro
        'message' => 'Error: ' . $e->getMessage(), // Mensagem de erro
    );
} finally {

    echo json_encode($result); // Retorna os dados em formato JSON
    $conn = null; // Fecha a conexão com o banco de dados
}
