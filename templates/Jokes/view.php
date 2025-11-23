<?php
/**
 * @var \App\Model\Entity\Joke $joke
 */
?>
<h1>Detalle del chiste</h1>

<article>
    <?php if (!empty($joke->setup)): ?>
        <p><strong><?= __('Setup:') ?></strong> <?= h($joke->setup) ?></p>
    <?php endif; ?>

    <p><strong><?= __('Punchline:') ?></strong> <?= h($joke->punchline) ?></p>

    <p><small><?= __('Creado:') ?> <?= h($joke->created) ?></small></p>
</article>

<?= $this->Html->link(__('Volver a la lista'), ['action' => 'index']) ?> |
<?= $this->Form->postLink(__('Borrar este chiste'), ['action' => 'delete', $joke->id], ['confirm' => __('¿Seguro que quieres borrar # {0}?', $joke->id)]) ?>
