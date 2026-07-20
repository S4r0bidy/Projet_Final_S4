<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ClientAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isClientLoggedIn')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter.');
        }

        // Sécurité supplémentaire : si le compte a été bloqué entre-temps
        if ($session->get('client_statut') === 'BLOQUE') {
            $session->destroy();
            return redirect()->to('/client/login')->with('error', 'Votre compte est bloqué.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire ici
    }
}

