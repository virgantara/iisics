<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $text
 * @property string $link
 * @property int $sequence
 * @property int $parent_id
 * @property int $new_window
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'link', 'sequence', 'parent_id', 'new_window'], 'required'],
            [['sequence', 'parent_id', 'new_window'], 'integer'],
            [['text', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'link' => Yii::t('app', 'Link'),
            'sequence' => Yii::t('app', 'Sequence'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'new_window' => Yii::t('app', 'New Window'),
        ];
    }
}
