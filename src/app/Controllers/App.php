<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SessionModel;

class App extends Controller
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login()
    {
        $data['title'] = ucfirst("login");
        $data['appname'] = 'Uncryptable Level 1';

        echo view('templates/header', $data);
        echo view('pages/login', $data);
        echo view('templates/footer', $data);
    }

    public function signup()
    {
        $data['title'] = ucfirst("register");
        $data['appname'] = 'Uncryptable Level 1';

        echo view('templates/header', $data);
        echo view('pages/register', $data);
        echo view('templates/footer', $data);
    }

    public function dashboard()
    {
        helper('App\Helpers\Crypt');
        $data['title'] = ucfirst("dashboard");
        $data['appname'] = 'Uncryptable Level 1';

        $request = service('request');
        $post = (array) $request->getJSON();
        // GET Request
        if (!isset($post['payload'])) {
            echo view('templates/header', $data);
            echo view('pages/loading', $data);
            echo view('templates/footer', $data);
            return;
        }
        $arr = decrypt($post['payload']);

        $session = new SessionModel();
        if (!$session->is_valid($arr['token'])) {
            $this->login();
            return;
        }
        
        if (isset($arr['is_admin'])) {
            if ($arr['is_admin'] == true) {
                $data['is_admin'] = true;
            }
            else {
                $data['is_admin'] = false;
            }
        }
        else {
            $data['is_admin'] = false;
        }
        
        echo view('templates/header', $data);
        echo view('pages/dashboard', $data);
        echo view('templates/footer', $data);
    }
}