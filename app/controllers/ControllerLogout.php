<?php

namespace app\controllers;


class ControllerLogout extends Base
{
    public function logout($request, $response)
    {
        try {
            session_destroy();
        } catch (\Exception $e) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
        return $response->withHeader('Location', HOME . '/login')->withStatus(302);
    }
}
