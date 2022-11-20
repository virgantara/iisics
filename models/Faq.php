<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $faq_id
 * @property string $faq_question
 * @property string $faq_answer
 * @property int $faq_sequence
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faq_question', 'faq_answer', 'faq_sequence'], 'required'],
            [['faq_answer'], 'string'],
            [['faq_sequence'], 'integer'],
            [['faq_question'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'faq_id' => Yii::t('app', 'Faq ID'),
            'faq_question' => Yii::t('app', 'Faq Question'),
            'faq_answer' => Yii::t('app', 'Faq Answer'),
            'faq_sequence' => Yii::t('app', 'Faq Sequence'),
        ];
    }
}
