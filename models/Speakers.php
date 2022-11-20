<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "speakers".
 *
 * @property int $speaker_id
 * @property string $speaker_name
 * @property string $speaker_slug
 * @property string $speaker_type
 * @property string $speaker_institution
 * @property string $speaker_content
 * @property string $speaker_image
 * @property int $sequence
 */
class Speakers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'speakers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['speaker_name', 'speaker_slug', 'speaker_institution', 'speaker_content', 'sequence'], 'required'],
            [['speaker_type', 'speaker_content'], 'string'],
            [['sequence'], 'integer'],
            [['speaker_name', 'speaker_slug'], 'string', 'max' => 100],
            [['speaker_institution'], 'string', 'max' => 255],
            [['speaker_image'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','jpeg','png'], 'maxSize' => 1024 * 1024 * 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'speaker_id' => Yii::t('app', 'ID'),
            'speaker_name' => Yii::t('app', 'Name'),
            'speaker_slug' => Yii::t('app', 'Speaker Slug'),
            'speaker_type' => Yii::t('app', 'Speaker Type'),
            'speaker_institution' => Yii::t('app', 'Institution'),
            'speaker_content' => Yii::t('app', 'Content'),
            'speaker_image' => Yii::t('app', 'Speaker Image'),
            'sequence' => Yii::t('app', 'Sequence'),
        ];
    }
}
