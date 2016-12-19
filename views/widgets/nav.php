	<div class="container-fluid kirasin-nav">
        <div class="row">
            <div class="col-xs-5 col-xs-offset-1">
                <?= ($username)? "Добро пожаловать, <span style=\"color:#CC9900;\">$username.</span>" : null?>
            </div>
            <div class="col-xs-5 text-right">
                <a href="<?= $urlManager->generateLink('/') ?>">Главная</a>
                <?php if($username):  ?>
                    <a href="<?= $urlManager->generateLink('/admin') ?>">Админка</a>
                    <a href="<?= $urlManager->generateLink('/logout') ?>">Выйти</a>
                <?php else: ?>
                    <a href="<?= $urlManager->generateLink('/login') ?>">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </div>