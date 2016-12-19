<?php

namespace Kirasin\Http;

use Kirasin\Kirasin;
/**
 * @author Даниил Керасиди
 * Класс который собирает в себя весь вывод приложения.
 */
class Response
{
	/**
	 * @var string $html_response - HTML код который выводится как ответ сервера
	 */
	private $html_response;

	/**
	 * Метод который устанавливает значение в свойство $html_response
	 * @param string $html - содержит код HTML
	 */
	public function setHtmlResponse($html)
	{
		$this->html_response = $html;
	}

	/**
	 * генерирует CSRF токен для страницы и форм
	 * @return string $token - CSRF токен
	 */
	public function generateCsrfToken()
	{
		$token = str_shuffle(sha1('ad@gfj^*hs34b7nsdfg/*fhj' . time() . session_id()));
		Kirasin::$app->session->set('csrf_token_' . session_id(), $token);
		return $token;
	}

	/**
	 * Метод вызывается при попытке вывести данный объект
	 * @return string html_response - возвращает свойство html_response, с HTML кодом страницы
	 */
	public function __toString()
	{
		return $this->html_response;
	}

}