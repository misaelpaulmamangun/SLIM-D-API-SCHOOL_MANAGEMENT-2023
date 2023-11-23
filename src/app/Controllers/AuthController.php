<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Enums\HttpStatus;
use App\Interfaces\AuthInterface;
use App\Models\User;
use PDOException;

class AuthController extends Controller implements AuthInterface
{
	public function login(Request $request, Response $response): Response
	{
		try {
			$email = $request->getParam('email');
			$password = $request->getParam('password');

			$user = User::where('email', $email)->first();

			// Checked if user exist in database
			if (empty($user)) {
				return $this->c->ResponseHelper->createErrorResponse('User not found', HttpStatus::NOT_FOUND->value);
			}

			// Verify the hashed password
			if (!password_verify($password, $user->password)) {
				return $this->c->ResponseHelper->createErrorResponse('Incorrect password', HttpStatus::UNAUTHORIZED->value);
			}
			// Remove password from user data
			unset($user['password']);

			$token = [
				'iss' => 'utopian',
				'iat' => time(),
				'exp' => time() + 1000,
				'data' => $user
			];

			$jwt = JWT::encode($token, $this->c->settings['jwt']['key']);

			return $this->c->ResponseHelper->createSuccessResponse(['jwt' => $jwt]);
		} catch (PDOException $e) {
			// Catch all database errors
			return $this->c->ResponseHelper->createErrorResponse($e->getMessage());
		}
	}

	public function register(Request $request, Response $response): Response
	{
		$email = $request->getParam('email');
		$password = $request->getParam('password');
		$confirmPassword = $request->getParam('confirm_password');
		$passwordMinimumLength = 8;

		try {

			// Check if email is valid
			if (!($this->c->AuthHelper->isEmail($email))) {
				return $this->c->ResponseHelper->createErrorResponse('Email is invalid.');
			}

			// Check if user's email exists in the database
			if ($this->isEmailExist($email)) {
				return $this->c->ResponseHelper->createErrorResponse('Email already exists.');
			}

			// Check if password and confirm password match
			if (!$this->c->AuthHelper->confirmPassword($password, $confirmPassword)) {
				return $this->c->ResponseHelper->createErrorResponse('Passwords do not match.');
			}

			// Check if password length is sufficient
			if (!$this->c->AuthHelper->passwordMinimumLength($password, $passwordMinimumLength)) {
				return $this->c->ResponseHelper->createErrorResponse('Password needs at least ' . $passwordMinimumLength . ' characters.');
			}

			$user = new User();
			$user->email = $email;
			$user->password = password_hash($password, PASSWORD_DEFAULT);
			$user->created_at = date('Y-m-d H:i:s');
			$user->save();

			return $this->c->ResponseHelper->createSuccessResponse(null, 'Success');
		} catch (PDOException $e) {
			return $this->c->ResponseHelper->createErrorResponse($response, $e->getMessage());
		}
	}

	private function isEmailExist(string $email): bool
	{
		$userCount = User::where('email', $email)->count();
		return $userCount > 0;
	}
}
