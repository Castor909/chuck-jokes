<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JokesTable;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

class JokesTableTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    protected array $fixtures = [
        'app.Jokes',
    ];

    /**
     * @var \App\Model\Table\JokesTable
     */
    protected JokesTable $Jokes;

    public function setUp(): void
    {
        parent::setUp();
        $this->Jokes = TableRegistry::getTableLocator()->get('Jokes');
    }

    public function tearDown(): void
    {
        unset($this->Jokes);

        parent::tearDown();
    }

    public function testSaveWithApiIdUnique(): void
    {
        $data = [
            'setup' => '',
            'punchline' => 'Test punchline',
            'api_id' => 'unique-api-123',
        ];

        $entity = $this->Jokes->newEntity($data);
        $this->assertEmpty($entity->getErrors(), 'No validation errors expected for first save');

        $result = $this->Jokes->save($entity);
        $this->assertNotFalse($result, 'First save should succeed');

        // Attempt to save another with same api_id
        $data2 = [
            'setup' => '',
            'punchline' => 'Another punchline',
            'api_id' => 'unique-api-123',
        ];
        $entity2 = $this->Jokes->newEntity($data2);
        $save2 = $this->Jokes->save($entity2);

        $this->assertFalse($save2, 'Second save with same api_id should fail due to uniqueness rule');
        $errors = $entity2->getErrors();
        $this->assertArrayHasKey('api_id', $errors, 'api_id should have uniqueness error');
    }
}
