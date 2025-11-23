<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * JokesFixture
 */
class JokesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'setup' => 'Lorem ipsum dolor sit amet',
                'punchline' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-11-23 14:00:29',
                'modified' => '2025-11-23 14:00:29',
            ],
        ];
        parent::init();
    }
}
