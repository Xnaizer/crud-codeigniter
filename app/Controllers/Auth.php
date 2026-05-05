<?php namespace App\Controllers;

use App\Models\UserModel;
use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController {
    use ResponseTrait;

    public function login() {
        $model = new UserModel();

        $input = $this->request->getJSON(true);

        $user = $model->where('username', $input['username'])->first();

        if(!$user || !password_verify($input['password'], $user['password'])) {
            return $this->failUnauthorized('Wrong credentials');
        }

        $payload = [
            "iat" => time(),
            "exp" => time() + 86400,
            "uid" => $user['id']
        ];

        $token = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');

        return $this->respond(['token' => $token]);
    }

    public function register() {
        $input = $this->request->getJSON(true);

        $model = new UserModel();

        $model->insert([
            'username' => $input['username'],
            'password' => password_hash($input['password'], PASSWORD_BCRYPT)
        ]);

        return $this->respondCreated(['msg' => 'Register success']);
    }
}