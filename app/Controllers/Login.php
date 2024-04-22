<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Login extends Controller
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
            if ($username !== null && isset($username['clave']) && isset($username['usuario'])) {
                if ($username['usuario'] == $user && $username['clave'] == $password) {
                    $response['status'] = true;
                    $response['message'] = 'Sesion iniciada correctamente';
                    /* $response['data'] = [
                        'usuario' => $username['usuario'],
                        'clave' => $username['clave']
                    ]; */

                    $userInfo = $userModel
                        ->select('rol.nombreRol, docente.nombre_completo')
                        ->join('rol', 'rol.idRol = usuarios.idRol', 'inner')
                        ->join('docente', 'docente.idDocente = usuarios.idDocente', 'inner')
                        ->where('usuario', $user)
                        ->first();

                    //Crear sesion de usuario
                    $session = session();
                    $userData = [
                        'usuario' => $username['usuario'],
                        'rol' => $userInfo['nombreRol'],
                        'docente' => $userInfo['nombre_completo'],
                        'isLoggedIn' => true
                    ];

                    $session->set($userData);
                } else {
                    $response['message'] = 'Credenciales inválidas';
                }
            } else {
                $response['message'] = 'Credenciales inválidas';
            }
        }

        return $this->response->setJSON($response);
    }

    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON([
            'status' => true,
            'message' => 'Sesion cerrada correctamente'
        ]);
    }
}
