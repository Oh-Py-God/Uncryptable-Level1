<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\SessionModel;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\OutgoingResponse;

class Api extends Controller
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login()
    {
        helper('App\Helpers\Crypt');
        $request = service('request');
        $post = (array) $request->getJSON();
        $arr = decrypt($post['payload']);
        $user = new UserModel();
        $loginResponse = $user->login_user($arr);
        $this->response->setHeader('Content-Type', 'application/json');
        if ($loginResponse['status'] === 'Success') {
            $session = new SessionModel();
            $res = $session->create_session($loginResponse['userid']);
            unset($loginResponse['userid']);
            $loginResponse['token'] = $res['token'];
            $loginResponse['master key'] =  $res['key'];
            print_r(encrypt($loginResponse));
        }
        else {
            print_r(encrypt($loginResponse));
        }
    }

    public function signup()
    {
        helper('App\Helpers\Crypt');
        $request = service('request');
        $post = (array) $request->getJSON();
        $arr = decrypt($post['payload']);
        $user = new UserModel();
        $this->response->setHeader('Content-Type', 'application/json');
        print_r(encrypt($user->register_user($arr)));
    }

    public function logout()
    {
        helper('App\Helpers\Crypt');
        $request = service('request');
        $post = (array) $request->getJSON();
        $arr = decrypt($post['payload']);
        $session = new SessionModel();
        $logoutResponse = $session->logout_user($arr);
        $this->response->setHeader('Content-Type', 'application/json');
        print_r(encrypt($logoutResponse));
    }

    public function verifyKey()
    {
        helper('App\Helpers\Crypt');
        $request = service('request');
        $post = (array) $request->getJSON();
        $arr = decrypt($post['payload']);
        $session = new SessionModel();
        $verifyKeyResponse = $session->verify_key($arr);
        $this->response->setHeader('Content-Type', 'application/json');
        print_r(encrypt($verifyKeyResponse));
    }
}
