<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_role}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $permissions
 * @property int|null $status
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permissions'], 'safe'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'permissions' => Yii::t('app', 'Permissions'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserRoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserRoleQuery(get_called_class());
    }
}
