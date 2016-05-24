<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\db\Migration;
use yii\db\Query;
use yii\db\Schema;

class m141222_110026_update_ip_field extends Migration
{
    public function up()
    {
        $users = (new Query())->from('{{%user}}')->select('id, registration_ip ip')->all();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->addColumn('{{%user}}', 'ip2', Schema::TYPE_STRING . '(45) AFTER registration_ip');
            foreach ($users as $user) {
                if ($user['ip'] == null) {
                    continue;
                }
                Yii::$app->db->createCommand()->update('{{%user}}', [
                    'ip2' => long2ip($user['ip'])
                ], 'id = ' . $user['id'])->execute();
            }
            $this->dropColumn('{{%user}}', 'registration_ip');
            $this->renameColumn('{{%user}}', 'ip2', 'registration_ip');
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function down()
    {
        $users = (new Query())->from('{{%user}}')->select('id, registration_ip ip')->all();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->addColumn('{{%user}}', 'ip2', Schema::TYPE_BIGINT . ' AFTER registration_ip');
            foreach ($users as $user) {
                if ($user['ip'] == null)
                    continue;
                Yii::$app->db->createCommand()->update('{{%user}}', [
                    'ip2' => ip2long($user['ip'])
                ], 'id = ' . $user['id'])->execute();
            }
            $this->dropColumn('{{%user}}', 'registration_ip');
            $this->renameColumn('{{%user}}', 'ip2', 'registration_ip');
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
