<?php

use Kirasin\Kirasin;
use Kirasin\App;

/** PSR-4 Автозагрузка (composer) */
require_once __DIR__ . '/../vendor/autoload.php';

/** Файл конфигурации с параметрами приложения */
require_once __DIR__ . '/../init/config.php';

/** Инъекция зависимостей через контейнер */
require_once __DIR__ . '/../init/dependencyes.php';

/** Инициализация приложения */
Kirasin::$app = new App($config, $container);

/** Определение роутов */
require_once __DIR__ . '/../init/routs.php';

Kirasin::$app->run();