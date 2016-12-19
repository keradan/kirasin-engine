<?php
/**
 * В  этом файле мы создаем контейнеры зависимостей
 * Реализация инъекции зависимостей. Через этот механизм осуществляется доступ почти ко всем компонентам приложения.
 * Исключением являются контроллеры - они вызываются через роутер, и компоненты
 * не принадлежащих непосредственно к движку, такие как модели БД, сервисы бизнес логики, виджеты и представления.
 */

use Kirasin\DependencyInjection\Container;

$container = new Container([
	'request' => function ($c) {
		return new Kirasin\Http\Request();
	},
	'response' => function ($c) {
		return new Kirasin\Http\Response();
	},
	'session' => function ($c) {
		return new Kirasin\Http\SessionStorage();
	},
	'cookies' => function ($c) {
		return new Kirasin\Http\CookiesStorage();
	},
	'flash' => function ($c) {
		return new Kirasin\Http\FlashMasseges();
	},
	'user' => function ($c) {
		return new Kirasin\AccessControll\User();
	},
	'validator' => function ($c) {
		return new Kirasin\Valid\Validator();
	},
	'router' => function ($c) {
		return new Kirasin\Routing\Router();
	},
	'urlManager' => function ($c) {
		return new Kirasin\Routing\UrlManager();
	},
	'db' => function ($c) {
		return new Kirasin\Model\DataBase();
	},
	'viewer' => function ($c) {
		return new Kirasin\View\PhpViewer();
	},
]);