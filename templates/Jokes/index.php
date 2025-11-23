<?php
/**
 * @var \Cake\Datasource\ResultSetInterface|iterable $jokes
 */
?>
<h1>Chistes guardados</h1>

<?php if (!empty($jokes) && !$jokes->isEmpty()): ?>
    <ul>
        <?php foreach ($jokes as $j): ?>
            <li>
                <?= $this->Html->link(h($j->punchline), ['action' => 'view', $j->id]) ?>
                <small> — <?= h($j->created) ?></small>
                |
                <?= $this->Form->postLink(__('Borrar'), ['action' => 'delete', $j->id], ['confirm' => __('¿Seguro que quieres borrar # {0}?', $j->id)]) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev(__('« Anterior'), ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Siguiente »'), ['escape' => false]) ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registros (de {{count}})')) ?></p>
    </div>
<?php else: ?>
    <p>No hay chistes guardados.</p>
<?php endif; ?>

<?= $this->Html->link(__('Volver'), ['controller' => 'Pages', 'action' => 'home']) ?>
