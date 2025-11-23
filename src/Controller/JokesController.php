<?php
namespace App\Controller;

use Cake\Http\Client;

class JokesController extends AppController
{
    public function random()
    {
        $joke = null;
        $http = new Client();

        // Si se envía el formulario (Guardar)
        if ($this->request->is('post')) {
            $posted = $this->request->getData('joke');
            if (!empty($posted)) {
                $text = mb_substr($posted, 0, 255);

                $jokesTable = $this->fetchTable('Jokes');
                $entity = $jokesTable->newEmptyEntity();
                $entity = $jokesTable->patchEntity($entity, ['joke' => $text]);

                if ($jokesTable->save($entity)) {
                    $this->Flash->success(__('Chiste guardado correctamente.'));
                    return $this->redirect(['action' => 'random']);
                }
                $this->Flash->error(__('No se pudo guardar el chiste.'));
            } else {
                $this->Flash->error(__('No hay texto para guardar.'));
            }
        } else {
            // GET: pedimos un chiste a la API
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