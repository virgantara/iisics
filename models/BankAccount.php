<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_account".
 *
 * @property int $id
 * @property string|null $nama_bank
 * @property string|null $nomor_rekening
 * @property string|null $atas_nama
 * @property string|null $keterangan
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_bank'], 'string', 'max' => 100],
            [['nomor_rekening'], 'string', 'max' => 50],
            [['atas_nama'], 'string', 'max' => 255],
            [['keterangan'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_bank' => 'Nama Bank',
            'nomor_rekening' => 'Nomor Rekening',
            'atas_nama' => 'Atas Nama',
            'keterangan' => 'Keterangan',
        ];
    }
}
