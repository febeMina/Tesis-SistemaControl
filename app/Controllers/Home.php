<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    
    }
    public function index(): string
    {
        return view('welcome_message');
    }
}
