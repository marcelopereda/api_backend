<?php

// SINTAXE SQL PARA BUSCAR CLIENTES NO BANCO DE DADOS
// $sql = "SELECT * FROM clientes WHERE id = :id";


// ENQUANTO NAO TEMOS BANCO DE DADOS, VAMOS SIMULAR COM UM ARQUIVO JSON
// O arquivo JSON é lido e convertido em um array de objetos PHP
// $data = json_decode(file_get_contents("dados.json"));


try {
    // VERIFICA SE  HÁ UM ID NA URL PARA CONSULTA ESPECIFICA
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "
            SELECT *
            FROM usuarios
            WHERE id_usuario = :id
            ";

        $stmt = $conn->prepare($sql); // PREPARAR A SINTAXE SQL
        // O bindParam() vincula o parâmetro :id à variável $id, que é um inteiro (PDO::PARAM_INT). Isso significa que o valor de $id será usado na consulta SQL quando ela for executada.
        // Isso é útil para evitar injeção de SQL, pois o valor de $id será tratado como um parâmetro e não como parte da consulta SQL.
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // VINCULA O PARAMETRO :ID A VARIAVEL $ID
    } else if (
        isset($_GET['email']) && is_string($_GET['email']) &&
        isset($_GET['senha']) && is_string($_GET['senha'])
    ) {

        $email = trim($_GET['email']);
        $senha = sha1(trim($_GET['senha']));
        $sql = "
            SELECT *
            FROM usuarios
            WHERE email LIKE :email
            AND senha LIKE :senha
            ORDER BY nome
    ";

        $stmt = $conn->prepare($sql); // PREPARAR A SINTAXE SQL


        // O operador LIKE em SQL é usado para buscar padrões em uma coluna de texto.".
        $stmt->bindValue(':email', $email, PDO::PARAM_STR); // VINCULA O PARAMETRO :nome A VARIAVEL $nome
        $stmt->bindValue(':senha', $senha, PDO::PARAM_STR); // VINCULA O PARAMETRO :nome A VARIAVEL $nome
    } else {
        $sql = "
            SELECT *
            FROM usuarios
            ORDER BY nome
    ";


        // stmt - statement
        $stmt = $conn->prepare($sql); // PREPARAR A SINTAXE SQL
    }





    $stmt->execute(); // EXECUTAR A SINTAXE SQL
    $data = $stmt->fetchAll(PDO::FETCH_OBJ); // OBTER OS DADOS EM UM ARRAY DE OBJETOS PHP

    // VERIFICA SE O ARRAY ESTÁ VAZIO
    if (empty($data)) {
        http_response_code(204); // NO CONTENT
    } else {
        $result = array(
            'status' => 'success',
            'message' => 'Dados encontrados',
            'data' => $data
        );
    }
} catch (Exception $e) {
    $result = array(
        'status' => 'error',
        'message' => 'Erro ao buscar os dados: ' . $e->getMessage()
    );
} finally {

    echo json_encode($result); // RETORNA O RESULTADO EM JSON
    $conn = NULL; // FECHA A CONEXÃO COM O BANCO DE DADOS
    exit; // FINALIZA O SCRIPT
}



if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $found = false;
    foreach ($data as $cliente) {
        if ($cliente->id == $id) {
            $data = $cliente;
            $found = true;
            break;
        }
    }

    // $data = $found ? $data : null;
    if (!$found) {
        http_response_code(204);
    }
}

if (isset($_GET['name']) && is_string($_GET['name'])) {
    $name = $_GET['name'];
    $result = array();

    $found = false;
    foreach ($data as $cliente) {
        // stripos() é uma função que faz uma busca sem diferenciar maiúsculas de minúsculas
        // Se o nome do cliente contém a string buscada, adiciona o cliente ao array de resultados
        if (stripos($cliente->name, $name) !== false) {
            $result[] = $cliente;
            $found = true;
        }
    }
    // $data = $found ? $data : null;
    if (!$found) {
        http_response_code(204);
    } else {
        $data = $result;
    }
}


// json_encode() converte o array de objetos PHP em um JSON
echo json_encode(
    array(
        'status' => 'success',
        'message' => 'GET method called',
        'data' => $data,
    )
);
