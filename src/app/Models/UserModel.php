<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public function register_user($data)
    {
        if (!isset($data['username'])
            || !isset($data['password'])
            || !isset($data['email'])
            || !isset($data['cpassword'])
            || !isset($data['name'])
        ) {
            return array(
                'status' => 'Failure',
                'message' => 'Request Parameters not set',
                'timestamp' => time()
            );
        }
        $db = db_connect();

        $id = $db->query("
            SELECT
                id_pk
            FROM
                t_users
            WHERE
                c_username=?
        ", [$data['username']])->getResultArray();
        if(sizeof($id) > 0) {
            return array(
                'status' => 'Failure',
                'message' => 'Username exists',
                'timestamp' => time()
            );
        }
        $db->query("
            INSERT INTO t_users(
                c_is_admin,
                c_username,
                c_passwd,
                c_email,
                c_name,
                c_created_at
            ) VALUES (
                '0',
                ?,
                ?,
                ?,
                ?,
                ?
            )
            ", [
                $data['username'],
                hash('sha256', $data['password']),
                $data['email'],
                $data['name'],
                time()
            ]);
        $db->close();
        return array(
            "status" => "Success",
            "message" => "OK",
            "timestamp" => time()
        );
    }

    public function login_user($data)
    {
        $db = db_connect();
        $arr = $db->query("
            SELECT
                id_pk
            FROM
                t_users
            WHERE
                c_username=? AND c_passwd=?
        ", [
            $data['username'],
            hash('sha256', $data['password'])
        ])->getResultArray();
        $db->close();
        
        if (sizeof($arr) === 0) {
            return array(
                'status' => 'Failure',
                'message' => 'Username or Password is incorrect',
                'timestamp' => time()
            );
        }
        else {
            return array(
                'status' => 'Success',
                'message' => 'Logged in Successfully',
                'timestamp' => time(),
                'userid' => $arr[0]['id_pk']
            );
        }
        
    }
}