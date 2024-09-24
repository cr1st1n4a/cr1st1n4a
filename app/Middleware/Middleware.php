<?php

namespace app\middleware;

class Middleware
{
    public static function route()
    {
        $middleware = function ($request, $handler) {
            #Executa o manipulador de requisição para obter a resposta
            $response = $handler->handle($request);
            #CAPTURAMOS O METODOS DE REQUISIÇÃO.
            $method = $request->getMethod();
            #CAPTURAMOS A PAGINA SOLICITADA PELO USUÁRIO
            $pagina = $request->getRequestTarget();
            #CASO USUÁRIO NÃO ESTEJA LOGADO, DIRECIONAMOS PARA AUTENTICAÇÃO
            if ($method == 'GET') {
                if (
                    (empty($_SESSION['usuario']) or
                        !boolval($_SESSION['usuario']['logado'])) and
                    ($pagina !== '/login')
                ) {
                    session_destroy();
                    return $response
                        ->withHeader('Location', HOME . '/login')
                        ->withStatus(302);
                    die();
                }
                if ($pagina == '/login') {
                    #Caso o usuário esteja logado, redirecionamos para a página inicial
                    if (
                        isset($_SESSION['usuario']) and
                        boolval($_SESSION['usuario']['logado'])
                    ) {
                        return $response->withHeader('Location', HOME)->withStatus(302);
                        die();
                    }
                }
            }
            return $response;
            die();
        };
        return $middleware;
    }
}