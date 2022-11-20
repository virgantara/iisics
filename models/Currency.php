<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property string|null $name
 * @property string|null $code
 * @property string|null $symbol
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 3],
            [['symbol'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'code' => 'Code',
            'symbol' => 'Symbol',
        ];
    }
}
