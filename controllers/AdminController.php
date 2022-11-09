<?php

namespace app\controllers;

use app\models\Link;
use schallschlucker\simplecms\controllers\backend\SettingsAndMaintenanceController;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\Cookie;

use app\models\Setting;
use app\models\Page;
use app\models\User;
use app\models\UserQuery;
use app\models\Log;
use app\models\LogQuery;
use app\models\Category;
use app\models\CategoryQuery;
use app\models\OrganizationQuery;
use app\models\Organization;

class AdminController extends Controller
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
        ];
    }

    public function actionTest()
    {
        var_dump(Link::find()
            ->joinWith('domains')
            ->where(['link_domain.partner_id' => Link::PARTNER_ADGOAL])
            ->count());
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $this->layout = 'admin';

        return $this->render('admin');
    }



    public function actionStatus() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $u = new User();
        $uq = new UserQuery( $u);

        $c = new Category();
        $cq = new CategoryQuery($c);

        $o = new Organization();
        $oq = new OrganizationQuery($o);

        $l = new Log();
        $lq = new LogQuery($l);

        return [
            'users' => $uq->count(),
            'users_active' => $uq->count(),
            'cats' => $cq->count(),
            'orgs' => $oq->count(),
            'orgs_active' => $oq->count(),
            'total_req' => $lq->count(),
            'api_req' => $lq->count(),
        ];
    }


    public function actionGetpage(  ) {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $cookies = Yii::$app->request->cookies;

        $language = $_COOKIE['lang'] ?? Yii::$app->language;

        $page_slug = Yii::$app->request->post('slug');


        $page = Page::find()->where( [ 'slug' => $page_slug ] )->active()->one();
        try {
            $cont = explode( "\n", $page['content']);

            if (is_array( $cont)) {
                if (count( $cont) > 1) {
                    foreach ($cont as $c) {

                        if (stristr( $c, $language.':')) {
                            $c = str_ireplace( $language.':', '', $c);
                            $page['content'] = $c;
                        }
                    }
                }
            }
        } catch (\Exception $exception) {

        }


        return $page;

    }

    public function actionGetsettings(  ) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $s = Setting::find()->all();
        return $s;
    }

    public function actionSavesettings(  ) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $settings = Yii::$app->request->post();

        foreach ( $settings as $name => $value ) {
            $isset = Setting::find()->where(['name' => $name])->one();

            if ($isset !== null ){

                $isset->value=$value;
                $isset->save();

            } else {
                $s = new Setting();
                $set  = [
                    'name' => $name,
                    'value' => $value
                ];
                $s->attributes = $set;
                $s->save();
            }



        }

        return $settings;
    }
}
