<?php
/**
 * @author Yuriy Basov <basowy@gmail.com>
 * @since 1.0.0
 */

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170425_191048_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(20),
            'name' => $this->string(100),
            'alias' => $this->string(45)->defaultValue(NULL)->unique(),
            'config' => $this->text()->defaultValue(NULL),
            'created_at' => $this->datetime()->defaultValue(NULL),
            'created_by' => $this->integer(20)->defaultValue(NULL),
            'updated_at' => $this->datetime()->defaultValue(NULL),
            'updated_by' => $this->integer(20)->defaultValue(NULL),
        ]);        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
