<?php

namespace app\controllers;

use app\models\User;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\validators\EmailValidator;
use yii\helpers\Url;

/**
 *
 * @property-read void $authKey
 */
class SignController extends Controller {

    public $enableCsrfValidation = false;


    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'rules'        => [
                    [ 'allow' => true, 'actions' => [ 'signin', 'signup', 'confirm', 'restore', 'restoreconfirm', 'change' ], 'roles' => [ '?' ] ],
                    [ 'allow' => true, 'actions' => [ 'logout' ], 'roles' => [ '@' ] ],
                    [ 'allow' => true, 'actions' => [ 'currentuser', 'getuserlang' ], 'roles' => [ '@', '?' ] ],
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

    public function actionSignin() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ( ! Yii::$app->request->isPost ) {
            return [ 'status' => 401, 'message' => 'Go away' ];
        }

        $login_data = Yii::$app->request->post();

        $user = User::find()->where( [ 'email' => $login_data['email'] ] )->andFilterWhere( [ 'active' => 1 ] )->one();

        if ( ! $user ) {
            return [ 'status' => 404, 'message' => 'User not found.' ];
        }

        if ( empty( $login_data['password'] ) ) {
            return [ 'status' => 401, 'message' => 'Password cannot be empty.' ];

        }

        if ( ! $this->validatePassword( $login_data['password'], $user ) ) {
            return [ 'status' => 404, 'message' => 'Password incorrect.' ];
        }

        Yii::$app->user->login( $user );
        Yii::$app->session->set( 'user_role', $user->user_role );

        return [ 'status' => 200, 'message' => '' ];
    }

    public function actionRestore() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        $user = User::find()->where( [ 'email' => $data['email'] ] )->andWhere( [ 'active' => 1 ] )->one();

        if ( ! $user ) {
            return [ 'status' => 404, 'message' => 'User not found.' ];
        }
        $token               = Yii::$app->security->generateRandomString( 10 );
        $user->restore       = 1;
        $user->restore_token = $token;
        if ( $error = $this->sendToken( $data['email'], $token, 'restore' ) !== true ) {
            return [ 'status' => 404, 'message' => $error ];
        }
        if ( $user->save() ) {
            return [ 'status' => 200, 'message' => 'Recovery link sent' ];
        }

        return [ 'status' => 404, 'message' => 'Unknown error' ];


    }

    public function actionRestoreconfirm() {
        if ( ! Yii::$app->request->get( 'code' ) ) {
            throw new \yii\web\NotFoundHttpException( "Go away" );
        }

        $user = User::find()->where( [ 'restore_token' => Yii::$app->request->get( 'code' ) ] )->andFilterWhere( [ 'restore' => 1 ] )->one();
        if ( ! $user ) {
            throw new \yii\web\NotFoundHttpException( "Go away" );
        }


        Yii::$app->session->set( 'restore_code', Yii::$app->request->get( 'code' ) );

        return Yii::$app->response->redirect( Url::to( '/login' ) );


    }

    public function actionChange() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ( ! Yii::$app->session->get( 'restore_code' ) ) {
            return [ 'status' => 404, 'message' => 'User not found.' ];
        }
        $user = User::find()->where( [ 'restore_token' => Yii::$app->session->get( 'restore_code' ) ] )->andFilterWhere( [ 'restore' => 1 ] )->one();

        if ( ! $user ) {
            return [ 'status' => 404, 'message' => 'User not found.' ];
        }

        $user->restore       = 0;
        $user->restore_token = '';
        $user->password      = Yii::$app->getSecurity()->generatePasswordHash( Yii::$app->request->post( 'new_pass' ) );

        if ( $user->save() ) {
            Yii::$app->session->remove( 'restore_code' );

            return [ 'status' => 200, 'message' => 'Password changed' ];
        }

        return [ 'status' => 404, 'message' => 'Unknown error' ];

    }

    public function actionConfirm() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data                       = Yii::$app->request->post();

        $user = User::find()->where( [ 'sign_up_token' => $data['token'] ] )->andFilterWhere( [ 'active' => 0 ] )->one();
        if ( ! $user ) {
            return [ 'status' => 404, 'message' => 'Code is incorrect' ];
        }
        $user->active        = 1;
        $user->sign_up_token = '';
        $user->save();
        if ( $user->save() ) {
            return [ 'status' => 200, 'message' => '' ];
        }

        return [ 'status' => 404, 'message' => 'Unknown error' ];

    }

    public function actionSignup() {

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ( ! Yii::$app->request->isPost ) {
            return [ 'status' => 401, 'error' => 'Go away' ];
        }

        $data = Yii::$app->request->post();

        $validator = new EmailValidator;

        if ( ! $validator->validate( $data['email'], $error ) ) {
            return [ 'status' => 404, 'message' => 'Invalid email' ];
        }

        $user = User::find()->where( [ 'email' => $data['email'] ] )->one();

        if ( $user ) {
            //todo: it's not a good idea
            return [ 'status' => 404, 'message' => 'User with the same email already exists.' ];
        }

        if ( empty( $data['name'] ) ) {
            return [ 'status' => 404, 'message' => 'Invalid name' ];
        }

        if ( empty( $data['password'] ) ) {
            return [ 'status' => 404, 'message' => 'Password must be set' ];
        }

        if ( empty( $data['terms'] ) ) {
            return [ 'status' => 404, 'message' => 'You have to accept the Terms & Conditions' ];
        }
        $token = Yii::$app->security->generateRandomString( 6 );
        $user  = [
            'first_name'    => $data['name'],
            'email'         => $data['email'],
            'password'      => Yii::$app->getSecurity()->generatePasswordHash( $data['password'] ),
            'sign_up_token' => $token,
            'active'        => 0,
            'news'          => (int) $data['news']
        ];


        $new             = new User();
        $new->attributes = $user;
        if ( $error = $this->sendToken( $data['email'], $token, 'signup' ) !== true ) {
            return [ 'status' => 404, 'message' => $error ];
        }
        $new->save();

        return [ 'status' => 200 ];
    }


    public function validatePassword( $password, $user ) {

        return Yii::$app->getSecurity()->validatePassword( $password, $user['password'] );
    }

    public function actionCurrentuser() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [ 'id' => Yii::$app->user->id ];
    }

    public function actionGetuserlang(  ) {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        return ['code' => $browser_lang, 'name' => $browser_lang];

    }


    public function actionLogout() {
        Yii::$app->user->logout();
        $this->redirect( 'login' );
    }

    private function sendToken( $email, $token, $type = 'signup' ) {

        if ( $type === 'restore' ) {

            $subject = 'Password restore';
            $url     = Url::base( true ) . '/restore?code=' . $token;
            $message = '<b>Click here: <a href="' . $url . '">restore</a></b>';

        } else {

            $subject = 'SignUp confirmation code';
            $message = '<b>Your code:' . $token . ' </b>';

        }
        try {
            Yii::$app->mailer->compose( 'html', ['content' => $message] )
                             ->setFrom( 'support@charity.local' )
                             ->setTo( $email )
                             ->setSubject( $subject )
                             ->setHtmlBody( $message )
                             ->send();

            return true;
        } catch ( \Swift_TransportException $e ) {
            return $e->getMessage();
        }

    }

}
