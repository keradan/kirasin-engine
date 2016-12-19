<?php
/**
 * Это файл в котором мы явно определяем все доступные роуты.
 * Любая ссылка не соответствующая ни одному из этих роутов не пройдет. Будет - 404
 */

use Kirasin\Kirasin;

$r = Kirasin::$app->router;
$r->register('GET' , '/')                 ->setController('Main')    ->setAction('Index');
$r->register('GET' , '/message')          ->setController('Main')    ->setAction('Message');
$r->register('GET' , '/login')            ->setController('User')    ->setAction('Login');
$r->register('POST', '/login')            ->setController('User')    ->setAction('PostLogin');
$r->register('GET',  '/logout')           ->setController('User')    ->setAction('Logout');
$r->register('POST', '/add-review')       ->setController('Main')    ->setAction('AddReview');
$r->register('GET' , '/admin')            ->setController('Admin')   ->setAction('Index');
$r->register('GET' , '/admin/edit/{id}')  ->setController('Admin')   ->setAction('EditReview');
$r->register('POST', '/admin/edit/{id}')  ->setController('Admin')   ->setAction('PostEditReview');
$r->register('POST', '/admin/accept/{id}')->setController('Admin')   ->setAction('PostAcceptReview');
$r->register('GET' , '/404')              ->setController('NotFound')->setAction('Index');