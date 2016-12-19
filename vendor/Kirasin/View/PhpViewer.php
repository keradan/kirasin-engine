<?php

namespace Kirasin\View;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 *  Класс шаблонизатор. Соединяет елементы представления друг с другом.
 */
class PhpViewer
{

	/**
	 * @var string $layout - содержит готовый и заполненный данными HTML код шаблона страницы
	 */
	private $layout;

	/**
	 * @var string $view - содержит готовый и заполненный данными HTML код представления екшена
	 */
	private $view;

	/**
	 * @var string $views_dir - путь к файлам представления
	 */
	private $views_dir;

	/**
	 * @var string $extension - Расширение файлов представления
	 */
	private $extension = '.php';

	/**
	 * При создании устанавливает путь к файлам представления
	 */
	public function __construct()
	{
		$this->views_dir = Kirasin::$app->request->root . Kirasin::$app->config['views_dir'];
	}

	/**
	 * Добавляет к шаблону страницы и к формам необходимые теги с CSRF токеном
	 * @param string $csrf_token - токен полученный от респонса
	 * @return Сам себя
	 */
	public function setCsrf($csrf_token)
	{
		$this->layout = str_replace(['<head>', '</form>'], [
			'<head><meta name="csrf_token" content="' . $csrf_token . '">',
			'<input type="hidden" name="csrf_token" value="' . $csrf_token . '"></form>',
		], $this->layout);

		return $this;
	}

	/**
	 * Собирает представление екшена
	 * @param string $view_dir - директория где лежит конкретный файл представления
	 * @param string $view_name - имя файла представления
	 * @param array $data - массив данных, которыми наполняется представление
	 * @return Сам себя
	 */
	public function buildView($view_dir, $view_name, array $data)
	{
		$view_path = $view_name . $this->extension;
		if($view_dir) $view_path = $view_dir . '/' . $view_path;

		extract($data);
		ob_start();
		include($this->views_dir . 'views/' . $view_path);
		$this->view = ob_get_clean();
		return $this;
	}

	/**
	 * Собирает шаблон страници
	 * @param string $controller_name - имя контроллера, оно же используется как имя файла шаблона
	 * @return Сам себя
	 */
	public function buildLayout($controller_name)
	{
		$content = $this->view;
		$css_path = Kirasin::$app->request->url . Kirasin::$app->config['css_path'];
		$js_path = Kirasin::$app->request->url . Kirasin::$app->config['js_path'];
		$css_files = Kirasin::$app->config['css_files'];
		$js_files = Kirasin::$app->config['js_files'];
		ob_start();
		include($this->views_dir . 'layouts/' . $controller_name . $this->extension);
		$this->layout = ob_get_clean();
		return $this;
	}

	/**
	 * Возвращает шаблон страницы. Он представляет из себя готовую HTML страницу от начала и до конца
	 * @return свойство $this->layout
	 */
	public function getHtml()
	{
		return $this->layout;
	}

}