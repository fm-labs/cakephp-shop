<?php

namespace Shop\Shell\Task;


class ProductImportTask extends BaseShopTask
{
    protected $_categoryCache = [];

    protected $_import = [];

    protected $_variantRootSku;
    protected $_variantRootId;

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(__d('shop',"Import products from CSV file"))
            /*
            ->addOption('path', [
                'help' => 'File path',
                'short' => 'p',
                'default' => null,
            ])
            */
            ->addOption('price-net', [
                'boolean' => true,
                'help' => 'The price column holds net price values (Default: false)',
                'default' => false
            ])
            ->addOption('image-pathprefix', [
                'help' => 'The image path prefix WITHOUT starting slash and WITH trailing slash',
                'default' => null
            ])
            ->addOption('dry-run', [
                'boolean' => true,
                'help' => 'Run without writing changes to the database (Default: false)',
                'default' => false
            ])
            ->addOption('force-parent', [
                'boolean' => true,
                'help' => 'Create parent category if it does not exist (Default: false)',
                'default' => false
            ])
            ->addOption('force-text', [
                'boolean' => true,
                'help' => 'Force non-empty Text column (Default: false)',
                'default' => false
            ])
            ->addOption('force-buyable', [
                'boolean' => true,
                'help' => 'Force buyable (Default: false)',
                'default' => false
            ])
            ->addOption('force-published', [
                'boolean' => true,
                'help' => 'Force published (Default: false)',
                'default' => false
            ])
            ->addOption('clean-text', [
                'boolean' => true,
                'help' => "Stip HTML tags from text (Default: false)",
                'default' => false
            ])
            ->addOption('wrap-text', [
                'boolean' => true,
                'help' => "Convert newline characters to <br> tags and wrap in <p> tag (Default: false)",
                'default' => false
            ])
            ->addOption('skip-priority', [
                'boolean' => true,
                'help' => "Skip setting the priority field (Default: false)",
                'default' => false
            ])
            ->addOption('subcategories', [
                'boolean' => true,
                'help' => 'CSV has additional Subkatogrie column after Kategorie column (Default: false)',
                'default' => false
            ])
            ->addArgument('filename', [
                'help' => 'Filename',
                'required' => true
            ]);

