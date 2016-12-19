<?php

namespace Kirasin;

/**
 * @author Даниил Керасиди
 * Обьект приложения
 * В него помещаеться контейнер зависимостей
 * таким образом осуществляется доступ почти ко всем компонентам приложения.
 * Исключением являются контроллеры - они вызываются через роутер, и компоненты
 * не принадлежащих непосредственно к движку, такие как модели БД, сервисы бизнес логики, виджеты и представления.
 */
class App
{
	public $config;
	
	/**
	 * @var object $container - Контейнер зависимостей. При обращении создает новый екземпляр компонента
	 */
	private $container;

	/**
	 * @var array $injected_components - Массив содержащий компоненты, которые уже были вызваны из контейнера.
	 */
	private $injected_components = [];

	/**
	 * При создании объекта приложения устанавливаются настройки и контейнер
	 * @param array $config - массив настроек
	 * @param object $container - объект контейнер с зарегестрированными колбеками, создающими и возвращающими объект компоненты
	 */
	public function __construct(array $config, $container)
	{
		$this->config = $config;
		$this->container = $container;

		mb_internal_encoding("UTF-8");

		if ($this->config['debug']) {
			ini_set('error_reporting', E_ALL);
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
		}
	}

	/**
	 * При запуске приложения мы совершаем попытку запустить текущий роут {}
	 * Он проверяется, если все норм - вызывается указанный там action 
	 * В конце мы выводим то что стобрал в себя объект $response
	 */
	public function run()
	{
		$this->router->runCurrentRoute();
		echo $this->response;
		die();
	}

	/**
	 * Если вызывается несуществующее свойство попадаем сюда
	 * @param string $name - Имя искомого свойства.
	 * @return object|null Объект из контейнера.
	 */
	public function __get($name)
	{
		if (!isset($this->injected_components[$name])) {
			if ($this->container->offsetExists($name)) {
				$this->injected_components[$name] = $this->container[$name];
			} else return null;
		}
        return $this->injected_components[$name];
	}

}