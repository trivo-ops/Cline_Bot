<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Core\Configure;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'message', 'errors', 'pagination']);
        $this->Authentication->allowUnauthenticated(['index', 'view']); // Adjust as per auth requirements
    }

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(EventInterface $event): ?\Cake\Http\Response
    {
        parent::beforeFilter($event);
        // For API, disable CakePHP's default security components like CSRF
        $this->FormProtection->setConfig('unlockedActions', ['add', 'edit', 'delete']);

        return null;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $query = $this->Products->find();

        // Pagination
        $limit = $this->request->getQuery('limit', Configure::read('Pagination.limit', 20)); // configurable limit
        $page = $this->request->getQuery('page', 1);

        $this->paginate = [
            'limit' => $limit,
            'page' => $page,
            'contain' => [],
        ];

        // Sorting
        $sortAllowlist = ['id', 'name', 'price', 'stock', 'created', 'modified'];
        $sort = $this->request->getQuery('sort');
        $direction = $this->request->getQuery('direction');

        if (in_array($sort, $sortAllowlist) && in_array(strtolower($direction), ['asc', 'desc'])) { // only ASC/DESC allowed
            $this->paginate['order'] = [$sort => $direction];
        } else {
            $this->paginate['order'] = ['id' => 'asc'];
        }

        $products = $this->paginate($query);
        $paginate = $this->Paginator->getPaginator()->getCurrent();

        $this->set([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully',
            'pagination' => [
                'page' => $paginate['page'],
                'limit' => $paginate['perPage'], // perPage is the actual limit used
                'total' => $paginate['count'],
                'pages' => $paginate['pageCount'],
            ],
            '_serialize' => ['success', 'data', 'message', 'pagination'],
            'errors' => new \stdClass(), // Ensure errors key is always present
        ]);
    }


    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);
        try {
            $product = $this->Products->get($id, ['contain' => []]);
            $this->set([
                'success' => true,
                'data' => $product,
                'message' => 'Product retrieved successfully',
                '_serialize' => ['success', 'data', 'message'],
                'errors' => new \stdClass(),
            ]);
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => $e->getMessage(),
                'errors' => ['id' => 'Product not found'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $product = $this->Products->newEmptyEntity();

        try {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->response = $this->response->withStatus(201); // 201 Created
                $this->set([
                    'success' => true,
                    'data' => $product,
                    'message' => 'Product created successfully',
                    '_serialize' => ['success', 'data', 'message'],
                    'errors' => new \stdClass(),
                ]);
            } else {
                $this->response = $this->response->withStatus(422); // 422 Unprocessable Entity
                $this->set([
                    'success' => false,
                    'data' => $product->toArray(),
                    'message' => 'Validation errors occurred',
                    'errors' => $product->getErrors(),
                    '_serialize' => ['success', 'data', 'message', 'errors'],
                ]);
            }
        } catch (PersistenceFailedException $e) {
            $this->response = $this->response->withStatus(422); // 422 Unprocessable Entity
            $this->set([
                'success' => false,
                'data' => $e->getEntity()->toArray(),
                'message' => 'Validation errors occurred',
                'errors' => $e->getEntity()->getErrors(),
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        } catch (\Exception $e) {
            $this->response = $this->response->withStatus(500); // 500 Internal Server Error
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'errors' => ['_error' => 'Internal Server Error'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['put', 'post']); // PUT for RESTful, POST for browser compatibility (if needed)

        try {
            $product = $this->Products->get($id);
            $product = $this->Products->patchEntity($product, $this->request->getData());

            if ($this->Products->save($product)) {
                $this->set([
                    'success' => true,
                    'data' => $product,
                    'message' => 'Product updated successfully',
                    '_serialize' => ['success', 'data', 'message'],
                    'errors' => new \stdClass(),
                ]);
            } else {
                $this->response = $this->response->withStatus(422); // 422 Unprocessable Entity
                $this->set([
                    'success' => false,
                    'data' => $product->toArray(),
                    'message' => 'Validation errors occurred',
                    'errors' => $product->getErrors(),
                    '_serialize' => ['success', 'data', 'message', 'errors'],
                ]);
            }
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => $e->getMessage(),
                'errors' => ['id' => 'Product not found'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        } catch (PersistenceFailedException $e) {
            $this->response = $this->response->withStatus(422); // 422 Unprocessable Entity
            $this->set([
                'success' => false,
                'data' => $e->getEntity()->toArray(),
                'message' => 'Validation errors occurred',
                'errors' => $e->getEntity()->getErrors(),
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        } catch (\Exception $e) {
            $this->response = $this->response->withStatus(500); // 500 Internal Server Error
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'errors' => ['_error' => 'Internal Server Error'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);

        try {
            $product = $this->Products->get($id);
            if ($this->Products->delete($product)) {
                $this->response = $this->response->withStatus(204); // 204 No Content
                $this->set([
                    'success' => true,
                    'data' => new \stdClass(),
                    'message' => 'Product deleted successfully',
                    '_serialize' => ['success', 'data', 'message'],
                    'errors' => new \stdClass(),
                ]);
            } else {
                $this->response = $this->response->withStatus(500); // 500 Internal Server Error in case of DB error
                $this->set([
                    'success' => false,
                    'data' => new \stdClass(),
                    'message' => 'Product could not be deleted. Please try again.',
                    'errors' => ['_error' => 'Deletion failed'],
                    '_serialize' => ['success', 'data', 'message', 'errors'],
                ]);
            }
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => $e->getMessage(),
                'errors' => ['id' => 'Product not found'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        } catch (\Exception $e) {
            $this->response = $this->response->withStatus(500); // 500 Internal Server Error
            $this->set([
                'success' => false,
                'data' => new \stdClass(),
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'errors' => ['_error' => 'Internal Server Error'],
                '_serialize' => ['success', 'data', 'message', 'errors'],
            ]);
        }
    }
}
