        <?php if ($event) include(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'message.php'); ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <form class="form-horizontal" method="post" action="<?= $form_action ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Имя:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="author" value="<?= $review->author ?>" required>
                                <p class="help-block" id="name_help_block" style="color: red;"><?= $validate_errors->author ?? null ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" value="<?= $review->email ?>" required>
                                <p class="help-block" id="name_help_block" style="color: red;"><?= $validate_errors->email ?? null ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment" class="col-sm-3 control-label">Текст:</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="5" id="text" name="text" required><?= $review->text ?></textarea>
                                <p class="help-block" id="name_help_block" style="color: red;"><?= $validate_errors->text ?? null ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">                            
                                <button type="submit" class="btn btn-success">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>