<?php

namespace Kirasin\Routing;

use Kirasin\Kirasin;
/**
 * @author Даниил Керасиди
 * Класс генератор ссылок. 
 */
class UrlManager
{
	/**
	 * @var string $base_url - домен сайта
	 */
	private $base_url;
	
	/**
	 * @var string $img_url - урл ссылка на папку с картинками
	 */
	private $img_url;

	/**
	 * @var string $img_dir - папка с картинками от корня
	 */
	private $img_dir;

	/**
	 * При создании устанавливаем свойства
	 */
	public function __construct()
	{
		$this->base_url = Kirasin::$app->request->url;
		$this->img_url = Kirasin::$app->request->url . Kirasin::$app->config['img_path'];
		$this->img_dir = Kirasin::$app->request->public_dir . Kirasin::$app->config['img_path'];
	}

	/**
	 * Генерирует правильную урл ссылку, соответствующую хотя бы одному из зарегестрированных роутов
	 * @param string $route - путь
	 * @param string|null $scroll - id елемента страницы, до которой нужно отскроллить
	 * @return string - html ссылка
	 */
	public function generateLink($route, $scroll = null)
	{
		if (!Kirasin::$app->router->verifyRoute($route, 'GET')) return false;
		return $this->base_url . $route . $scroll;
	}

	/**
	 * Генерирует правильную ссылку для атрибута формы "action", соответствующую хотя бы одному из зарегестрированных роутов
	 * @param string $route - путь
	 * @return string - содержимое html аттрибута action
	 */
	public function generateFormAction($route)
	{
		if (!Kirasin::$app->router->verifyRoute($route, 'POST')) return false;
		return $this->base_url . $route;
	}

	/**
	 * Генерирует правильную ссылку на существующую картинку
	 * @param string $img_path - имя картинки и возможно поддиректория в которой она вложена
	 * @return string - содержимое html аттрибута src
	 */
	public function generateImgSrc($img_path)
	{
		if (!file_exists($this->img_dir . $img_path)) return false;
		return $this->img_url . $img_path;
	}
}