<?php

namespace app\controllers;

use Admitad\Api\Api;
use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\Setting;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Link;
use app\models\LinkQuery;


class SiteController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string س
     */
    public function actionIndex()
    {
//        $admitad = new Admitad();
//        $admitad->collect();
//
//        echo 'Було додано: ' . $adgoal->added_count . ' нових посилань/магазинів'
//            . PHP_EOL
//            . 'Пропущено по причині існування в базі: ' . $adgoal->exists_count;

       return $this->render('index');
    }

    public function actionGoto($name)
    {
        $link = Link::find()
            ->where(['name' => $name])
            ->one();

        $linkDomain = $link->getActiveDomain();

        if ($linkDomain) {
            return $this->redirect($linkDomain['affiliate_url']);
        } else {
            return $this->redirect('/');
        }

    }

}
