<?php

//SINTAXE SQL PARA BUSCAR TODOS OS CLIENTES
// $sql = "SELECT * FROM clientes";


try {

    if (isset($_GET["id"]) && is_numeric($_GET["id"])) { // Verifica se o parâmetro 'id' está definido e é numérico
        $id = $_GET["id"]; // Atribui o valor do parâmetro 'id' à variável $id

        $sql = "
         SELECT *
         FROM clientes
         WHERE id_cliente = :id
        ";

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Vincula o parâmetro ':id' à variável $id

    } elseif (isset($_GET["nome"]) && is_string($_GET["nome"])) { // Verifica se o parâmetro 'nome' está definido na URL
        $nome = $_GET["nome"]; // Atribui o valor do parâmetro 'nome' à variável $nome

        $sql = "
         SELECT *
         FROM clientes
         WHERE nome LIKE :nome
         ORDER BY nome
        ";

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL

        $stmt->bindValue(':nome', '%' . $nome . '%', PDO::PARAM_STR); // Vincula o parâmetro ':name' à variável $name com o caractere '%' para busca parcial

    } elseif (isset($_GET["cidade"]) && is_string($_GET["cidade"])) { // Verifica se o parâmetro 'cidade' está definido na URL
        $cidade = $_GET["cidade"]; // Atribui o valor do parametro 'cidade' à variável $cidade

        $sql = "
         SELECT *
         FROM clientes
         WHERE cidade LIKE :cidade
         ORDER BY nome
        ";

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL

        $stmt->bindValue(':cidade', '%' . $cidade . '%', PDO::PARAM_STR); // Vincula o parâmetro ':name' à variável $name com o caractere '%' para busca parcial

    } else {

        $sql = "
         SELECT *
         FROM clientes
         ORDER BY nome
    "; // Define a consulta SQL para selecionar todos os clientes

        $stmt = $conn->prepare($sql); // Prepara a consulta SQL

    }


    $stmt->execute(); // Executa a consulta SQL
    $data = $stmt->fetchAll(PDO::FETCH_OBJ); // Busca todos os resultados e armazena em um array de objetos

    if (empty($data)) { // Verifica se o array de dados está vazio

        http_response_code(204); // Define o código de resposta HTTP como 204 (sem conteúdo)

    } else {
        foreach ($data as $key => $cliente) { // Percorre cada cliente no array de dados
            $data[$key]->endereco = array(
                'logradouro' => $cliente->logradouro,
                'numero' => $cliente->numero,
                'complemento' => $cliente->complemento,
                'bairro' => $cliente->bairro,
                'cidade' => $cliente->cidade,
                'estado' => $cliente->estado,
                'cep' => $cliente->cep
            ); // Cria um novo objeto 'endereco' com os dados do cliente

            unset($data[$key]->logradouro);
            unset($data[$key]->numero);
            unset($data[$key]->complemento);
            unset($data[$key]->bairro);
            unset($data[$key]->cidade);
            unset($data[$key]->estado);
            unset($data[$key]->cep);
        }

        // Se os resultados não estiverem vazios, retorna os dados
        $result = array(
            'status' => 'success', // Indica que a operação foi bem-sucedida
            'message' => 'Data found', // Mensagem de sucesso
            'data' => $data // Dados dos clientes encontrados
        );
    }
} catch (Exception $e) {
    $result = array(
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage(), // Mensagem de erro em caso de falha na execução da consulta
    );
} finally {
    echo json_encode($result); // Retorna os dados em formato JSON
    $conn = null; // Fecha a conexão com o banco de dados
}
exit; // Encerra o script

// //Verifica se o id foi passado na url e se é um número
// if (isset($_GET['id']) && is_numeric($_GET['id'])) { // Verifica se o parâmetro 'id' está definido e é numérico
//     $id = $_GET['id']; // Atribui o valor do parâmetro 'id' à variável $id

//     //busca o cliente com o id específico
//     $found = false; // Inicializa a variável $found como falsa, indicando que o cliente não foi encontrado ainda
//     foreach ($data as $cliente) { // Percorre cada cliente no array de dados
//         if ($cliente->id == $id) { // Verifica se o ID do cliente atual é igual ao ID fornecido na URL
//             $data = array($cliente); // Retorna o cliente específico
//             $found = true; // Marca que o cliente foi encontrado
//             break; // Para evitar continuar o loop após encontrar o cliente
//         }
//     }

//     // $data = $found ? $data : null; Se o cliente foi encontrado, mantém os dados, caso contrário, define como nulo
//     if (!$found) { // Se o cliente não foi encontrado, define $data como nulo
//         http_response_code(204); // Define o código de resposta HTTP como 204 (sem conteúdo)
//         exit; // Retorna uma resposta vazia
//     } // Encerra a execução do script, retornando uma resposta vazia
// }

// if (isset($_GET['name']) && is_string($_GET['name'])) { // Verifica se o parâmetro 'nome' está definido na URL
//     $name = $_GET['name']; // Atribui o valor do parâmetro 'nome' à variável $nome
//     $result = array(); // Inicializa um array vazio para armazenar os resultados da busca

//     //BUSCAR CLIENTE COM O ID DA URL

//     $found = false; // Inicializa a variável $found como falsa, indicando que o cliente não foi encontrado ainda
//     foreach ($data as $cliente) { // Percorre cada cliente no array de dados
//         if (stripos($cliente->name, $name) !== false) { // Verifica se o nome do cliente atual contém a palavra fornecida na URL
//             $result[] = $cliente; // Adiciona o cliente ao array de resultados
//             $found = true; // Marca que o cliente foi encontrado

//         }
//     }

//     if (!$found) { // Se o cliente não foi encontrado, define $data como nulo
//         http_response_code(204); // Define o código de resposta HTTP como 204 (sem conteúdo)
//         exit; // Retorna uma resposta vazia
//     } else { // Se o cliente foi encontrado, atualiza $data com os resultados
//         $data = $result; // Atualiza $data com os resultados encontrados
//     }
// }

// echo json_encode( // Retorna os dados em formato JSON
//     array(
//         'status' => 'error',
//         'message' => 'Get method called',
//         'data' => $data
//     ) // Mensagem de erro indicando que o método GET não está implementado,
// );
