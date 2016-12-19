<?php

namespace Kirasin\AccessControll;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Осуществляет контроль доступа к закрытой части приложения
 */
class User
{
	/**
	 * @var string $user_model - Название класса модели, которая используется для работы с БД
	 */
	private $user_model;

	/**
	 * @var object $session - екземпляр обьекта сессии.
	 */
	private $session;

	public function __construct()
	{
		$this->user_model = Kirasin::$app->config['user_model_class'];
		$this->session = Kirasin::$app->session;
	}

	/**
	 * Логинит юзера в систему
	 * @param int $id - ID ползователя в БД
	 * Проверяет: залогинен ли, существует ли такой юзер в БД
	 * Далее создает идентификатор сессии, который помещается в БД и в хранилище $_SESSION
	 * @return bool true|false При успехе или ошибке соответственно
	 */
	public function login(int $id)
	{
		if($this->isLoggedIn()) return false;
		$user = $this->user_model::find()->one($id);
		if(!$user) return false;
		$session_id = str_shuffle(sha1('adkhj23487(*&$sdfhj' . time() . session_id()));
		$user->session_id = md5($session_id);
		if(!$user->save()) return false;
		if(!$this->session->set('user', (object)[
			'session_id' => $session_id,
			'user_id' => $id,
		])) return false;
		return true;
	}

	/** 
	 * Возвращает БД идентификатор залогиненого юзера
	 * @return int | false
	 */
	public function getId()
	{
		if(!$this->session->has('user')) return false;
		return $this->session->get('user')->user_id ?? false;
	}

	/** 
	 * Возвращает идентификатор сессии залогиненого юзера
	 * @return str | false
	 */
	public function getSessionId()
	{
		if(!$this->session->has('user')) return false;
		return $this->session->get('user')->session_id ?? false;
	}

	/**
	 * Логаутит текущего юзера из системы
	 * Проверяет: есть ли юзер в хранилище $_SESSION
	 * Далее сбрасывает идентификатор сессии в БД и в $_SESSION
	 * @return bool true|false При успехе или ошибке соответственно
	 */
	public function logout()
	{
		if(!$this->session->has('user')) return false;
		$user = $this->user_model::find()->one($this->session->get('user')->user_id);
		if($user) $user->session_id = null;
		if($user) $user->save();
		if(!$this->session->remove('user')) return false;
		return true;
	}

	/**
	 * Проверяет: есть ли юзер в хранилище $_SESSION, и в БД
	 * Далее сверяет идентификатор сессии из БД и $_SESSION
	 * @return bool true|false При успехе или ошибке соответственно
	 */
	public function isLoggedIn()
	{
		if(!$this->session->has('user')) return false;
		$session_user = $this->session->get('user');
		$db_user = $this->user_model::find()->one($session_user->user_id);
		if(!$db_user) return false;
		if($db_user->session_id == md5($session_user->session_id)) return true;
	}

}