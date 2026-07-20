<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdministrateurModel;

class AuthAdminController extends BaseController
{
    protected AdministrateurModel $administrateurModel;

    public function __construct()
    {
        $this->administrateurModel = new AdministrateurModel();
    }

    public function index()
    {
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/auth/login');
    }

    public function login()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $admin = $this->administrateurModel->verifierIdentifiants($username, $password);
        if (! $admin) {
            return redirect()->back()->withInput()->with('error', 'Identifiants invalides.');
        }

        session()->set([
            'admin_id'        => $admin['id'],
            'admin_username'  => $admin['username'],
            'isAdminLoggedIn' => true,
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('message', 'Déconnexion effectuée.');
    }
}

