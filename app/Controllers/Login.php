<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;


class Login extends BaseController
{

   
 
    public function __construct()
    {
        helper('url');
        if (session()->get('isLoggedIn')) {
            $currentMethod = service('request')->getUri()->getSegment(2, 'index'); // Obtiene el nombre del método actual
            if ($currentMethod !== null && $currentMethod !== 'logout' && session()->get('isLoggedIn')) {
                redirect()->to(base_url('public/home'))->send();
                exit;
            }
        }
    }
    public function index()
    {
        return view('login/form');
    }

    public function signIn()
    {
        $maxIntento = 3;
     
        $userModel = new UserModel(); 
        $jsonUser = $this->request->getJSON();
        $response = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        
     
        $user = $jsonUser->username;
        $password = $jsonUser->password;
    
        if (empty($user) || empty($password)) {
            $response['message'] = 'Por favor ingresa el usuario y la contraseña';

        } else {

            $username = $userModel->where('usuario', $user)->first();
             
            if (empty($username)) {
                $response['message'] = 'El usuario no existe.';
                return $this->response->setJSON($response);
            }
                // Check if user is inactive
                if ($username['estado'] === 'Inactivo') {
                    $response['message'] = 'El usuario está inactivo. Por favor, contacta al administrador.';
                    return $this->response->setJSON($response);
                }
                
                if ($username['estado'] === 'Eliminado') {
                    $response['message'] = 'El usuario ha sido eliminado.';
                    return $this->response->setJSON($response);
                } 
           
            if ($username !== null && isset($username['clave']) && isset($username['usuario'])) {
                // Verificar la contraseña encriptada

                if (password_verify($password, $username['clave'])) {
                    $response['status'] = true;
                    $response['message'] = 'Sesión iniciada correctamente';
    
                    // Obtener información del usuario
                    $userInfo = $userModel
                        ->select('usuarios.idUsuarios, rol.nombreRol')
                        ->join('rol', 'rol.idRol = usuarios.idRol', 'inner')
                        ->where('usuario', $user)
                        ->first();
    
                    // Crear sesión de usuario 
                    $session = session();
                    $userData = [
                        'usuario' => $username['usuario'],
                        'id' => $userInfo['idUsuarios'],
                        'rol' => $userInfo['nombreRol'],
                        'isLoggedIn' => true
                    ];
    
                    $session->set($userData);
                    $session->remove('loginAttempts'); // Reset attempts on success

                } else {
                    
                                        // Increment attempt count
                            $session = session();
                            $attempts = $session->get('loginAttempts') ?? 0;
                            $attempts++;
                            $session->set('loginAttempts', $attempts);

                            $response['message'] = 'Usuario o contraseña incorrecta. Intento ' . $attempts;

                            if ($attempts >= $maxIntento) {
                                // Optionally, you can block the user here
                                // $userModel->setEstadoInactivo($username['idUsuarios']);
                                $userModel->CambioEstadoInactivo($username['idUsuarios']);
                                
                                $response['message'] = 'Usuario Bloqueado';
                                return $this->response->setJSON($response);
                            }

                }
            } else {
                     
                 // Increment attempt count
                
                      // Increment attempt count
                        $session = session();
                        $attempts = $session->get('loginAttempts') ?? 0;
                        $attempts++;
                        $session->set('loginAttempts', $attempts);

                        $response['message'] = 'Usuario o contraseña incorrecta. Intento ' . $attempts;

                        if ($attempts >= $maxIntento) {
                            $response['message'] = 'Usuario fue bloqueado';
                            return $this->response->setJSON($response);
                        }
               
            }
        }
    
        return $this->response->setJSON($response);
    }
    
    public function beforeRequest()
    {
        $session = session();

         // Comprobar si la sesión está configurada y si existe la última actividad
        if ($session->has('lastActivity')) {
            
              $lastActivity = $session->get('lastActivity');
           
           $currentTime = time();
    
         //Comprueba si la última actividad fue hace más de 5 minutos
            
            if (($currentTime - $lastActivity) > 100) { // 300 seconds = 5 minutes
                        
                    $session->destroy();
                    return $this->response->setJSON(['status' => false,'message' => 'Sesión cerrada por inactividad']);
                                                      }
                                                    }
                    // Update last activity time
                    $session->set('lastActivity', time());
    }
    
    
   


    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON([
            'status' => true,
            'message' => 'Sesion cerrada correctamente'
        ]);
    }

    public function someAction()
    {
        
        $this->beforeRequest();
      
    }

}
