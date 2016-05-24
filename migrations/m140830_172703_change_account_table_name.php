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

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class m140830_172703_change_account_table_name extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_user_account', '{{%account}}');
        $this->renameTable('{{%account}}', '{{%social_account}}');
        $this->addForeignKey('fk_user_account', '{{%social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_account', '{{%social_account}}');
        $this->renameTable('{{%social_account}}', '{{%account}}');
        $this->addForeignKey('fk_user_account', '{{%account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }
}
