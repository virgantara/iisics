<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "homecontent".
 *
 * @property int $page_id
 * @property string $page_title
 * @property string $page_slug
 * @property string $page_description
 * @property string $page_content
 * @property int $page_view
 * @property int $sequence
 */
class Homecontent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'homecontent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_title', 'page_description', 'page_content', 'sequence'], 'required'],
            [['page_description', 'page_content'], 'string'],
            [['page_view', 'sequence'], 'integer'],
            [['page_title', 'page_slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('app', 'Page ID'),
            'page_title' => Yii::t('app', 'Page Title'),
            'page_slug' => Yii::t('app', 'Page Slug'),
            'page_description' => Yii::t('app', 'Page Description'),
            'page_content' => Yii::t('app', 'Page Content'),
            'page_view' => Yii::t('app', 'Page View'),
            'sequence' => Yii::t('app', 'Sequence'),
        ];
    }
}
