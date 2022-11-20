<?php

namespace tests\unit\models;

use Yii;

use app\models\User;
use app\models\DataDiri;

class UserTest extends \Codeception\Test\Unit
{
    // public function testFindUserById()
    // {
    //     expect_that($user = User::findIdentity(100));
    //     expect($user->username)->equals('admin');

    //     expect_not(User::findIdentity(999));
    // }

    // public function testFindUserByAccessToken()
    // {
    //     expect_that($user = User::findIdentityByAccessToken('100-token'));
    //     expect($user->username)->equals('admin');

    //     expect_not(User::findIdentityByAccessToken('non-existing'));        
    // }

    public function testCreateUser()
    {
        $model = new User();
        $model->scenario = 'sce_user';
        $dataDiri = new DataDiri();

        $model->attributes = [
            'NIY' => '1234',
            'email' => 'tester@example.com',
            'username' => '1234',
            'nama' => 'testing nama',
            'id_prod' => 4,
            // 'status' =>'Active',
            // 'created_at' => 123455,
            // 'updated_at' => 123455,
            // 'password_hash' => Yii::$app->security->generatePasswordHash('testing')
        ];

        $dataDiri->attributes = [
            'NIY' => '1234',
            'nama' => 'testing_nama',
            'gender' => 'Laki-laki',
            'tempat_lahir' => 'test tempat lahir',
            'tanggal_lahir' => '1988-01-01',
        ];

        // $this->assertTrue($model->validate());
        $this->assertFalse($model->validate());
        $this->assertFalse($dataDiri->validate());
    }

    // public function testFindUserByUsername()
    // {
    //     expect_that($user = User::findByUsername('160589'));
    //     expect_not(User::findByUsername('not-160589'));
    // }

    /**
     * @depends testFindUserByUsername
     */
    // public function testValidateUser($user)
    // {
    //     $user = User::findByUsername('admin');
    //     expect_that($user->validateAuthKey('test100key'));
    //     expect_not($user->validateAuthKey('test102key'));

    //     expect_that($user->validatePassword('admin'));
    //     expect_not($user->validatePassword('123456'));        
    // }

}
