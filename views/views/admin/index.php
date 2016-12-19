        <?php if ($event) include(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'message.php'); ?>
        <div class="container">
            <div class="row ">
                <div class="col-xs-12">
                    <table class="table table-striped table-reviews">
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Текст</th>
                                <th>Картинка</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Изменить</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($reviews as $review): ?>
    <tr <?= ($review->status == 0)? 'class="new-review"' : null ?>>
        <td><?= $review->author ?></td>
        <td><?= $review->email ?></td>
        <td><?= $review->text ?></td>
        <td class="review-img">
            <?= ($review->img)? '<img src="' . $review->img . '" class="img-responsive img-thumbnail img-round">' : '&mdash;' ?>
        </td>
        <td><?= $review->date ?></td>
        <td class="text-center text-<?php if ($review->status == 2) echo 'success'; if ($review->status == 1) echo 'danger';?>" nowrap>
            <?php if ($review->status == 0): ?>
                <form method="post" action="<?php echo $urlManager->generateFormAction('/admin/accept/' . $review->id); ?>">
                    <button type="submit" class="btn btn-success" name="status" value="2"><i class="glyphicon glyphicon-ok"></i></button>
                    <button type="submit" class="btn btn-danger" name="status" value="1"><i class="glyphicon glyphicon-remove"></i></button>
                </form>
            <?php elseif ($review->status == 1) : ?>
                <i class="glyphicon glyphicon-remove"></i> Отклонен
            <?php elseif ($review->status == 2) : ?>
                <i class="glyphicon glyphicon-ok"></i> Принят
            <?php endif; ?>
        </td>
        <td class="text-center"><a class="btn btn-warning" href="<?= $review->link ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
    </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>  
                </div>
            </div>
        </div>
