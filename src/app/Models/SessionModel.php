<?php namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    private function generateNew($name, $table, $prefix, $len)
    {
        $db = db_connect();
        $all_chars = "abcdef01234567890";
        do {
            $tmp = "";
            for ($i = 0; $i < $len; $i++)
                $tmp = $tmp . "0";
            for ($i = 0; $i < $len; $i++)
                $tmp[$i] = $all_chars[random_int(0, strlen($all_chars) - 1)];
            $rand = $prefix . $tmp;
        } while (sizeof($db->query(
            "SELECT id_pk FROM " . $table . " WHERE " . $name . " = ?",
            array($rand))->getResultArray()) > 0);
        $db->close();
        return $rand;
    }

    public function create_session($userid)
    {
        $db = db_connect();
        $session = $this->generateNew('c_session', 't_sessions', '', 24);
        $flag = $this->generateNew('c_flag', 't_sessions', '', 36);
        $db->query("DELETE FROM t_sessions WHERE users_id_fk=?", [$userid]);
        $db->query("
            INSERT INTO t_sessions (
                users_id_fk,
                c_flag,
                c_session,
                c_created_at
            ) VALUES (
                ?, ?, ?, ?
            )
        ", [$userid, $flag, $session, time()]);
        $db->close();

        return array(
            'token' => $session,
            'key' => $flag
        );
    }


    public function is_valid($token)
    {
        $db = db_connect();
        $created = $db->query("
            SELECT c_created_at FROM t_sessions WHERE c_session=?",[$token]
        )->getResultArray();
        if (sizeof($created) === 0) return false;
        return (time() - $created[0]['c_created_at']) < 300;
    }

    public function logout_user($data)
    {
        if (isset($data['token'])) {
            $token = $data['token'];
            if ($this->is_valid($token)) {
                $db = db_connect();
                $db->query("DELETE FROM t_sessions WHERE c_session=?", [$token]);
                $db->close();
                return array(
                    'status' => 'Success',
                    'message' => 'Logged out successfully!',
                    'timestamp' => time()
                );
            }
            else {
                return array(
                    'status' => 'Success',
                    'message' => 'Session is invalid or expired!',
                    'timestamp' => time()
                ); 
            }
        }
        else {
            return array(
                'status' => 'Failure',
                'message' => 'token is missing',
                'timestamp' => time()
            );
        }
    }

    public function verify_key($data)
    {
        if (isset($data['token']) && isset($data['key'])) {
            $token = $data['token'];
            if ($this->is_valid($token)) {
                $db = db_connect();
                $id = $db->query("
                    SELECT
                        id_pk
                    FROM
                        t_sessions
                    WHERE
                        c_session=? AND c_flag=?
                    ", [$token, $data['key']]
                )->getResultArray();
                $db->close();
                
                if (sizeof($id) === 0) {
                    return array(
                        'status' => 'Failure',
                        'message' => 'Invalid key',
                        'timestamp' => time()
                    );
                }
                else {
                    return array(
                        'status' => 'Success',
                        'message' => 'Your flag is ohpygod{cli3nt_enCrypti0n_byPass3d}',
                        'timestamp' => time()
                    );
                }
            }
            else {
                return array(
                    'status' => 'Failure',
                    'message' => 'Invalid Session',
                    'timestamp' => time()
                );
            }
        }
        else {
            array(
                'status' => 'Failure',
                'message' => 'Parameters missing token/key',
                'timestamp' => time()
            );
        }
    }
}