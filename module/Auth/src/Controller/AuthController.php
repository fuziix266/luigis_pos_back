<?php

namespace Auth\Controller;

use Auth\Model\UserTable;
use Auth\Service\JwtService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class AuthController extends AbstractActionController
{
    private UserTable $userTable;
    private JwtService $jwtService;

    public function __construct(UserTable $userTable, JwtService $jwtService)
    {
        $this->userTable = $userTable;
        $this->jwtService = $jwtService;
    }

    public function loginAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isOptions()) {
            return new JsonModel([]);
        }

        if (!$request->isPost()) {
            $this->getResponse()->setStatusCode(405);
            return new JsonModel(['success' => false, 'error' => 'Method not allowed']);
        }

        $body = json_decode($request->getContent(), true);
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['success' => false, 'error' => 'Username y password requeridos']);
        }

        $user = $this->userTable->findByUsername($username);

        if (!$user || !password_verify($password, $user->password_hash)) {
            $this->getResponse()->setStatusCode(401);
            return new JsonModel(['success' => false, 'error' => 'Credenciales inválidas']);
        }

        if (!$user->is_active) {
            $this->getResponse()->setStatusCode(403);
            return new JsonModel(['success' => false, 'error' => 'Usuario desactivado']);
        }

        $token = $this->jwtService->generateToken([
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
        ]);

        return new JsonModel([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user->getArrayCopy(),
            ],
        ]);
    }

    public function meAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isOptions()) {
            return new JsonModel([]);
        }

        $authHeader = $request->getHeader('Authorization');
        if (!$authHeader) {
            $this->getResponse()->setStatusCode(401);
            return new JsonModel(['success' => false, 'error' => 'Token requerido']);
        }

        $token = str_replace('Bearer ', '', $authHeader->getFieldValue());
        $payload = $this->jwtService->validateToken($token);

        if (!$payload) {
            $this->getResponse()->setStatusCode(401);
            return new JsonModel(['success' => false, 'error' => 'Token inválido']);
        }

        $user = $this->userTable->findById($payload->user_id);
        if (!$user) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel(['success' => false, 'error' => 'Usuario no encontrado']);
        }

        return new JsonModel([
            'success' => true,
            'data' => ['user' => $user->getArrayCopy()],
        ]);
    }
}
