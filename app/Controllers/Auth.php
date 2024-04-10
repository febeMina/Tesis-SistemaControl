<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        if ($username && $password) {
            $user = $userModel->where('usuario', $username)->first();
    
            if ($user !== null && isset($user['clave']) && is_string($password) && password_verify($password, $user['clave'])) {
                // Usuario autenticado, guardar datos en sesión o redirigir a otra página
                return redirect()->to('');
            } else {
                // Usuario no autenticado, mostrar mensaje de error o redirigir al formulario de inicio de sesión
                return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
            }
        } else {
            // Datos de usuario no enviados correctamente, redirigir al formulario de inicio de sesión
            return redirect()->back()->withInput()->with('error', 'Por favor ingresa el usuario y la contraseña');
        }
    }
    
    public function logout()
    {
        // Aquí se manejará la lógica de cierre de sesión
    }
}
