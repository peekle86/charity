<?php

/* @var $this \yii\web\View */
/* @var $content string */


use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

if (empty(Yii::$app->user->id) && !strstr(Yii::$app->request->url, 'login')) {
    return Yii::$app->response->redirect( Url::to( '/login' ) );
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<body >
    <?php $this->beginBody() ?>
    <div id="app">
        <router-view></router-view>
    </div>

    <script src="/js/app.js"></script>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
