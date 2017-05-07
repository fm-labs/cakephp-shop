<?php
namespace Shop\Model\Table;

use Cake\Core\Plugin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopCategory;

/**
 * ShopCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentShopCategories
 * @property \Cake\ORM\Association\HasMany $ChildShopCategories
 * @property \Cake\ORM\Association\HasMany $ShopProducts
 */
class ShopCategoriesTable extends Table
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

        $this->table('shop_categories');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('ParentShopCategories', [
            'className' => 'Shop.ShopCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildShopCategories', [
            'className' => 'Shop.ShopCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ShopProducts', [
            'foreignKey' => 'shop_category_id',
            'className' => 'Shop.ShopProducts',
        ]);
        $this->hasMany('ShopTexts', [
            'foreignKey' => 'model_id',
            'className' => 'Shop.ShopTexts',
            'conditions' => ['ShopTexts.model' => 'Shop.ShopCategories']
        ]);

        $this->belongsToMany('ShopTags', [
            //'foreignKey' => 'shop_tag_id',
            'className' => 'Shop.ShopTags',
            'propertyName' => 'tags',
            'joinTable' => 'shop_categories_tags',
        ]);
        $this->addBehavior('Content.ContentModule', [
            'alias' => 'ContentModules',
            'scope' => 'Shop.ShopCategories'
        ]);
        /*
        $this->addBehavior('Attachment.Attachment', [
            'dataDir' =>  MEDIA . 'gallery' . DS,
            'dataUrl' => MEDIA_URL . '/gallery',
            'fields' => [
                'preview_image_file' => [
                ],
                'featured_image_file' => [
                    //'multiple' => true
                ]
            ]
        ]);
        */

        if (Plugin::loaded('Media')) {
            $this->addBehavior('Media.Media', [
                'model' => 'Shop.ShopCategories',
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
                    ],
                    'media_images' => [
                        'mode' => 'table',
                        'config' => 'shop',
                        'multiple' => true
                    ],
                    'custom_file1' => [
                        'config' => 'shop'
                    ],
                    'custom_file2' => [
                        'config' => 'shop'
                    ],
                    'custom_file3' => [
                        'config' => 'shop'
                    ],
                    'custom_file4' => [
                        'config' => 'shop'
                    ],
                    'custom_file5' => [
                        'config' => 'shop'
                    ]
                ]
            ]);
        }

        //$this->addBehavior('Eav.Attributes');

        $this->addBehavior('Banana.Sluggable', [
            'field' => 'name'
        ]);
        $this->addBehavior('Banana.Publishable');

        $this->addBehavior('Tree.Tree', [
            'level' => 'level'
        ]);

        $this->addBehavior('Translate', [
            'fields' => ['name', 'slug', 'desc_html', 'teaser_html'],
            'translationTable' => 'ShopI18n'
        ]);
        //$this->locale('de');

        if (Plugin::loaded('Search')) {
            $this->addBehavior('Search.Search');
            $this->searchManager()
                ->add('name', 'Search.Like', [
                    'before' => true,
                    'after' => true,
                    'fieldMode' => 'OR',
                    'comparison' => 'LIKE',
                    'wildcardAny' => '*',
                    'wildcardOne' => '?',
                    'field' => ['title']
                ])
                ->value('is_published', [
                    'filterEmpty' => true
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
            ->allowEmpty('id', 'create');

        $validator
            ->add('parent_id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('parent_id');

        $validator
            ->allowEmpty('lft');

        $validator
            ->allowEmpty('rght');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('preview_image_file');

        $validator
            ->allowEmpty('featured_image_file');

        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_published', 'create')
            ->notEmpty('is_published');

        return $validator;
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
        //$rules->add($rules->existsIn(['parent_id'], 'ParentShopCategories'));
        return $rules;
    }
}
