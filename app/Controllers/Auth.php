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
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user !== null && isset($user['password']) && is_string($password) && password_verify($password, $user['password'])) {
            // Usuario autenticado, guardar datos en sesión o redirigir a otra página
            return redirect()->to('ruta/a/otra/pagina');
        } else {
            // Usuario no autenticado, mostrar mensaje de error o redirigir al formulario de inicio de sesión
            return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
        }
    }

    public function logout()
    {
        // Aquí se manejará la lógica de cierre de sesión
    }
}
