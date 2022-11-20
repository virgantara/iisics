<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "system".
 *
 * @property int $sys_id
 * @property string $sys_name
 * @property string $sys_content
 */
class System extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys_name', 'sys_content'], 'required'],
            [['sys_name'], 'string', 'max' => 50],
            [['sys_content'], 'string', 'max' => 500],
            [['sys_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sys_id' => Yii::t('app', 'Sys ID'),
            'sys_name' => Yii::t('app', 'Sys Name'),
            'sys_content' => Yii::t('app', 'Sys Content'),
        ];
    }
}
