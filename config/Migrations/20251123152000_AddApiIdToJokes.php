<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\AdapterInterface;

class AddApiIdToJokes extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jokes');
        $table->addColumn('api_id', 'string', [
            'null' => true,
            'default' => null,
            'limit' => 255,
        ]);

        // Add a unique index on api_id to prevent duplicate saves from the API
        // Note: SQLite allows multiple NULLs, so manual entries without api_id won't be blocked.
        $table->addIndex(['api_id'], ['unique' => true, 'name' => 'UNQ_jokes_api_id']);

        $table->update();
    }
}
