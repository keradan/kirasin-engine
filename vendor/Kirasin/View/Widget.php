<?php

namespace Kirasin\View;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Абстрактный класс который содержит методы для работы с виджетами
 */
abstract class Widget
{
	/** Конструктор закрыт */
	private function __construct() {}

	/**
	 * @var strin $views_dir - путь к файлам представления
	 */
	private $views_dir;
	
	/**
	 * @var string $extension - Расширение файлов представления
	 */
	private $extension = '.php';

	/**
	 * Единая точка входа в виджет. Метод который вызывается для выведения виджета.
	 * @return возвращает метод __toString()
	 */
	public static function view()
	{
		$widget = new static();
		$widget->views_dir = Kirasin::$app->request->root . Kirasin::$app->config['views_dir'];
		return $widget->__toString();
	}

	/**
	 * Метод который соединяет файл представления текущего виджета с данными и возвращает html код
	 * @return html код
	 */
	public function __toString()
	{
		extract($this->viewData());
		ob_start();
		include($this->views_dir . 'widgets/' . $this->viewName() . $this->extension);
		return ob_get_clean();
	}

	/** Методы обязательные для переопредиления в дочерних классах */
	abstract protected function viewName() : string;
	abstract protected function viewData() : array;
}