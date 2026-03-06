<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\ProductsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\ProductsController Test Case
 *
 * @uses \App\Controller\Api\ProductsController
 */
class ProductsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Products',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::index()
     */
    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::index()
     */
    public function testIndex(): void
    {
        $this->get('/api/products.json');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertNotEmpty($this->_getBodyAsString());
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertGreaterThan(0, count($response['data']));

        // Test pagination and sorting
        $this->get('/api/products.json?page=1&limit=1&sort=name&direction=desc');
        $this->assertResponseOk();
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertEquals(1, count($response['data']));
        $this->assertEquals('Product 2', $response['data'][0]['name']); // Assuming fixture data
    }


    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::view()
     */
    public function testView(): void
    {
        $this->get('/api/products/1.json');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(1, $response['data']['id']);

        // Test not found
        $this->get('/api/products/999.json');
        $this->assertResponseCode(404);
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertStringContainsString('Product not found', $response['message']);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::add()
     */
    public function testAdd(): void
    {
        $this->post('/api/products.json', [
            'name' => 'New Product',
            'description' => 'A brand new product',
            'price' => 25.99,
            'stock' => 50,
            'status' => 'active',
        ]);
        $this->assertResponseCode(201); // Created
        $this->assertContentType('application/json');
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('New Product', $response['data']['name']);

        // Test validation error
        $this->post('/api/products.json', [
            'name' => '', // Missing name
            'price' => -10, // Invalid price
        ]);
        $this->assertResponseCode(422); // Unprocessable Entity
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('name', $response['errors']);
        $this->assertArrayHasKey('price', $response['errors']);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::edit()
     */
    public function testEdit(): void
    {
        $this->put('/api/products/1.json', [
            'name' => 'Updated Product Name',
            'price' => 15.00,
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('Updated Product Name', $response['data']['name']);
        $this->assertEquals(15.00, $response['data']['price']);

        // Test not found
        $this->put('/api/products/999.json', ['name' => 'Non Existent']);
        $this->assertResponseCode(404);
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);

        // Test validation error
        $this->put('/api/products/1.json', [
            'name' => '', // Missing name
            'price' => -5, // Invalid price
        ]);
        $this->assertResponseCode(422);
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('name', $response['errors']);
        $this->assertArrayHasKey('price', $response['errors']);
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Api\ProductsController::delete()
     */
    public function testDelete(): void
    {
        $this->delete('/api/products/1.json');
        $this->assertResponseCode(204); // No Content
        $this->assertResponseEmpty();

        // Verify it's actually deleted
        $this->get('/api/products/1.json');
        $this->assertResponseCode(404);

        // Test not found
        $this->delete('/api/products/999.json');
        $this->assertResponseCode(404);
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);
    }
}
