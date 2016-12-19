<?php
/**
 * Это файл конфигурации. Это массив и он доступен через объект приложения
 */

$config = [
	/** Режим дебаг */
	'debug' => true,

	/** База данных */
	'db' => [
		'db_host' => 'localhost',
		'db_name' => 'beejee',
		'db_user' => 'beejee_user',
		'db_pass' => '12345',
		'db_pref' => 'beejee_krsn_1312_',
	],

	/** Класс модели пользователя. Используется компонентом приложения "User" для осуществления контроля доступом */
	'user_model_class' => '\Models\Users',

	/** Мои контроллеры модели и представления для этого приложения */
	'controllers_namespace' => '\Controllers\\',
	'models_namespace' => '\Models\\',
	'views_dir' => '/views/',
	'widgets_dir' => '/widgets/',

	/**
	 * Классы контроллеры для главной страницы и 404 
	 * Можна создать свой, и разместить в \Controllers\{имя контроллера}
	 * По умолчанию можно задать такие:
	 * main_controller - \Kirasin\Controller\DefaultController
	 * not_found_controller - \Kirasin\Controller\NotFoundController
	 */
	'main_controller' => '\Controllers\MainController',
	'not_found_controller' => '\Controllers\NotFoundController',

	/** Допустимые параметры загружаемой картинки */
	'img_max_width' => '320',
	'img_max_height' => '240',
	'img_allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],

	/** Пути для картинок, css и js файлов */
	'img_path' => '/img/',
	'css_path' => '/css/',
	'js_path' => '/js/',

	/** Подключаем стили */
	'css_files' => [
		'style.css',
	],

	/** Подключаем js скрипты */
	'js_files' => [
		'bootstrap.min.js',
		'img-preview.js',
    	'sort.js',
    	'preview.js',
    	'form.js',
	],

];