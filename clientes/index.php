<?php
//IMPORTA O ARQUIVO DE CABEÇALHO QUE CONTÉM
//AS DEFINIÇÕES DE CABEÇALHO E CONFIGURAÇÕES DE ACESSO
require_once '../headers.php';

//verificar o metodo da requisição

if (method == 'GET') {
   include "get.php"; // Inclui o arquivo get.php que contém a função getClientes()  

} else if (method == 'POST') {
   include "post.php"; // Inclui o arquivo post.php que contém a função postClientes()

} else if (method == 'PUT') {
   include "put.php"; // Inclui o arquivo put.php que contém a função putClientes()

} else if (method == 'DELETE') {
   include "delete.php"; // Inclui o arquivo delete.php que contém a função deleteClientes()

} else {
}
