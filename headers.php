<?php

require_once 'conn.php'; // Inclui o arquivo de conexão com o banco de dados


header('Content-Type: application/json; charset=utf-8'); // Define o tipo de conteudo como JSON

// header('Access-Control-Allow-Origin: https://glauco.com'); Permite acesso apenas de um dominio especifico
header('Access-Control-Allow-Origin: *'); //Permite acesso de qualquer dominio
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Permite os metodos GET, POST, PUT e DELETE
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Permite cabeçalhos especificos
header('Access-Control-Allow-Credentials: true'); // Permite credenciais (cookies, autenticação HTTP, etc.)
// var_dump($_SERVER); // Exibe informações sobre os cabeçalhos, caminhos e locais de script   

//define uma constante chamada method com o valor do método HTTP da requisição
// Isso é útil para identificar o tipo de requisição que está sendo feita (GET, POST, etc.)
define('method', $_SERVER['REQUEST_METHOD']); // Captura o método HTTP da requisição


