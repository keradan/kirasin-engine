<?php

namespace Kirasin\Controller;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Абстрактный клас от которого наслудуються все контроллеры приложения
 */
abstract class Controller
{
	/**
	 * @var object $urlManager - генератор ссылок
	 */
	protected $urlManager;

	/**
	 * @var object $response - объект который собирает в себя весь вывод приложения
	 */
	protected $response;

	/**
	 * @var object $request - объект который собирает данные запроса в себя. Через него осуществляется доступ к данным запроса.
	 */
	protected $request;

	/**
	 * @var object $viewer - объект шаблонизатор
	 */
	private $viewer;

	/**
	 * @var string $layout_name - имя html шаблона текущего контроллера
	 */
	private $layout_name;

	/**
	 * @var string $view_dir - путь к файлам представления
	 */
	private $view_dir;

	/**
	 * @var string $controller_name - имя текущего контроллера
	 */
	private $controller_name;

	/**
	 * При создании станавливаются свойства
	 */
	public function __construct()
	{
		$this->response = Kirasin::$app->response;
		$this->request = Kirasin::$app->request;
		$this->viewer = Kirasin::$app->viewer;
		$this->urlManager = Kirasin::$app->urlManager;

		$controller_path = explode('\\', get_class($this));
		$this->controller_name = str_replace('Controller', null, lcfirst(array_pop($controller_path)));

		$this->layout_name = $this->controller_name;
		$this->view_dir = $this->controller_name;
	}

	/**
	 * Метод который вызывает шаблонизатор и собирает на основе файлов представления и данных Html ответ для объекта $response
	 * @param string $view_name - имя представления выполняемого екшена
	 * @param array $data - массив данных, которые выводятся в файле представления
	 */
	protected function render(string $view_name, array $data = [])
	{
		$this->response->setHtmlResponse($this->viewer
			->buildView($this->view_dir, $view_name, $data)
			->buildLayout($this->layout_name)
			->setCsrf($this->response->generateCsrfToken())
			->getHtml()
		);
	}

	/**
	 * Метод для кастомной установки имени HTML шаблона
	 */
	protected function setLayoutName($layout_name)
	{
		$this->layout_name = $layout_name;
	}

	/**
	 * Метод для кастомной установки пути к представлению екшена (вместо установленного по умолчанию пути, на основе имени контроллера)
	 */
	protected function setViewDir($view_dir)
	{
		$this->view_dir = $view_dir;
	}

	/**
	 * @deprecated На данный момент не используется, и вряд-ли будет
	 * Метод beforeAction() используется для того же самого
	 */
	public function prepareLayout()
	{
		return $this;
	}

	/**
	 * Метод вызывется роутером перед вызовом екшена.
	 * Сюда можно добавлять логику, проверки и т.д. которые будут выполнены контроллером в любом случае
	 * @return object $this - возвращает себя же (объект текущего контроллера)
	 */
	public function beforeAction()
	{
		return $this;
	}

	/**
	 * Екшен который по умолчанию вызывается если роутер не нашел соответствий между текущим
	 * адресом запроса, и зарегистрированными в приложении роутами.
	 * Так же его можна вызвать из любого другого екшена для того чтобы выдать ошибку 404.
	 */
	public function actionNotFound()
	{
		$this->setLayoutName('main');
		$this->setViewDir('');
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		Kirasin::$app->flash->addEvent(false, 'Ошибка 404', 'Страница не найдена(');
		$this->render('message', [
			'event' => Kirasin::$app->flash->getEvent(),
		]);
	}

	/**
	 * Метод который добавляет сообщение в Флеш (в сессию) и вызывает редирект по указанному пути
	 * @param bool $success - значение успешности выполненной ранее операции
	 * @param string $headline - Заголовок сообщения
	 * @param string $message - текст сообщения
	 * @param string $route - маршрут редиректа
	 */
	public function addEventAndRedirect(bool $success , string $headline, string $message, string $route)
	{
		Kirasin::$app->flash->addEvent($success, $headline, $message);
		$this->redirect($route, '#message');
	}

	/**
	 * Метод делает переадресацию на другую страницу сайта
	 * @param string $route - страница на которую произойдет переадресация
	 * @param string|null $scroll - id елемента страницы, до которой нужно отскроллить
	 */
	public function redirect($route, $scroll = null)
	{
		header("Location: " . Kirasin::$app->urlManager->generateLink($route, $scroll));
		die();
	}

}