        return $parser;
    }

    public function main()
    {
        $this->out("-- Shop Import --");
        foreach  ($this->args as $key => $val) {
            $this->out("Arg: $key - $val");
        }
        foreach  ($this->params as $key => $val) {
            $this->out("Param: $key - $val");
        }

        //$this->_stop(0);

        $fileName = $this->args[0];
        $dryRun = (isset($this->params['dry-run'])) ? $this->params['dry-run'] : false;
        $forceParent = (isset($this->params['force-parent'])) ? $this->params['force-parent'] : false;
        $forceText = (isset($this->params['force-text'])) ? $this->params['force-text'] : false;
        $forceBuyable = (isset($this->params['force-buyable'])) ? $this->params['force-buyable'] : false;
        $forcePublished = (isset($this->params['force-published'])) ? $this->params['force-published'] : false;
        $cleanText = (isset($this->params['clean-text'])) ? $this->params['clean-text'] : false;
        $wrapText = (isset($this->params['wrap-text'])) ? $this->params['wrap-text'] : false;
        $usePriceNet = (isset($this->params['price-net'])) ? $this->params['price-net'] : false;
        $subcategories = (isset($this->params['subcategories'])) ? $this->params['subcategories'] : false;
        $skipPriority = (isset($this->params['skip-priority'])) ? $this->params['skip-priority'] : false;
        $imagePathPrefix = (isset($this->params['image-pathprefix'])) ? $this->params['image-pathprefix'] : ''; // $fileName . '/';

        $this->loadModel('Shop.ShopCategories');
        $this->loadModel('Shop.ShopProducts');


        $this->_import = ['fail' => 0, 'updated' => 0, 'added' => 0, 'skipped' => 0, 'clean' => 0];

        $importFile = DATA . 'import' . DS . $fileName . '.csv';
        if (!is_file($importFile)) {
            $this->error("Import file not found: " . $importFile);
        }

        $file = fopen($importFile,"r");
        if (!$file) {
            $this->error("Failed to open file $importFile");
        }
        ;
        $fields = ['Kategorie', 'Titel', 'Text', 'Bild', 'Artikelnummer', 'Preis', 'Reihung'];
        $subcategories = false;
        $header = [];
        $rows = [];
        $i = -1;
        while(! feof($file))
        {
            $i++;
            $line = fgetcsv($file);

            // header
            if ($i == 0) {
                $header = $line;

                // get rid of last element if it is empty
                if (empty($header[count($header) - 1])) {
                    unset($header[count($header) - 1]);
                }

                //if (count($header) == count($fields) + 1) {
                if ($subcategories) {
                    $fields = ['Kategorie', 'Subkategorie', 'Titel', 'Text', 'Bild', 'Artikelnummer', 'Preis', 'Reihung'];
                    //$subcategories = true;
                    $this->out("Subcategories enabled");
                }
                elseif (count($header) != count($fields)) {
                    $this->error("Malformed header: Count mismatch");
                }
                for ($j = 0; $j < count($fields); $j++) {
                    if ($header[$j] != $fields[$j]) {
                        $this->error("Malformed header: Invalid field $fields[$j] on position $j");
                    }
                }

                continue;
            }


            // r0w
            $row = [];
            for ($j = 0; $j < count($fields); $j++) {
                $row[$fields[$j]] = trim($line[$j]);
            }

            if (!$row['Kategorie']) {
                $this->_importError($i, 'Row error: Kategorie MISSING');
                continue;
            }
            $categoryId = $this->_findCategoryFromString($row['Kategorie'], null, $forceParent);
            if (!$categoryId) {
                $this->_importError($i, 'Row error: Category not found: ' . $row['Kategorie']);
                continue;
            }

            if ($subcategories && $row['Subkategorie']) {
                $subCategoryId = $this->_findCategoryFromString($row['Subkategorie'], $categoryId);
                if (!$subCategoryId) {
                    $this->_importError($i, 'Row error: Subcategory not found: ' . $row['Subkategorie'], 'warning');
                    //continue;

                    $category = $this->_createCategory($row['Subkategorie'], $categoryId);
                    if (!$category) {
                        $this->_importError($i, 'Row error: Failed to create subcategory: ' . $row['Subkategorie']);
                        $this->error("Aborted");
                        continue;
                    }
                    $categoryId = $category->id;
                } else {
                    $categoryId = $subCategoryId;
                }
            }

            $categoryImagePath = $imagePathPrefix;

            if (!$row['Artikelnummer']) {
                $this->_importError($i, 'Row error: Artikelnummer MISSING');
                continue;
            }
            $skuId = strtoupper($row['Artikelnummer']);
            // article variant root check
            if ($this->_variantRootSku && preg_match('/^' . $this->_variantRootSku . '/', $skuId, $matches)) {
                $skuId = $skuId;
                $this->out('<info>Is Variant Child</info>');
            }
            elseif (preg_match('/^[\_](.*)$/', $skuId, $matches)) {
                $this->out('<info>Is Variant Root</info>');

                $skuId = $matches[1];
                $this->_variantRootSku = $skuId;
            } else {
                //$this->out('<info>Reset Variant Root</info>');
                $this->_variantRootSku = null;
                $this->_variantRootId = null;
            }


            if (!$row['Titel']) {
                $this->_importError($i, 'Row error: Titel MISSING');
                continue;
            }
            $title = $row['Titel'];

            if (!$row['Text'] && $forceText) {
                $this->_importError($i, 'Row error: Text MISSING');
                continue;
            }
            $desc = $row['Text'];
            if ($cleanText) {
                $desc = strip_tags($desc);
            }
            if ($wrapText) {
                $desc = '<p>' . nl2br($row['Text']) . '</p>';
            }


            $image = null;
            if ($row['Bild']) {
                $image = trim($categoryImagePath, '/') . '/' . ltrim($row['Bild'], '/');
            }

            if (!$row['Preis']) {
                $this->_importError($i, 'Row error: Preis MISSING');
                continue;
            }
            $price = $row['Preis'];

            if (!$usePriceNet) {
                // calculate gros price from net price
                $price = $price / 1.2;
            }


            if ($skipPriority) {
                $priority = 1;
            } else {
                if (!$row['Reihung']) {
                    $this->_importError($i, 'Row error: Reihung MISSING');
                    continue;
                }
                $priority = $row['Reihung'];
            }



            // create or update product
            $product = $this->ShopProducts->find()->where(['sku' => $skuId])->contain([])->first();

            if ($product) {

                $stat = 'updated';
                $entityData = [
                    'shop_category_id' => $categoryId,
                    'type' => 'simple',
                    'title' => $title,
                    'teaser_html' => $desc,
                    //'featured_image_file' => $image,
                    'price_net' => $price,
                    'tax_rate' => 20.0,
                    'priority' => $priority
                ];

            } else {

                $stat = 'added';
                $product = $this->ShopProducts->newEntity();
                $entityData = [
                    'shop_category_id' => $categoryId,
                    'type' => 'simple',
                    'title' => $title,
                    'teaser_html' => $desc,
                    'sku' => $skuId,
                    //'featured_image_file' => $image,
                    'is_buyable' => true,
                    'is_published' => false,
                    'price_net' => $price,
                    'tax_rate' => 20.0,
                    'priority' => $priority
                ];

            }

            if ($this->_variantRootSku) {
                if ($this->_variantRootSku == $product->sku) {
                    $entityData['parent_id'] = null;
                    $entityData['type'] = 'parent';
                } elseif ($this->_variantRootSku && $this->_variantRootId) {
                    $entityData['parent_id'] = $this->_variantRootId;
                    $entityData['type'] = 'child';
                }
            }

            if ($image) {
                $entityData['featured_image_file'] = $image;
            }

            if ($forceBuyable) {
                $entityData['is_buyable'] = true;
            }

            if ($forcePublished) {
                $entityData['is_published'] = true;
            }

            $product = $this->ShopProducts->patchEntity($product, $entityData);
            if ($product->errors()) {
                $this->_importError($i, 'Product has errors', 'fail');
                debug($product->errors());
                continue;
            }

            if (!$product->dirty()) {
                $this->_import['clean']++;
                continue;
            }

            /*
            $debugProduct = $product->toArray();
            unset($debugProduct['shop_category']);
            debug($debugProduct);
            continue;
            */

            if (!$dryRun && !$this->ShopProducts->save($product)) {
                $this->_importError($i, 'Saving failed', 'fail');
                continue;
            }
            $this->out(sprintf('<success>Product %s imported</success>', $product['sku']));

            if ($this->_variantRootSku === $product->sku) {
                $this->_variantRootId = $product->id;
            }


            $this->_import[$stat]++;
        }

        fclose($file);


        $this->out(__d('shop',"Added {0} | Failed {1} | Updated {2} | Skipped {3} | Processed {4} | Clean {5}",
            $this->_import['added'], $this->_import['fail'], $this->_import['updated'], $this->_import['skipped'], $i, $this->_import['clean']));


        $this->out("<success>Import successful!</success>");
    }


    protected function _createCategory($categoryName, $parentId = null)
    {
        $category = $this->ShopCategories->newEntity([
            'name' => $categoryName,
            'parent_id' => $parentId,
            'is_published' => true,
        ]);

        if ($category->errors()) {
            $this->out("Category $categoryName has errors");
            debug($category->errors());
            return false;
        }

        return $this->ShopCategories->save($category);
    }

    protected function _findCategoryFromString($categoryString, $parentId = null, $forceParent = false)
    {
        $key = md5($categoryString);
        if (!isset($this->_categoryCache[$key])) {

            // using 'LIKE BINARY' to force case sensitive string comparison
            // @see http://stackoverflow.com/questions/5629111/how-can-i-make-sql-case-sensitive-string-comparison-on-mysql
            $filter = ['name LIKE BINARY ' => $categoryString];
            //if ($forceParent) {
            //    $filter['parent_id'] = null;
            //}

            $category = $this->ShopCategories
                ->find()
                ->where($filter);

            if ($parentId) {
                $category->where(['name' => $categoryString, 'parent_id' => $parentId]);
            }

            $category = $category->first();

            if (!$category) {
                return false;
            }
            $this->_categoryCache[$key] = $category->id;
        }
        return $this->_categoryCache[$key];
    }

    protected function _importError($line, $msg, $stat = 'skipped') {
        $this->_import[$stat]++;
        $this->err("Import error on line $line: $msg");
    }

}