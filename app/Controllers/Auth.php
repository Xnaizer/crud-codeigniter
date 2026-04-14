<?php namespace App\Controllers;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController {
    use ResponseTrait;

    public function login() {
        $model = new UserModel();
        $user = $model->where('username', $this->request->getVar('username'))->first();
        if(!$user || !password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Wrong credentials');
        }
        $payload = [
            "iat" => time(),
            "exp" => time() + (24 * 60 * 60),
            "uid" => $user['id']
        ];
        $token = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');  
        return $this->respond(['token' => $token]);
    }

    public function register() {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
        ];

        if(!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $model = new UserModel();
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];

        if($model->insert($data)) {
            return $this->respondCreated(['msg' => 'User berhasil didaftarkan']);
        }

        return $this->fail('Gagal mendaftarkan user');
    }
}