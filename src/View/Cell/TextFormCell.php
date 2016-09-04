<?php
namespace Shop\View\Cell;

use Cake\View\Cell;
use Shop\Model\Table\TextsTable;

/**
 * TextForm cell
 *
 * @property TextsTable $Texts
 */
class TextFormCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($model = null, $modelId = null, $modelScope = null, $class = null)
    {
        $this->Texts = $this->loadModel('Shop.Texts');

        debug($model);
        if (is_array($model) && isset($model[0])) {
            extract($model, EXTR_IF_EXISTS);
        }


        $entity = $this->Texts->newEntity();
        $this->set('entity', $entity);
    }
}
