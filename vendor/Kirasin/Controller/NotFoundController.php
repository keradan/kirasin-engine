<?php

namespace Kirasin\Controller;

/**
 * @author Даниил Керасиди
 * Контроллер для ошибки 404 по умолчанию
 */
class NotFoundController extends Controller
{
	
	public function actionIndex()
	{
		$this->actionNotFound();
	}

}