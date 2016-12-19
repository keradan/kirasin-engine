<?php

namespace Controllers;

use Kirasin\Kirasin;
use \Models\Reviews;
use Services\AddReviewService;

/**
 * 
 */
class MainController extends \Kirasin\Controller\Controller
{
	public function actionIndex()
	{
		$cookies = Kirasin::$app->cookies;
		if (!in_array($cookies->get('sort'), ['date', 'author', 'email'])) $cookies->remove('sort');
		$sort = ($cookies->has('sort'))? $cookies->get('sort') : 'date';
		$sort_desc = ($sort == 'date')? true : false;

		$this->render('index', [
			'sort' => $sort,
			'reviews' => Reviews::find()->where('status', 2)->order_by($sort, $sort_desc)->all(),
			'form_action' => $this->urlManager->generateFormAction('/add-review'),
			'event' => Kirasin::$app->flash->getEvent(),
			'validate_errors' => Kirasin::$app->flash->getValidateErrors(),
		]);
	}

	public function actionMessage()
	{
		if(!Kirasin::$app->flash->hasEvent()) $this->redirect('/404');
		$this->setViewDir('');
		$this->render('message', [
			'event' => Kirasin::$app->flash->getEvent(),
		]);
	}

	public function actionAddReview()
	{
		$add_review_service = new AddReviewService($this->request->getFormParams());
		if(!$add_review_service->isValid()) return $this->addEventAndRedirect(false, 'Ошибка!', 'Что-то не в порядке с данными формы', '/');
		
		$review = Reviews::new();
		$review->author = $add_review_service->author;
		$review->email = $add_review_service->email;
		$review->text = $add_review_service->text;
		$review->img = $add_review_service->img;
		$review->date = time();

		if(!$review->save()) return $this->addEventAndRedirect(false, 'Ошибка!', 'Отзыв не был добавлен', '/');
		if($add_review_service->img && !$add_review_service->loadImg())
			return $this->addEventAndRedirect(false, 'Ошибка!', 'Отзыв загрузился без картинки', '/');
		return $this->addEventAndRedirect(true, 'Отзыв добавлен.', 'Спасибо за ваше внимание.', '/');
	}
}