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
     * @return mixed س
     */
    public function actionIndex()
    {
//        $session = Yii::$app->session;
//        if ($session->has('redirect_url')) {
//            $url = $session->get('redirect_url');
//            $session->remove('redirect_url');
//
//            return $this->redirect($url);
//        }

       return $this->render('index');
    }

    public function actionGoto($name)
    {
        $link = Link::find()
            ->where(['name' => $name])
            ->one();

        $linkDomain = $link->activeDomain;

        if ($linkDomain) {
//            $session = Yii::$app->session;
//            $session->set('redirect_url', $linkDomain['affiliate_url']);

            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'redirect_url',
                'value' => $linkDomain['affiliate_url'],
                'httpOnly' => false
            ]));
        }

        return $this->redirect('/');
    }

}
