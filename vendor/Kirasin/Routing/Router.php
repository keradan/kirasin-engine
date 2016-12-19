<?php

namespace Kirasin\Routing;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Класс маршрутизатор. Регистрирует все доступные ссылки сайта, проверяет текущий роут на соответствие зарегистрированным и т.д.
 */
class Router
{
	/**
	 * @var string $current_route - содержит текущий роут. Строка формата: 'part1/part2/part3'
	 */
	private $current_route;

	/**
	 * @var string $current_method - содержит метод текущего http запроса (того запроса который
	 * привел пользователя на страницу сгенерированную в процессе выполнения данного скрипта)
	 */
	private $current_method;

	/**
	 * @var string $last_route_name - вспомогательное свойство, используется для того чтобы 
	 * было более удобно указать контроллер и екшен при регистрации роута
	 */
	private $last_route_name;

	/**
	 * @var array $registered_routes - все зарегистрированные роуты
	 */
	private $registered_routes = [];

	/**
	 * При создании устанавливаются значения текущего роута и метода
	 */
	public function __construct()
	{
		$request = Kirasin::$app->request;
		$this->current_method = $request->method;
		$this->current_route = $this->getCurrentRoute($request->request_uri, $request->url);
	}

	/**
	 * Вспомогательный метод который проверяет преобразует данные из реквеста в правильную строку маршрута
	 * @param string $request_uri - все что в адрессе после домена
	 * @param string $url - домен сайта
	 * @return string - Корректный роут соответствующий данным реквеста или ведущий на ошибку 404
	 */
	private function getCurrentRoute($request_uri, $url)
	{
		if(strpos($request_uri, 'index.php')) return '/404';
		if(preg_match("/\/\/+/", $request_uri)) return '/404';
		$request_uri = preg_replace("/\/\/+/", '/', str_replace(explode('/', $url), null, $request_uri));
		if($request_uri{strlen($request_uri) - 1} == '/' && strlen($request_uri) != 1) return '/404';
		return $request_uri;
	}

	/**
	 * Метод явно добавляющий в систему новый роут
	 * @param string $method - метод http запроса.
	 * @param string $url_pattern - строка которой должна соответствовать ссылка по которой перейдет пользователь
	 * @return object $this - возвращает себя (екземпляр текущего класса) 
	 */
	public function register($method, $url_pattern)
	{
		$this->registered_routes[$method . ':' . $url_pattern] = [];
		$this->last_route_name = $method . ':' . $url_pattern;
		return $this;
	}

	/**
	 * Задает регистрируемому роуту контроллер
	 * @param string $controller - имя контроллера
	 * @return object $this - возвращает себя (екземпляр текущего класса) 
	 */
	public function setController($controller)
	{
		$this->registered_routes[$this->last_route_name]['controller'] = $controller;
		return $this;
	}

	/**
	 * Задает регистрируемому роуту контроллер
	 * @param string $controller - имя контроллера
	 * @return object $this - возвращает себя (екземпляр текущего класса) 
	 */
	public function setAction($action)
	{
		$this->registered_routes[$this->last_route_name]['action'] = $action;
		return $this;
	}

	/**
	 * Валидирует текущую часть текущего роута.
	 * @param string $url_pattern_parts - Разбитый по слэшу на части шаблон зарегистрированного роута
	 * @param string $current_route_parts - Разбитый по слэшу на части роут по которому пришел юзер
	 * @return array - возвращает массив, каждій елемент которого является bool true|false, и указывает на соответствие
	 * между текущим роутом и зарегистрированным
	 */
	private function verifiedRouteParts($url_pattern_parts, $current_route_parts)
	{
		return array_map(function ($param, $value) {
			/** данная часть роута соответствует паттерну */
			if ($param == $value) return true;
			else {
				/** Если значение отсутствует */
				if (!$value) return false;
				/** Если значение содержит что-то кроме латинских букв, цыфр, дефиса или нижнего подчеркивания */
				if(!preg_match("/^[a-z0-9_-]+$/", $value)) return false;
				/** Если параметр не соответствует шаблону {param} */
				if(!preg_match("/^\{\w+\}$/", $param)) return false;
				/** Если все ок - возвращаем массив, для установки урл параметров в реквест */
				return ['param' => $param, 'value' => $value];
			}
		}, $url_pattern_parts, $current_route_parts);
	}

	/**
	 * Передаем в реквест параметры урл запроса. Далее мы сможем получить к ним доступ из любого места приложения
	 * @param array $verified_route_parts - Уже проверенные на соответствие и валидность части текущего роута
	 */
	private function setUrlParams($verified_route_parts)
	{
		foreach ($verified_route_parts as $verified_item) {
			if (is_array($verified_item)) Kirasin::$app->request->setUrlParam(
				str_replace(['{', '}'], null, $verified_item['param']),
				$verified_item['value']
			);
		}
	}

	/**
	 * Валидирует текущий роут. Он должен соответствовать одному из зарегистрированных роутов, и не должен содержать запрещенных символов
	 * @param string $current_route - текущий роут
	 * @param string $current_method - текущий метод
	 */
	public function verifyRoute($current_route, $current_method)
	{
		if ($current_route == '/'){
			$this->current_route = "GET:/";
			return true;
		}
		$current_route_parts = explode('/', substr($current_route, 1));
		foreach ($this->registered_routes as $url_pattern => $items) {
			list($registered_method, $registered_route) = explode(':', $url_pattern);

			if ($registered_route == '/') continue;
			$url_pattern_parts = explode('/', substr($registered_route, 1));

			/** Проверяем роут на соответствие паттерну */
			$verified_route_parts = $this->verifiedRouteParts($url_pattern_parts, $current_route_parts);

			/** если ни одного несовпадения (роут соответствует паттерну) */
			if (!in_array(false, $verified_route_parts)) {
				/** если не соответствуют методы - прерываем текущую итерацию */
				if ($current_method != $registered_method) continue;
				/** Меняем имя роута на паттерн, которому он соответствует */
				$this->current_route = $url_pattern;
				/** Если есть какие-то указанные в паттерне параметры - мы их отдаем реквесту */
				$this->setUrlParams($verified_route_parts);
				/** Возвращаем true - роут соответствует паттерну */
				return true;
			}
		}

		/** Если роут не соответствует ни одному паттерну возвращаем false */
		return false;
	}

	/**
	 * Запуск текущего роута
	 * Вначале вызываем проверку, если все ок запускаем указанные контроллер и екшен
	 */
	public function runCurrentRoute()
	{
		if(!$this->verifyRoute($this->current_route, $this->current_method)) {
			$this->current_route = $this->current_method . ':/404';
		}

		$controller_name = $this->getControllerName();
		$action_name = $this->getActionName();

		$controller = new $controller_name();
		if (!method_exists($controller, $action_name)) $action_name = 'actionNotFound';
		$controller->prepareLayout()->beforeAction()->$action_name();
	}
	
	/**
	 * Вспомогательный метод, выдает полное имя класса нужного контроллера
	 * @return string
	 */
	private function getControllerName()
	{
		$name = $this->registered_routes[$this->current_route]['controller'];
		if ($name == 'Main') return Kirasin::$app->config['main_controller'];
		if ($name == 'NotFound') return Kirasin::$app->config['not_found_controller'];
		return Kirasin::$app->config['controllers_namespace'] . $name . 'Controller';
	}

	/**
	 * Вспомогательный метод, выдает полное имя класса нужного екшена
	 * @return string
	 */
	private function getActionName()
	{
		return 'action' . $this->registered_routes[$this->current_route]['action'];
	}

}