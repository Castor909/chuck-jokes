<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Jokes Model
 *
 * @method \App\Model\Entity\Joke newEmptyEntity()
 * @method \App\Model\Entity\Joke newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Joke> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Joke get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Joke findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Joke patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Joke> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Joke|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Joke saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Joke>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joke>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joke>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joke> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joke>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joke>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joke>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joke> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class JokesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('jokes');
        $this->setDisplayField('setup');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('setup')
            ->maxLength('setup', 255)
            ->requirePresence('setup', 'create')
            ->allowEmptyString('setup');

        $validator
            ->scalar('punchline')
            ->maxLength('punchline', 255)
            ->requirePresence('punchline', 'create')
            ->allowEmptyString('punchline');

        $validator
            ->scalar('api_id')
            ->maxLength('api_id', 255)
            ->allowEmptyString('api_id');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Ensure api_id is unique when provided
        $rules->add($rules->isUnique(['api_id'], 'El id de la API ya existe.'));

        return $rules;
    }
}
