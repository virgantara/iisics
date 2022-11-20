<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "topics".
 *
 * @property int $topic_id
 * @property string $topic_title
 */
class Topics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['topic_title'], 'required'],
            [['topic_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'topic_id' => Yii::t('app', 'Topic ID'),
            'topic_title' => Yii::t('app', 'Topic Title'),
        ];
    }
}
