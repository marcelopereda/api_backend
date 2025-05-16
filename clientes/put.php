<?php

try {
    //recuperar informações de formulário vindo do frontend
    $postfields = json_decode(file_get_contents("php://input"), true);

    //verificar se existe informações de formulário
    if (!empty($postfields)) {
        
        $id = $postfields['id_cliente'];
        $nome = $postfields['nome'] ?? null;
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

        if (empty($id)) {
            http_response_code(400);
            throw new Exception("ID do cliente não foi informado!");
        }

        //verifica campos obrigatórios
        if (empty($nome) || empty($postfields['endereco'])) {
            http_response_code(400);
            throw new Exception("Nome e endereço são campos obrigatórios!");
        }

        $sql = "
    UPDATE clientes SET
    nome = :nome,
    cpf = :cpf,
    whatsapp = :whatsapp,
    email = :email,
    logradouro = :logradouro,
    numero = :numero,
    complemento = :complemento,
    bairro = :bairro,
    cidade = :cidade,
    estado = :estado,
    cep = :cep
    WHERE id_cliente = :id
    ";

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Vincula o parâmetro ':id' à variável $id
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // Vincula o parâmetro ':name' à variável $name
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR); // Vincula o parâmetro ':cpf' à variável $cpf
        $stmt->bindParam(':whatsapp', PDO::PARAM_STR); // Vincula o parâmetro ':whatsapp' à variável $whatsapp
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Vincula o parâmetro ':email' à variável $email
        $stmt->bindParam(':logradouro', $logradouro); // Vincula o parâmetro ':address' à variável $address
        $stmt->bindParam(':numero', $numero); // Vincula o parâmetro ':number' à variável $number
        $stmt->bindParam(':complemento', $complemento, is_null($complemento) ? PDO::PARAM_NULL : PDO::PARAM_STR); // Vincula o parâmetro ':complement' à variável $complement
        $stmt->bindParam(':bairro', $bairro); // Vincula o parâmetro ':neighborhood' à variável $neighborhood
        $stmt->bindParam(':cidade', $cidade); // Vincula o parâmetro ':city' à variável $city
        $stmt->bindParam(':estado', $estado); // Vincula o parâmetro ':state' à variável $state
        $stmt->bindParam(':cep', $cep); // Vincula o parâmetro ':zip' à variável $zip

        $stmt->execute();

        $result = array(
            'status' => 'sucess',
            'message' => 'Cliente alterado com sucesso!'
        );
    } else {
        http_response_code(400);
        //se não houver dados, retorna um erro
        throw new Exception("Nenhum dado foi recebido!");
    }
} catch (Exception $e) {
    $result =  array(
        'status' => 'error',
        'message' => $e->getMessage(),
    );
} finally {
    //retorna o resultado em formato JSON);
    echo json_encode($result);
    //fecha a conexão com o banco de dados
    $conn = NULL;
}
