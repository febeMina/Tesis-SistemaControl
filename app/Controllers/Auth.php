<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function __construct()
    {
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }   
    }
    
    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {

        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $maxIntento = 3; // numero de intentos permitido
        $Intento = 0;

        // Validar que se hayan enviado tanto el usuario como la contraseña
        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Por favor ingresa el usuario y la contraseña');
        }

        $user = $userModel->where('usuario', $username)->first();

        if ($this->userModel->UsuarioBloqueado($username)) {
            return redirect()->back()->withInput()('error', 'Este usuario está bloqueado.');
        }

       
       

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user !== null && isset($user['clave']) && is_string($password) && password_verify($password, $user['clave'])) {
            // Usuario autenticado, guardar datos en sesión o redirigir a otra página
            return redirect()->to('');
        } else {
            // Usuario no autenticado, mostrar mensaje de error o redirigir al formulario de inicio de sesión
            return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
           
           $Intento= $this->$Intento +1;

            if($this->$Intento ==  $this->$maxIntento ){
                $this->userModel->setEstadoInactivo($user['idUsuarios']);
                return redirect()->back()->withInput()->with('error', 'Usuario Bloqueado');
            }
        }
    }
    
    public function logout()
    {
        // Aquí se manejará la lógica de cierre de sesión
    }
}
