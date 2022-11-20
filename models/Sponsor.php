<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sponsor".
 *
 * @property int $id
 * @property string $sponsor_name
 * @property string $sponsor_role
 * @property int $sequence
 * @property string $file_path
 */
class Sponsor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_name', 'sponsor_role', 'sequence'], 'required'],
            [['sequence'], 'integer'],
            [['sponsor_name', 'sponsor_role'], 'string', 'max' => 255],
            [['file_path'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','jpeg','png'], 'maxSize' => 1024 * 1024 * 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sponsor_name' => Yii::t('app', 'Sponsor Name'),
            'sponsor_role' => Yii::t('app', 'Sponsor Role'),
            'sequence' => Yii::t('app', 'Sequence'),
            'file_path' => Yii::t('app', 'Logo'),
        ];
    }
}
