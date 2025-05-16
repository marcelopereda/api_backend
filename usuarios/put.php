<?php

try {
    //recuperar informações de formulário vindo do frontend
    $postfields = json_decode(file_get_contents("php://input"), true);

    //verificar se existe informações de formulário
    if (!empty($postfields)) {
        $id = $postfields['id_usuario'] ?? null; // Atribui o valor do campo 'id_cliente' se existir, caso contrário, atribui null
        $nome = $postfields['nome'] ?? null; // Atribui o valor do campo 'name' se existir, caso contrário, atribui null
        $email = $postfields['email'] ?? null; // Atribui o valor do campo 'age' se existir, caso contrário, atribui null
        $senha = sha1($postfields['senha']) ?? null;

        if (empty($id)) {
            http_response_code(400);
            throw new Exception("ID do usuario não foi informado!");
        }

        //verifica campos obrigatórios
        if (empty($nome) || empty($email) || empty($senha)) {

            http_response_code(400);
            
            throw new Exception("Nome ,E-mail e Senha são campos obrigatórios!");
        }

        $sql = "
    UPDATE usuarios SET
    nome = :nome,
    email = :email,
    senha = :senha
    WHERE id_usuario = :id
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
    

        $stmt->execute();

        $result = array(
            'status' => 'sucess',
            'message' => 'Usuário alterado com sucesso!'
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
