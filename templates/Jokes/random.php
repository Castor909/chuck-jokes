<?php
/**
 * @var string|null $joke
 */
?>
<h1>Chiste aleatorio</h1>

<?php if (!empty($joke)): ?>
    <blockquote>
        <?= h($joke) ?>
    </blockquote>

    <?= $this->Form->create(null, ['type' => 'post']) ?>
    <?= $this->Form->hidden('setup', ['value' => '']) ?>
    <?= $this->Form->hidden('punchline', ['value' => $joke]) ?>
    <?= $this->Form->hidden('joke', ['value' => $joke]) ?>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
<?php else: ?>
    <p>No hay chiste disponible.</p>
<?php endif; ?>