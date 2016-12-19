<?php

namespace Controllers;

use Kirasin\Kirasin;
use \Models\Reviews;
use Services\AcceptReviewService;
use Services\EditReviewService;

/**
 * 
 */
class AdminController extends \Kirasin\Controller\Controller
{
	
	private $flash;

	public function beforeAction()
	{
		$this->flash = Kirasin::$app->flash;
		if (!Kirasin::$app->user->isLoggedIn()) $this->addEventAndRedirect(false, 'Доступ закрыт!', 'Для доступа в админ панель авторизуйтесь.', '/login');
		return $this;
	}

	public function actionIndex()
	{
		$this->render('index', [
			'reviews' => Reviews::find()->order_by('status')->order_by('date', true)->all(),
			'urlManager' => $this->urlManager,
			'event' => $this->flash->getEvent(),
		]);
	}

	public function actionEditReview()
	{
		$review = Reviews::find()->one($this->request->getUrlParam('id'));
		if (!$review) return $this->actionNotFound();
		$this->render('edit', [
			'review' => $review,
			'form_action' => $this->urlManager->generateFormAction('/admin/edit/' . $this->request->getUrlParam('id')),
			'event' => Kirasin::$app->flash->getEvent(),
			'validate_errors' => Kirasin::$app->flash->getValidateErrors(),
		]);
	}

	public function actionPostAcceptReview()
	{
		$accept_review_service = new AcceptReviewService($this->request->getFormParams());
		if(!$accept_review_service->isValid()) return $this->addEventAndRedirect(false, 'Ошибка!', 'Что-то пошло не так...', '/admin');

		$review = Reviews::find()->one($this->request->getUrlParam('id'));
		if (!$review) $this->addEventAndRedirect(false, 'Ошибка', 'Отзыва не существует(', '/admin');
			
		$review->status = $accept_review_service->status;
		if (!$review->save()) $this->flash->addEvent(false, 'Ошибка', 'Статус не был изменен(');
		else $this->flash->addEvent(true, 'Поздравляем!', 'Статус успешно изменен.');
		$this->redirect('/admin');
	}

	public function actionPostEditReview()
	{
		$edit_review_service = new EditReviewService($this->request->getFormParams());
		if(!$edit_review_service->isValid())
			return $this->addEventAndRedirect(false, 'Ошибка!', 'Что-то пошло не так...', '/admin/edit/' . $this->request->getUrlParam('id'));

		$review = Reviews::find()->one($this->request->getUrlParam('id'));
		if (!$review) $this->flash->addEventAndRedirect(false, 'Ошибка', 'Отзыва не существует(', '/admin');

		$review->author = $edit_review_service->author;
		$review->email = $edit_review_service->email;
		$review->text = $edit_review_service->text;
		$review->is_edited = 1;

		if (!$review->save()) $this->flash->addEvent(false, 'Ошибка', 'Отзыв небыл изменен(');
		else $this->flash->addEvent(true, 'Поздравляем!', 'Статус успешно отредактирован.');
		$this->redirect('/admin');
	}

}