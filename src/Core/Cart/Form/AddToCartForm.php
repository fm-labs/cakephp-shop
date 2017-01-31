<?php

namespace Shop\Core\Cart\Form;


use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class AddToCartForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('amount', ['type' => 'string']);
        $schema->addField('refid', ['type' => 'string']);
        $schema->addField('refscope', ['type' => 'string']);
        return $schema;
    }

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

    protected function _execute(array $data)
    {
        debug($data);
        if ($this->validate($data)) {

        }
    }
}