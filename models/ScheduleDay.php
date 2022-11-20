<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule_day".
 *
 * @property int $id
 * @property string|null $day_name
 * @property int $sequence
 *
 * @property ScheduleTime[] $scheduleTimes
 */
class ScheduleDay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule_day';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence'], 'required'],
            [['sequence'], 'integer'],
            [['day_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'day_name' => Yii::t('app', 'Day Name'),
            'sequence' => Yii::t('app', 'Sequence'),
        ];
    }

    /**
     * Gets query for [[ScheduleTimes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScheduleTimes()
    {
        return $this->hasMany(ScheduleTime::className(), ['day_id' => 'id'])->orderBy(['waktu_mulai'=>SORT_ASC]);
    }
}
