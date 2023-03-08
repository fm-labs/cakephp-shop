<?php
declare(strict_types=1);

namespace Shop\Core\Cart\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Class AddToCartForm
 *
 * @package Shop\Core\Cart\Form
 */
class AddToCartForm extends Form
{
    /**
     * @param \Cake\Form\Schema $schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        $schema->addField('amount', ['type' => 'string']);
        $schema->addField('refid', ['type' => 'string']);
        $schema->addField('refscope', ['type' => 'string']);

        return $schema;
    }

    /**
     * @param \Cake\Validation\Validator $validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('amount')
            ->notEmptyString('amount')

            ->requirePresence('refid')
            ->notEmptyString('refid')

            ->requirePresence('refscope')
            ->notEmptyString('refscope');

        return $validator;
    }

    /**
     * @param array $data
     * @return bool|void
     */
    protected function _execute(array $data): bool
    {
    }
}
