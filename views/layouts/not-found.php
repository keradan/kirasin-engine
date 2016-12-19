<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Страница не найдена</title>

        <!-- css -->
        <?php foreach ($css_files as $css_file): ?>
            <link href="<?= $css_path . $css_file ?>" rel="stylesheet">
        <?php endforeach; ?>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

        <!-- JQuery cookie -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

        <!-- js -->
        <?php foreach ($js_files as $js_file): ?>
            <script src="<?= $js_path . $js_file ?>"></script>
        <?php endforeach; ?>
    </head>
  <body>
    <?= Widgets\NavWidget::view() ?>
    <h1 class="text-center">Форма 2 обратной связи. <br><span class="small">Тестовое задание для Beejee</span></h1><br /><br /><br />
    <h2 class="text-center">Страница не найдена(</h2>
    <main>
        <?= $content ?>
    </main>
    
    <br /><br /><br /><br /><br />
    <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
  </body>
</html>