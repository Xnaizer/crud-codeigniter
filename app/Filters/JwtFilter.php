<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        $key = getenv('JWT_SECRET');
        $header = $request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return service('response')->setJSON(['msg' => 'Token Required'])->setStatusCode(401);

        $token = explode(' ', $header)[1];

        try {
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            $request->user = $decode;
        } catch (\Exception $e) {
            return service('response')->setJSON(['msg' => 'Invalid Token'])->setStatusCode(401);
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}