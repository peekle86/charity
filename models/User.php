<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string $password
 * @property string|null $avatar
 * @property string|null $created_date
 * @property string|null $updated_date
 * @property int|null $restore
 * @property string|null $restore_token
 * @property int $user_role
 * @property int|null $status
 * @property string|null $sign_up_token
 * @property int|null $active
 * @property-read mixed $authKey
 * @property int|null $news
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['avatar'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['restore', 'user_role', 'status', 'active', 'news'], 'integer'],
            [['first_name', 'last_name', 'email', 'restore_token', 'sign_up_token'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 150],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'avatar' => Yii::t('app', 'Avatar'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'restore' => Yii::t('app', 'Restore'),
            'restore_token' => Yii::t('app', 'Restore Token'),
            'user_role' => Yii::t('app', 'User Role'),
            'status' => Yii::t('app', 'Status'),
            'sign_up_token' => Yii::t('app', 'Sign Up Token'),
            'active' => Yii::t('app', 'Active'),
            'news' => Yii::t('app', 'News'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'first_name',
            'last_name',
            'avatar',
            'email',
            'user_role',
            'status',
            'active'
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
