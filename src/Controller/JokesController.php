<?php
namespace App\Controller;

use Cake\Http\Client;
use Cake\Log\Log;

class JokesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('FormProtection');
    }

    public function random()
    {
        $joke = null;
        $apiId = null;
        $http = new Client();

        if ($this->request->is('post')) {
            $posted = $this->request->getData('joke');
            if (!empty($posted)) {
                $text = mb_substr($posted, 0, 255);
                $postedApiId = $this->request->getData('api_id');

                $jokesTable = $this->fetchTable('Jokes');
                $entity = $jokesTable->newEmptyEntity();

                // Construir los campos que la entidad/validación esperan
                $data = [
                    'setup' => '',            // mantener vacío o poner un valor si se desea
                    'punchline' => $text,
                    'api_id' => $postedApiId ?: null,
                ];

                $entity = $jokesTable->patchEntity($entity, $data);

                if ($jokesTable->save($entity)) {
                    $this->Flash->success(__('Chiste guardado correctamente.'));
                    return $this->redirect(['action' => 'random']);
                }

                // Nuevo: registrar y mostrar errores de validación/entidad
                $errors = $entity->getErrors();
                Log::warning('Joke save failed: ' . json_encode($errors));
                if (!empty($errors)) {
                    if (isset($errors['api_id'])) {
                        $this->Flash->error(__('Este chiste ya fue guardado anteriormente.'));
                    } else {
                        $this->Flash->error(__('No se pudo guardar el chiste. Errores: {0}', json_encode($errors)));
                    }
                } else {
                    $this->Flash->error(__('No se pudo guardar el chiste.'));
                }
            } else {
                $this->Flash->error(__('No hay texto para guardar.'));
            }
        } else {
            // GET...
            $response = $http->get('https://api.chucknorris.io/jokes/random');
            if ($response->isOk()) {
                $data = $response->getJson();
                if (!empty($data['value'])) {
                    $joke = $data['value'];
                    $apiId = $data['id'] ?? null;
                } else {
                    $this->Flash->error(__('Respuesta inválida de la API.'));
                }
            } else {
                $this->Flash->error(__('No se pudo obtener chiste desde la API.'));
            }
        }

        $this->set(compact('joke', 'apiId'));
    }

    public function index()
    {
        $jokesTable = $this->fetchTable('Jokes');
        $query = $jokesTable->find()
            ->select(['id', 'setup', 'punchline', 'created'])
            ->order(['id' => 'DESC']);

        $jokes = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('jokes'));
    }

    public function view($id = null)
    {
        if ($id === null) {
            throw new \Cake\Http\Exception\NotFoundException(__('Chiste no encontrado'));
        }

        $jokesTable = $this->fetchTable('Jokes');
        $joke = $jokesTable->get($id);

        $this->set(compact('joke'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $jokesTable = $this->fetchTable('Jokes');
        $joke = $jokesTable->get($id);

        if ($jokesTable->delete($joke)) {
            $this->Flash->success(__('Chiste borrado correctamente.'));
        } else {
            $this->Flash->error(__('No se pudo borrar el chiste.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}