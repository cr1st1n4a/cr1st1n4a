<?php

#Cria uma variável de sessão com o nome.
$_SESSION['nome'] = 'veli el brabo';
#Cria uma uma variável com a idade
$_SESSION['idade'] = 29;



#Esta constante tem o seguiunte valor: string(8) "/var/www"
define('ROOT', dirname(__FILE__, 3));
#Esta constante tem o seguiunte valor: string(18) "/var/www/app/views"
define('DIR_VIEW', ROOT . '/app/views');
define('EXT_VIEWS', '.html');
define('HOME', 'http://localhost');