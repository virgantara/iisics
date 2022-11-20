<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule_time".
 *
 * @property int $id
 * @property string $waktu_mulai
 * @property string|null $waktu_selesai
 * @property string $agenda
 * @property string $description
 * @property int $day_id
 *
 * @property ScheduleDay $day
 */
class ScheduleTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waktu_mulai', 'agenda', 'description', 'day_id'], 'required'],
            [['waktu_mulai', 'waktu_selesai'], 'safe'],
            [['day_id'], 'integer'],
            [['agenda', 'description'], 'string', 'max' => 500],
            [['day_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScheduleDay::className(), 'targetAttribute' => ['day_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'waktu_mulai' => Yii::t('app', 'Waktu Mulai'),
            'waktu_selesai' => Yii::t('app', 'Waktu Selesai'),
            'agenda' => Yii::t('app', 'Agenda'),
            'description' => Yii::t('app', 'Description'),
            'day_id' => Yii::t('app', 'Day'),
        ];
    }

    /**
     * Gets query for [[Day]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDay()
    {
        return $this->hasOne(ScheduleDay::className(), ['id' => 'day_id']);
    }
}
