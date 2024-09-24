<?php
#Cria e inicializar uma sessão no servidor.
session_start();
#Cria uma variável de sessão com o nome.
$_SESSION['nome'] = 'Eu Sou Groot';
#Cria uma uma variável com a idade
$_SESSION['idade'] = 250;
#Esta constante tem o seguiunte valor: string(8) "/var/www"
define('ROOT', dirname(__FILE__, 3));
#Esta constante tem o seguiunte valor: string(18) "/var/www/app/views"
define('DIR_VIEW', ROOT . '/app/views');
define('EXT_VIEWS', '.html');
define('HOME', 'http://localhost');