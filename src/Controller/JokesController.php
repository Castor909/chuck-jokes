<?php
namespace App\Controller;

use Cake\Http\Client;
use Cake\Log\Log;

class JokesController extends AppController
{
    public function random()
    {
        $joke = null;
        $http = new Client();

        if ($this->request->is('post')) {
            $posted = $this->request->getData('joke');
            if (!empty($posted)) {
                $text = mb_substr($posted, 0, 255);

                $jokesTable = $this->fetchTable('Jokes');
                $entity = $jokesTable->newEmptyEntity();

                // Construir los campos que la entidad/validación esperan
                $data = [
                    'setup' => '',            // mantener vacío o poner un valor si se desea
                    'punchline' => $text,
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
                    $this->Flash->error(__('No se pudo guardar el chiste. Errores: {0}', json_encode($errors)));
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
                } else {
                    $this->Flash->error(__('Respuesta inválida de la API.'));
                }
            } else {
                $this->Flash->error(__('No se pudo obtener chiste desde la API.'));
            }
        }

        $this->set(compact('joke'));
    }
}