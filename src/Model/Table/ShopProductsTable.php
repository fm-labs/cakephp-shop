<?php
namespace Shop\Model\Table;

use Cake\Collection\Iterator\MapReduce;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\ORM\Exception\RolledbackTransactionException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopProduct;
use User\Controller\Component\AuthComponent;

/**
 * ShopProducts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCategories
 * @property \Search\Manager $searchManager
 */
class ShopProductsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('shop_products');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('ShopCategories', [
            'className' => 'Shop.ShopCategories',
            'foreignKey' => 'shop_category_id',
        ]);

        $this->belongsTo('ParentShopProducts', [
            'className' => 'Shop.ShopProducts',
            'foreignKey' => 'parent_id',
        ]);

        $this->hasMany('ChildShopProducts', [
            'className' => 'Shop.ShopProducts',
            'foreignKey' => 'parent_id'
        ]);

        /*
        $this->addBehavior('Attachment.Attachment', [
            'dataDir' =>  MEDIA . 'shop' . DS,
            'dataUrl' => MEDIA_URL . '/shop',
            'fields' => [
                'media_images' => [
                    'multiple' => true,
                    'i18n' => true,
                ]
            ]
        ]);
        */

        if (Plugin::loaded('Media')) {
            $this->addBehavior('Media.Media', [
                'fields' => [
                    'preview_image_file' => [
                        'config' => 'shop'
                    ],
                    'featured_image_file' => [
                        'config' => 'shop'
                    ],
                    'image_files' => [
                        'config' => 'shop',
                        'multiple' => true
                    ]
                ]
            ]);
        }

        $this->addBehavior('Banana.Sluggable', [
            'field' => 'title'
        ]);

        //$this->addBehavior('Eav.Attributes');

        $this->addBehavior('Banana.Publishable');

        $this->addBehavior('Translate', [
            'fields' => ['title', 'slug', 'desc_long_text', 'desc_short_text'],
            'translationTable' => 'ShopI18n'
        ]);

        if (Plugin::loaded('Search')) {
            $this->addBehavior('Search.Search');
            $this->searchManager()
                ->add('title', 'Search.Like', [
                    'before' => true,
                    'after' => true,
                    'fieldMode' => 'OR',
                    'comparison' => 'LIKE',
                    'wildcardAny' => '*',
                    'wildcardOne' => '?',
                    'field' => ['title']
                ])
                ->add('sku', 'Search.Like', [
                    'before' => false,
                    'after' => false,
                    'fieldMode' => 'OR',
                    'comparison' => 'LIKE',
                    'wildcardAny' => '*',
                    'wildcardOne' => '?',
                    'field' => ['sku']
                ])
                ->value('shop_category_id', [
                    'filterEmpty' => true
                ])
                ->boolean('is_buyable', [
                    'filterEmpty' => true
                ])
                ->boolean('is_published', [
                    'filterEmpty' => true,
                ]);
        }

    }

    protected function _initializeSchema(\Cake\Database\Schema\Table $schema)
    {
        $schema->columnType('image_files', 'media_file');
        return $schema;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->allowEmpty('sku');
            //->add('sku', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']); //@TODO

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('slug');

        $validator
            ->allowEmpty('desc_short_text');

        $validator
            ->allowEmpty('desc_long_text');

        $validator
            ->allowEmpty('preview_image_file');

        $validator
            ->allowEmpty('featured_image_file');

        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_published', 'create')
            ->notEmpty('is_published');

        $validator
            ->add('publish_start_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('publish_start_date');

        $validator
            ->add('publish_end_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('publish_end_date');

        return $validator;
    }

    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param Event $event
     * @param Query $query
     * @param array $options
     * @param $primary
     */
    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param Event $event
     * @param Query $query
     * @param array $options
     * @param $primary
     */
    public function beforeFind(Event $event, Query $query, $options, $primary)
    {
        //if (!isset($options['skip_price']) || $options['skip_price'] === false) {
        //    return;
        //}

        $mapper = function ($row, $key, MapReduce $mapReduce) use ($options) {

            $row['price_net_original'] = $row['price_net'];
            //$row['price_net'] = $row['price_net'];

            if (Shop::config('Shop.CustomerDiscounts.enabled') == true && isset($options['for_customer'])) {
                $ShopCustomerDiscounts = TableRegistry::get('Shop.ShopCustomerDiscounts');
                $userDiscounts = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $options['for_customer'],
                    'shop_product_id' => $row['id'],
                    'published' => true
                ])->all();

                //@TODO Implement customer discount price mod
            }

            $mapReduce->emitIntermediate($row, $key);
        };

        $reducer = function ($bucket, $name, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['shop_category_id'], 'ShopCategories'));
        return $rules;
    }

    public function findProduct(Query $query) {
        //$query->find('media');
        $query->find('all', ['media' => true]);
        return $query;
    }

    public function add(ShopProduct $entity, array $data = [])
    {
        if ($data) {
            $this->patchEntity($entity, $data);
        }
        return $this->save($entity);
    }

    public function edit(ShopProduct $entity, array $data = [])
    {
        try {
            if ($data) {
                $this->patchEntity($entity, $data);
            }
            return $this->save($entity);

        } catch (RolledbackTransactionException $ex) {
            return false;
        }
    }

    /**
     * @param $id
     * @return Query
     */
    public function findPublishedChildren($id)
    {
        return $this->find()
            ->where(['parent_id' => $id, 'is_published' => true]);
    }
}
