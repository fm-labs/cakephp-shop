<?php

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
     * @param Schema $schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('amount', ['type' => 'string']);
        $schema->addField('refid', ['type' => 'string']);
        $schema->addField('refscope', ['type' => 'string']);

        return $schema;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('amount')
            ->notEmpty('amount')

            ->requirePresence('refid')
            ->notEmpty('refid')

            ->requirePresence('refscope')
            ->notEmpty('refscope');

        return $validator;
    }

    /**
     * @param array $data
     * @return bool|void
     */
    protected function _execute(array $data)
    {
    }
}
