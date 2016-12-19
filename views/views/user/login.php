        
        <?php if ($event) include(dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'message.php'); ?>

        <div class="container">
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                    <h3 class="text-center">Войдите:</h3><br>
                    <form class="form-horizontal" method="post" action="<?= $form_action ?>" id="login_form" >
                        <div class="form-group">
                            <label for="login" class="col-sm-2 control-label">Логин</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="login" name="login" placeholder="Введите логин..." required>
                                <p class="help-block" id="name_help_block" style="color: red;"><?= $validate_errors->login ?? null ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Пароль</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль..." required>
                                <p class="help-block" id="name_help_block" style="color: red;"><?= $validate_errors->password ?? null ?></p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-lg btn-success">Подтвердить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>