        <div class="container">
            <div class="row">
                <div class="col-xs-10 text-center">
                    Сортировать:
                    <select name="sort" id="sort" style="padding:5px;">
                        <option value="date"<?= ($sort == 'date')? 'selected' : null ?>>По дате</option>
                        <option value="author"<?= ($sort == 'author')? 'selected' : null ?>>По имени</option>
                        <option value="email"<?= ($sort == 'email')? 'selected' : null ?>>По e-mail</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="container">
            <?php foreach ($reviews as $review): ?>
                <div class="row review">
                    <div class="col-xs-10 col-xs-offset-1"><?= $review->author ?></div>
                    <div class="col-xs-10 col-xs-offset-1"><?= $review->email ?></div>
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="review-content">
                            <p><?= $review->text ?></p>
                            <?= ($review->img)? '<img src="' . $review->img . '" alt="some img">' : null ?>
                        </div>
                    </div>
                    <div class="col-xs-6 col-xs-offset-1">Отзыв добавлен<?= $review->date ?></div>
                    <div class="col-xs-4 text-muted text-right" <?= ($review->is_edited)? null : 'style="display: none;"' ?>>
                        <i class="glyphicon glyphicon-pencil"></i> Изменен администратором
                    </div><br />
                </div><hr /><br /><br />
            <?php endforeach; ?>
        </div>
        <?php if ($event) include(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'message.php'); ?>
        <div class="container" id="form11">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="add-review">
                        <h3 class="text-center">Добавьте отзыв:</h3><br />
                        <form class="form-horizontal" method="post" action="<?= $form_action ?>" id="add_review_form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Имя:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="author" placeholder="Введите ваше имя..." required>
                                    <p class="help-block" id="name_help_block"><?= $validate_errors->author ?? null ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Введите ваш e-mail..." required>
                                    <p class="help-block text-danger" id="email_help_block"><?= $validate_errors->email ?? null ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comment" class="col-sm-3 control-label">Текст:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" id="text" name="text" placeholder="Введите текст сообщения..." required></textarea>
                                    <p class="help-block text-danger" id="text_help_block"><?= $validate_errors->text ?? null ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add_img" class="col-sm-3 control-label">Картинка:</label>
                                <div class="col-sm-9">
                                    <input type="file" id="add_img" name="img" accept="image/jpeg,image/png,image/gif"> <span id="add_img_reset">Отменить файл &#10006;</span>
                                    <img id="img_preview" src="img/img.png" alt="Превью картинки" class="img-round img-thumbnail">
                                    <p class="help-block text-danger" id="text_help_block"><?= $validate_errors->img ?? null ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" id="show_preview_box" class="btn btn-default">Предварительный просмотр</button>
                                    <button type="submit" class="btn btn-success">Отправить</button>
                                </div>
                            </div>
                        </form>
                        <div class="preview-box">
                            <div class="row review">
                                <div class="col-xs-10 col-xs-offset-1" id="preview_box_name"><p>Вася</p></div>
                                <div class="col-xs-10 col-xs-offset-1" id="preview_box_email"><p>vasya@gmail.com</p></div>
                                <div class="col-xs-10 col-xs-offset-1">
                                    <div class="review-content" id="preview_box_content">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel, voluptas veritatis. Nemo suscipit expedita sit architecto nobis quas, nostrum non eveniet facere sequi officia vero doloribus esse ad, quibusdam voluptatem.</p>
                                        <img src="img/img.png" alt="some img">
                                    </div>
                                </div>
                                <div class="col-xs-10 col-xs-offset-1" id="preview_box_date"><p>Добавлено в 12:45 24.12.16</p></div><br />
                            </div>
                            <p class="text-center"><button type="button" id="submit_preview_box" class="btn btn-lg btn-success">Отправить <i class="glyphicon glyphicon-send"></i></button></p><br />
                        </div>
                    </div>
                </div>
            </div>
        </div>