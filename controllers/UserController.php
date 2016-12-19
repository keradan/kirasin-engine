<?php

namespace Controllers;

use Kirasin\Kirasin;
use Models\Users;
use Services\LoginService;

/**
 * 
 */
class UserController extends \Kirasin\Controller\Controller
{
	public $user;
	public $flash;

	public function beforeAction()
	{
		$this->user = Kirasin::$app->user;
		$this->flash = Kirasin::$app->flash;
		$this->setLayoutName('main');
		return $this;
	}

	public function actionLogin()
	{
		if ($this->user->isLoggedIn()) $this->redirect('/');
		$this->render('login', [
			'form_action' => $this->urlManager->generateFormAction('/login'),
			'event' => $this->flash->getEvent(),
			'validate_errors' => $this->flash->getValidateErrors(),
		]);
	}

	public function actionPostLogin()
	{
		if ($this->user->isLoggedIn()) $this->redirect('/');

		$login_service = new LoginService($this->request->getFormParams());
		if(!$login_service->isValid()) return $this->addEventAndRedirect(false, 'Ошибка!', 'Что-то не в порядке с данными формы', '/login');

		$user = Users::find()->where('login', $login_service->login)->one();
		if (!$user) return $this->addEventAndRedirect(false, 'Ошибка!', 'Такого пользователя нет', '/login');
		if (!$this->user->login($user->id)) return $this->addEventAndRedirect(false, 'Ошибка!', 'Авторизация не удалась(', '/login');
		$this->redirect('/admin');
	}

	public function actionLogout()
	{
		$this->user->logout();
		$this->redirect('/');
	}

}