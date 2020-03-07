<?php
namespace Shop\Model\Table;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Core\Plugin;
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Seo\Sitemap\SitemapLocation;
use Shop\Model\Entity\ShopCategory;

/**
 * ShopCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentShopCategories
 * @property \Cake\ORM\Association\HasMany $ChildShopCategories
 * @property \Cake\ORM\Association\HasMany $ShopProducts
 *
 * @method string|null locale(string $locale)
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

        $this->setTable('shop_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

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

        if (Plugin::isLoaded('Media')) {
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
                        'config' => 'default'
                    ],
                    'custom_file2' => [
                        'config' => 'default'
                    ],
                    'custom_file3' => [
                        'config' => 'default'
                    ],
                    'custom_file4' => [
                        'config' => 'default'
                    ],
                    'custom_file5' => [
                        'config' => 'default'
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
        //$this->setLocale('de');

        if (Plugin::isLoaded('Search')) {
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

    protected function _initializeSchema(TableSchema $schema)
    {
        //$schema->setColumnType('preview_image_file', 'media_file');
        //$schema->setColumnType('featured_image_file', 'media_file');
        //$schema->setColumnType('image_files', 'media_file');

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

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        Cache::clear(false, 'content_menu');
    }

    /**
     * @return Collection
     */
    public function findSitemap()
    {
        $locations = [];
        $categories = $this->find('published')->find('threaded')->order(['lft' => 'ASC'])->contain([])->all();
        $this->_buildSitemap($locations, $categories);

        return new Collection($locations);
    }

    /**
     * @param $locations
     * @param $pages
     * @return void
     */
    protected function _buildSitemap(&$locations, $categories, $level = 0)
    {
        foreach ($categories as $category) {
            $url = Router::url($category->getUrl(), true);
            $priority = 1 - ( $level / 10 );
            $lastmod = $category->modified;
            $changefreq = 'weekly';

            $locations[] = new SitemapLocation($url, $priority, $lastmod, $changefreq);

            if ($category->children) {
                $this->_buildSitemap($locations, $category->children, $level + 1);
            }
        }
    }
}
