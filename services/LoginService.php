<?php

namespace Services;

use Kirasin\Kirasin;
use Models\Users;

/**
 * 
 */
class LoginService
{
	private $validator;

	private $user_id = null;
	private $auth_data;

	private $is_valid;

	public function __construct($form_data)
	{
		$this->validator = Kirasin::$app->validator;
		$this->is_valid = $this->validator->validate($this->rules(), $form_data);
		if ($this->is_valid) $this->auth_data = (object)$form_data;
	}

	private function rules()
	{
		return [
			'login' => ['required', 'login', 'min' => 3, 'max' => 15, $this->validator->custom($this, 'validateLogin')],
			'password' => ['required', 'password', 'min' => 3, 'max' => 15, $this->validator->custom($this, 'validatePassword')],
		];
	}

	public function validateLogin($login, $validator)
	{
		$user = Users::find()->where('login', $login)->one();
		if (!$user) return $validator->addError("Такого логина не существует");
		$this->user_id = $user->id;
		return true;
	}

	public function validatePassword($password, $validator)
	{
		if (!$this->user_id) return $validator->addError("");
		$user = Users::find()->one($this->user_id);
		if (md5($password . $user->salt) != $user->password) return $validator->addError("Неверный пароль");
		return true;
	}	

	public function __get($name)
	{
		return $this->auth_data->$name ?? null;
	}

	public function isValid()
	{
		return $this->is_valid;
	}

}