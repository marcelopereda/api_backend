<?php
// Database connection settings

define('DB_HOST', 'localhost'); // Define o host do banco de dados
define('DB_USER', 'root'); // Define o usuário do banco de dados
define('DB_PASS', ''); // Define a senha do banco de dados  
define('DB_NAME', 'db_backend'); // Define o nome do banco de dados


try {

    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS); // Cria uma nova conexão com o banco de dados MySQL usando PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Define o modo de erro para exceções
    // echo "Connected successfully"; Exibe mensagem de sucesso na conexão

} catch (Exception $e) {

    echo "Connection failed: " . $e->getMessage(); // Exibe mensagem de erro e encerra o script
    exit; // Encerra o script
}
