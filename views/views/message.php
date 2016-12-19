        <div class="container" id="message">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-<?= ($event->success)? 'success' : 'danger' ?>">
                        <p class="panel-heading"><?= $event->head ?></p>
                        <p class="panel-body"><?= $event->message ?> <br/> <?= $validate_errors->common ?? null ?></p>
                    </div>
                </div>
            </div>
        </div>