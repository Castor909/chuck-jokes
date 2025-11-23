<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

class JokesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Jokes',
    ];

    public function setUp(): void
    {
        parent::setUp();
        // Ensure CSRF, Security components behave as in app
    }

    public function testIndex(): void
    {
        $this->get('/jokes');
        $this->assertResponseOk();
        // Fixture contains one record with punchline 'Lorem ipsum dolor sit amet'
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    public function testView(): void
    {
        $this->get('/jokes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    public function testDelete(): void
    {
        // Enable CSRF token and security token for postLink emulation
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // Ensure record exists
        $jokes = TableRegistry::getTableLocator()->get('Jokes');
        $this->assertNotNull($jokes->get(1));

        $this->post('/jokes/delete/1');
        $this->assertResponseSuccess();
        $this->assertRedirect(['controller' => 'Jokes', 'action' => 'index']);

        // After deletion it should not be found
        $this->expectException(\Cake\Datasource\Exception\RecordNotFoundException::class);
        $jokes->get(1);
    }

    public function testRandomSaveAndDuplicatePrevention(): void
    {
        // Simulate saving a joke coming from the API
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $post = [
            'joke' => 'A unique test joke',
            'api_id' => 'api-test-999',
        ];

        $this->post('/jokes/random', $post);
        $this->assertResponseSuccess();

        // Post same api_id again should show friendly error and not create new record
        $this->post('/jokes/random', $post);
        $this->assertResponseOk();
        $this->assertResponseContains('Este chiste ya fue guardado anteriormente.');
    }
}
