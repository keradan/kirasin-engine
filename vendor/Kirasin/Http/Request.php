<?php

namespace Kirasin\Http;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Класс который принимает все данные запроса, и предоставляет к ним удобный и безопасный доступ
 */
class Request
{
	/**
	 * @var string $root - Путь к корню приложения
	 */
	public $root;

	/**
	 * @var string $public_dir - путь к директории public_html
	 */
	public $public_dir;

	/**
	 * @var string $protocol - http или https
	 */
	public $protocol;

	/**
	 * @var string $url - домен сайта
	 */
	public $url;

	/**
	 * @var string $request_uri - все что в адрессе после домена
	 */
	public $request_uri;

	/**
	 * @var string $method - метод запроса (GET или POST)
	 */
	public $method;

	/**
	 * @var string $form_params - данные из POST запроса и файлы переданные методом POST
	 */
	public $form_params;

	/**
	 * @var array $url_params - массив который содержит параметры урл запроса вместо данных пришедших стандартным способом (метод GET)
	 */
	public $url_params = [];

	/**
	 * При создании устанавливаются свойства и проверяется CSRF
	 */
	public function __construct()
	{
		session_start();
		if (!$this->csrfVerify()) die('CSRF ERROR!');

		$this->root = dirname($_SERVER['SCRIPT_FILENAME'], 2);
		$this->public_dir = $this->root . '/' . 'public_html';
		$this->protocol = (!empty($_SERVER['HTTPS']))? 'https://' : 'http://';
		$this->url = substr(str_replace('index.php', null, $this->protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']), 0, -1);
		$this->request_uri = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		
		$this->form_params = $this->setFormParams($_POST, $_FILES);
	}

	/**
	 * Проверка CSRF
	 * сверяются CSRF токены из массива POST и SESSION
	 * если проверка прошла токены удаляются - они одноразовые
	 * @return bool true|false
	 */
	private function csrfVerify()
	{
		if (empty($_POST)) return true;
		if (empty($_SESSION)) return false;
		if (!isset($_POST['csrf_token']) || empty($_POST['csrf_token'])) return false;
		if (!isset($_SESSION['csrf_token_' . session_id()])) return false;
		if ($_POST['csrf_token'] != $_SESSION['csrf_token_' . session_id()]) return false;
		unset($_SESSION['csrf_token_' . session_id()], $_POST['csrf_token']);
		return true;
	}

	/**
	 * Задаются параметры урл запроса
	 * @param string $name - ключ
	 * @param string $value - значение
	 */
	public function setUrlParam($name, $value)
	{
		$this->url_params[$name] = $value;
	}

	/**
	 * Возвращает указанный параметр урл запроса
	 * @param string $name - ключ
	 */
	public function getUrlParam($name)
	{
		return (isset($this->url_params[$name]))? $this->url_params[$name] : null;
	}

	/**
	 * Задаются данные пришедшие из формы
	 * @param array $post - массив данных из метода POST
	 * @param array $files - массив файлов из метода POST
	 * @return array|null - возвращаем либо массив данных либо ничего
	 */
	public function setFormParams($post, $files)
	{
		if ($files && !$files['img']['tmp_name'] && $files['img']['error'] == 4) unset($files['img']);
		$form_params = ($post)? $post : [];
		if ($files) foreach ($files as $f_name => $f_data) $form_params[$f_name] = $f_data;
		unset($_GET, $_POST, $_FILES, $form_params['csrf_token']);
		return (!empty($form_params))? $form_params : null;
	}

	/**
	 * Возвращает массив данных пришедших из формы
	 * @return array|null - если нету тогда null
	 */
	public function getFormParams()
	{
		return $this->form_params ?? null;
	}

}