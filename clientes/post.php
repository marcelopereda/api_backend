<?php


try {
    // Recuperar informações do cliente do corpo da requisição POST
    $postfields = json_decode(file_get_contents("php://input"), true); // Lê os dados do corpo da requisição POST e os decodifica em um array associativo
    // var_dump($postfields); // Exibe os dados recebidos para depuração

    // Verifica se os dados foram recebidos corretamente
    if (!empty($postfields)) {
        
       
        $nome = $postfields['nome'] ?? null;
        $imagem = $postfields['imagem'] ?? null; // Atribui o valor do campo 'image' se existir, caso contrário, atribui null
        $cpf = $postfields['cpf'] ?? null; // Atribui o valor do campo 'cpf' se existir, caso contrário, atribui null
        $whatsapp = $postfields['whatsapp'] ?? null; // Atribui o valor do campo 'whatsapp' se existir, caso contrário, atribui null
        $email = $postfields['email'] ?? null; // Atribui o valor do campo 'email' se existir, caso contrário, atribui null
        $endereco = $postfields['endereco'] ?? null;
        $logradouro = $postfields['endereco']['logradouro'] ?? null;
        $numero = $postfields['endereco']['numero'] ?? null;
        $complemento = $postfields['endereco']['complemento'] ?? null;
        $bairro = $postfields['endereco']['bairro'] ?? null;
        $cidade = $postfields['endereco']['cidade'] ?? null;
        $estado = $postfields['endereco']['estado'] ?? null;
        $cep = $postfields['endereco']['cep'] ?? null;

        // Verifica se todos os campos obrigatórios foram preenchidos
        if (empty($nome) || empty($postfields['endereco'])) { // Verifica se o campo 'name' ou 'address' estão vazios
            http_response_code(400); // Define o código de resposta HTTP como 400 (solicitação inválida)
            throw new Exception('Nome e endereço são obrigatórios'); // Lança uma exceção se os campos obrigatórios não forem preenchidos
        }

        $sql = "
        INSERT INTO clientes (nome,imagem,cpf,whatsapp,email, logradouro, numero, complemento, bairro, cidade, estado, cep) VALUES
         (  
            :nome,
            :imagem,
            :cpf,
            :whatsapp,
            :email,
            :logradouro,
            :numero,
            :complemento,
            :bairro,
            :cidade,
            :estado,
            :cep
        )"; // Define a consulta SQL para inserir um novo cliente na tabela 'clientes'

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // Vincula o parâmetro ':name' à variável $name
        $stmt->bindParam(':imagem', $imagem, PDO::PARAM_STR); // Vincula o parâmetro ':image' à variável $image
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR); // Vincula o parâmetro ':cpf' à variável $cpf
        $stmt->bindParam(':whatsapp',$whatsapp, PDO::PARAM_STR); // Vincula o parâmetro ':whatsapp' à variável $whatsapp
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Vincula o parâmetro ':email' à variável $email
        $stmt->bindParam(':logradouro', $logradouro); // Vincula o parâmetro ':address' à variável $address
        $stmt->bindParam(':numero', $numero); // Vincula o parâmetro ':number' à variável $number
        $stmt->bindParam(':complemento', $complemento, is_null($complemento) ? PDO::PARAM_NULL : PDO::PARAM_STR); // Vincula o parâmetro ':complement' à variável $complement
        $stmt->bindParam(':bairro', $bairro); // Vincula o parâmetro ':neighborhood' à variável $neighborhood
        $stmt->bindParam(':cidade', $cidade); // Vincula o parâmetro ':city' à variável $city
        $stmt->bindParam(':estado', $estado); // Vincula o parâmetro ':state' à variável $state
        $stmt->bindParam(':cep', $cep); // Vincula o parâmetro ':zip' à variável $zip

        $stmt->execute(); // Executa a consulta SQL

        $result = array(
            'status' => 'success', // Indica que a operação foi bem-sucedida
            'message' => 'Cliente cadastrado com sucesso', // Mensagem de sucesso
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
