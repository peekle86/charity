<?php

namespace app\controllers;

use app\models\LinkDomain;
use app\models\User;
use app\models\Log;
use app\models\Link;
use app\models\LinkQuery;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\This;
use Yii;
use app\models\Setting;
use yii\filters\AccessControl;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\validators\EmailValidator;
use yii\helpers\Url;
use yii\httpclient\Client;
use \yii\web\Cookie;
/**
 *
 * @property-read void $authKey
 */
class SearchController extends Controller {

    public $enableCsrfValidation = false;
    private $key = 'AIzaSyDhbyy4NDWLurxI1KeqSG1DucNHG1SU7KM';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'rules'        => [
                    [ 'allow' => true, 'actions' => [ 'search' ], 'roles' => [ '@', '?' ] ],
                    [ 'allow' => true, 'actions' => [ 'logo' ], 'roles' => [ '@', '?' ] ],
                ],
                'denyCallback' => function ( $rule, $action ) {
                    Yii::$app->response->redirect( [ 'login' ] );
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    private function getVisIpAddr() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (str_contains($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                return explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    private function getUserCountry( $ip ) {
        $client = new Client();

        $res = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
        if (isset( $res['geoplugin_countryCode'])) {
            return $res['geoplugin_countryCode'];
        }
        return false;


    }

    public function actionLogo(  ) {
        if ( ! Yii::$app->request->isPost ) {
            return [ 'status' => 401, 'message' => 'Go away' ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        $logo = Setting::find()->where(['name' => 'logo'])->one()->value;
        $b_title = Setting::find()->where(['name' => 'b_title'])->one()->value;
        $home_title = Setting::find()->where(['name' => 'home_title'])->one()->value;
        $home_desc = Setting::find()->where(['name' => 'home_desc'])->one()->value;
        return [ 'logo' => $logo, 'b_title' => $b_title, 'home_desc' => $home_desc, 'home_title' => $home_title ];

    }

    public function actionSearch() {

        if ( ! Yii::$app->request->isPost ) {
            return [ 'status' => 401, 'message' => 'Go away' ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $client = new Client();
        $log = new Log();
        $isset = Setting::find()->where(['name' => 'g_api_key'])->one()->value;
        if ($isset) {
            $key = $isset;
        } else {
//            $key = $this->key;

        }
        $search = urlencode( Yii::$app->request->post('q'));
        $link = null;

        try {

            $cookies = Yii::$app->response->cookies;
            $country = $this->getUserCountry( $this->getVisIpAddr());

            $gl = '';
            if ($country) {
                $gl  = "&gl=".$country;
            }

            if (!empty($_COOKIE['lang'])) {
                $lang = $_COOKIE['lang'];
            } else {
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            }

            $hl = '';

            if ($lang) {
                $hl  = "&hl=".$lang;

            }
            $res = $client
                ->createRequest()
                ->setMethod( 'GET' )
                ->setUrl( "https://www.googleapis.com/customsearch/v1?key={$key}&cx=94d87e4862372aba2&q={$search}".$hl.$gl )
                ->send();


            $ads = Link::find()->all();

            $links = [];

            foreach ($res->data['items'] as $result) {
//                $target_domain = parse_url($result['link']);
//                $target_domain = str_replace('www.', '', $target_domain['host']););
                $target_domain = str_replace('www.', '', $result['displayLink']);

                if (!$link) {
                    $domain = LinkDomain::find()
                        ->where(['active' => 1])
                        ->andWhere(['like', 'name', '%'.$target_domain.'%', false])
                        ->limit(1)
                        ->one();

                    if ($domain) {
                        $temp = $domain->link;
                        if ($temp->active == 1) {
                            $link = $temp;
                        }
                    }
//                    $link = Link::find()
//                        ->joinWith('domains')
//                        ->where(['link.active' => 1])
//                        ->where(['like', 'link_domain.name', '%'.$target_domain.'%'])
//                        ->limit(1)
//                        ->one();
                }

                if ($link) {
                    $issetLink = false;
                    foreach ($links as $setLink) {
                        if ($setLink->id == $link->id) {
                            $issetLink = true;
                        }
                    }
                    if (!$issetLink) {
                        $links[] = $link;
                    }
                }

//                foreach ($ads as $ad) {
////                    echo "<pre>";
////                        print_r($ad['target_domain']);
////                        print_r($result['displayLink']);
////                    echo "</pre>";
//                    if (stristr(  $result['displayLink'], $ad['target_domain'])) {
//                        $link =  $ad;
//                    }
//                }

            }

            $log->attributes = [
                'type' => 'search',
                'data' => $search
            ];

//            total_support

            $log->save();
        } catch (\Exception $exception) {
            $res = $exception->getMessage();
            return [ 'status' => 400, 'results' => $res, 'ads' => $link  ];
        }

        return [ 'status' => 200, 'results' => $res->data, 'ads' => $link ];
    }

}
