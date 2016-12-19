<?php

namespace Widgets;

use Kirasin\Kirasin;
use Models\Users;

/**
 * 
 */
class NavWidget extends \Kirasin\View\Widget
{

	protected function viewName() : string
	{
		return 'nav';
	}

	protected function viewData() : array
	{
		return [
			'username' => Users::find()->one(Kirasin::$app->user->getId())->username,
			'urlManager' => Kirasin::$app->urlManager,
		];
	}
}