<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface; // Asegúrate de que este es el correcto
use CodeIgniter\Filters\FilterInterface;

class CocinaFilters implements FilterInterface
{
    
            public function before(RequestInterface $request, $arguments = null)
                {
                    // Aquí obtienes la información del usuario
                    $session = session();
                    
            // Verificas si el usuario está autenticado y es un administrador  
            if (!$session->get('isLoggedIn') ||  $session->get('rol') !== 'Cocina'&& $session->get('rol') !== 'Administrador') {
            // Redirigir o mostrar error si no es Cocina
                          return redirect()->to('/home')->with('error', 'No tienes permisos para acceder a esta sección.');
                    }
                }

        
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Aquí puedes agregar lógica después de que la respuesta se haya enviado
        // Por ejemplo, puedes hacer algún tipo de registro o manipulación
    }
               
    
 }

?>