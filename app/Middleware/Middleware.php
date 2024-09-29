<?php

namespace app\Middleware;

class Middleware
{
    public static function route()
    {
        return function ($request, $handler) {
            // Inicia a sessão se ainda não estiver iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $response = $handler->handle($request);
            $method = $request->getMethod();
            $pagina = $request->getRequestTarget();

            // Verificação para redirecionar usuários não logados
            if ($method === 'GET') {
                // Se não estiver logado, redirecione para login
                if (empty($_SESSION['login']) || !isset($_SESSION['login']['logado']) || !boolval($_SESSION['login']['logado'])) {
                    // Evitar redirecionar para /login se já estiver nessa página
                    if ($pagina !== '/login') {
                        return $response
                             ->withHeader('Location', HOME . '/login')
                             ->withStatus(302);
                    }
                }

                // Redireciona usuários logados que tentam acessar a página de login
                if ($pagina === '/login' && isset($_SESSION['login']) && boolval($_SESSION['login']['logado'])) {
                    return $response->withHeader('Location', HOME . '/')->withStatus(302);
                }
            }

            return $response;
        };
    }
}