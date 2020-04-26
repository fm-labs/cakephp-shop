<?php
declare(strict_types=1);

namespace Shop\Shell;

use Cake\Console\Shell;

/**
 * Class ShopImportShell
 * @package App\Shell
 *
 * @property \Shop\Model\Table\ShopCustomersTable $ShopCustomers
 * @property \Shop\Model\Table\ShopCategoriesTable $ShopCategories
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 * @property \Attachment\Model\Table\AttachmentsTable $Attachments
 */
class ShopImportShell extends Shell
{
    protected $_categoryCache = [];

    protected $_import = [];

    public function importShopCustomersBak($fileName)
    {
        $this->abort('!!!!!! This method is deprecated - Use importShopCustomers() instead!!!!!!');

        $this->loadModel('Shop.ShopCustomers');

        $this->_import = ['fail' => 0, 'updated' => 0, 'added' => 0, 'skipped' => 0, 'clean' => 0];

        $importFile = DATA . 'import' . DS . $fileName . '.csv';
        if (!is_file($importFile)) {
            $this->abort("Import file not found: " . $importFile);
        }

        $file = fopen($importFile, "r");
        if (!$file) {
            $this->abort("Failed to open file $importFile");
        }

        $fields = ['Anrede', 'Vorname', 'Zuname', 'Strasse', 'PLZ', 'Ort', 'Land', 'Passwort', 'Tel', 'Fax', 'Email'];
        $header = [];
        $rows = [];
        $i = -1;
        while (! feof($file)) {
            $i++;
            $line = fgetcsv($file);

            if (empty($line)) {
                $this->out("Empty Line");
                continue;
            }

            // header
            if ($i == 0) {
                $header = $line;

                // get rid of last element if it is empty
                //if (empty($header[count($header) - 1])) {
                //    unset($header[count($header) - 1]);
                //}

                if (count($header) != count($fields)) {
                    $this->abort("Malformed header: Count mismatch");
                }

                for ($j = 0; $j < count($fields); $j++) {
                    if ($header[$j] != $fields[$j]) {
                        $this->abort("Malformed header: Invalid field $fields[$j] on position $j");
                    }
                }

                continue;
            }

            // r0w
            $row = [];
            for ($j = 0; $j < count($fields); $j++) {
                $row[$fields[$j]] = trim($line[$j]);
            }

            $customer = $this->ShopCustomers
                ->find()
                ->where(['ShopCustomers.email' => $row['Email']])
                ->first();

            if (!$customer) {
                $customer = $this->ShopCustomers->newEmptyEntity();
            }

            $entityData = [
                'email' => $row['Email'],
                'password' => $row['Passwort'],
                'greeting' => $row['Anrede'],
                'first_name' => $row['Vorname'],
                'last_name' => $row['Zuname'],
                'street' => $row['Strasse'],
                'zipcode' => $row['PLZ'],
                'city' => $row['Ort'],
                'country' => $row['Land'],
                'country_iso2' => $row['Land'],
                'phone' => $row['Tel'],
                'fax' => $row['Fax'],
            ];

            $customer->setAccess('*', true);
            $customer = $this->ShopCustomers->patchEntity($customer, $entityData);
            if ($customer->getErrors()) {
                $this->_importError($i, 'Customer has errors', 'fail');
                debug($customer->getErrors());
                continue;
            }

            if (!$customer->isDirty()) {
                $this->_import['clean']++;
                continue;
            }

            //debug($customer->toArray());
            //continue;
            if (!$this->ShopCustomers->save($customer)) {
                $this->_importError($i, 'Saving failed', 'fail');
                continue;
            }

            $this->_import['added']++;
        }

        fclose($file);

        $this->out(__d(
            'shop',
            "Added {0} | Failed {1} | Updated {2} | Skipped {3} | Processed {4} | Clean {5}",
            $this->_import['added'],
            $this->_import['fail'],
            $this->_import['updated'],
            $this->_import['skipped'],
            $i,
            $this->_import['clean']
        ));
    }

    public function importShopCustomers($fileName)
    {
        $this->loadModel('Shop.ShopCustomers');

        $this->_import = ['fail' => 0, 'updated' => 0, 'added' => 0, 'skipped' => 0, 'clean' => 0, 'updatepass' => 0];

        $importFile = DATA . 'import' . DS . $fileName . '.csv';
        if (!is_file($importFile)) {
            $this->abort("Import file not found: " . $importFile);
        }

        $file = fopen($importFile, "r");
        if (!$file) {
            $this->abort("Failed to open file $importFile");
        }

        $fields = ['greeting', 'first_name', 'last_name', 'street', 'zipcode', 'city', 'country', 'password', 'phone', 'fax', 'email'];
        $header = [];
        $rows = [];
        $i = -1;
        while (! feof($file)) {
            $i++;
            $line = fgetcsv($file);

            if (empty($line)) {
                $this->out("Empty Line");
                continue;
            }

            // header
            if ($i == 0) {
                $header = $line;

                // get rid of last element if it is empty
                //if (empty($header[count($header) - 1])) {
                //    unset($header[count($header) - 1]);
                //}

                if (count($header) != count($fields)) {
                    $this->abort("Malformed header: Count mismatch");
                }

                for ($j = 0; $j < count($fields); $j++) {
                    if ($header[$j] != $fields[$j]) {
                        $this->abort("Malformed header: Invalid field $fields[$j] on position $j");
                    }
                }

                continue;
            }

            // r0w
            $row = [];
            for ($j = 0; $j < count($fields); $j++) {
                $row[$fields[$j]] = trim($line[$j]);
            }

            $count = 'updated';
            $customer = $this->ShopCustomers
                ->find()
                ->where(['ShopCustomers.email' => $row['email']])
                ->first();

            if (!$customer) {
                $count = 'added';
                $customer = $this->ShopCustomers->newEmptyEntity();
            }

            $entityData = [
                'email' => $row['email'],
                'greeting' => $row['greeting'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'street' => $row['street'],
                'zipcode' => $row['zipcode'],
                'city' => $row['city'],
                'country' => $row['country'],
                'country_iso2' => $row['country'],
                'phone' => $row['phone'],
                'fax' => $row['fax'],
                'email_verified' => true,
                'is_blocked' => false,
                'is_new' => false,
            ];

            if (!$customer->password) {
                $entityData['password'] = $row['password'];
                $this->_import['updatepass']++;
            }

            $customer->setAccess('*', true);
            $customer = $this->ShopCustomers->patchEntity($customer, $entityData);
            if ($customer->getErrors()) {
                $this->_importError($i, 'Customer has errors', 'fail');
                debug($customer->getErrors());
                continue;
            }

            if (!$customer->isDirty()) {
                $this->_import['clean']++;
                continue;
            }

            debug($customer->toArray());

            /*
            if (!$this->ShopCustomers->save($customer)) {
                $this->_importError($i, 'Saving failed', 'fail');
                continue;
            }
            */

            $this->_import[$count]++;
        }

        fclose($file);

        $this->out(__d(
            'shop',
            "Added {0} | Failed {1} | Updated {2} | Skipped {3} | Processed {4} | Clean {5} | Updated Pass {6}",
            $this->_import['added'],
            $this->_import['fail'],
            $this->_import['updated'],
            $this->_import['skipped'],
            $i,
            $this->_import['clean'],
            $this->_import['updatepass']
        ));
    }

    public function importShopProducts($fileName = null, $forceParent = false)
    {
        $this->loadModel('Shop.ShopCategories');
        $this->loadModel('Shop.ShopProducts');

        $importMap = [
            'herbst_2015' => [
                'image_path' => 'herbst2015/',
            ],
            'winter_2015' => [
                'image_path' => 'winter2015/',
            ],
            'moebel' => [
                'image_path' => 'moebel/',
            ],
            'fruehling_2016' => [
                'image_path' => 'fruehling_2016/',
            ],
            'gartenzeit_02' => [
                'image_path' => 'gartenzeit_02/',
            ],
        ];

        $this->_import = ['fail' => 0, 'updated' => 0, 'added' => 0, 'skipped' => 0, 'clean' => 0];

        $forceParent = (bool)$forceParent;

        $importFile = DATA . 'import' . DS . 'shop_products_' . $fileName . '.csv';
        if (!is_file($importFile)) {
            $this->abort("Import file not found: " . $importFile);
        }

        $file = fopen($importFile, "r");
        if (!$file) {
            $this->abort("Failed to open file $importFile");
        }
        $fields = ['Kategorie', 'Titel', 'Text', 'Bild', 'Artikelnummer', 'Preis', 'Reihung'];
        $subcategories = false;
        $header = [];
        $rows = [];
        $i = -1;
        while (! feof($file)) {
            $i++;
            $line = fgetcsv($file);

            // header
            if ($i == 0) {
                $header = $line;

                // get rid of last element if it is empty
                if (empty($header[count($header) - 1])) {
                    unset($header[count($header) - 1]);
                }

                if (count($header) == count($fields) + 1) {
                    $fields = ['Kategorie', 'Subkategorie', 'Titel', 'Text', 'Bild', 'Artikelnummer', 'Preis', 'Reihung'];
                    $subcategories = true;
                    $this->out("Subcategories enabled");
                } elseif (count($header) != count($fields)) {
                    $this->abort("Malformed header: Count mismatch");
                }
                for ($j = 0; $j < count($fields); $j++) {
                    if ($header[$j] != $fields[$j]) {
                        $this->abort("Malformed header: Invalid field $fields[$j] on position $j");
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
                        $this->abort("Aborted");
                        continue;
                    }
                    $categoryId = $category->id;
                } else {
                    $categoryId = $subCategoryId;
                }
            }

            $categoryImagePath = isset($importMap[$fileName]) ? $importMap[$fileName]['image_path'] : '';

            if (!$row['Artikelnummer']) {
                $this->_importError($i, 'Row error: Artikelnummer MISSING');
                continue;
            }
            $skuId = $row['Artikelnummer'];

            if (!$row['Titel']) {
                $this->_importError($i, 'Row error: Titel MISSING');
                continue;
            }
            $title = $row['Titel'];

            if (!$row['Text']) {
                $this->_importError($i, 'Row error: Text MISSING');
                continue;
            }
            $desc = '<p>' . nl2br($row['Text']) . '</p>';

            $image = null;
            if ($row['Bild']) {
                $image = $categoryImagePath . $row['Bild'];
            }

            if (!$row['Preis']) {
                $this->_importError($i, 'Row error: Preis MISSING');
                continue;
            }
            $price = $row['Preis'];

            if (!$row['Reihung']) {
                $this->_importError($i, 'Row error: Reihung MISSING');
                continue;
            }
            $orderPos = $row['Reihung'];

            // create or update product
            $product = $this->ShopProducts->find()->where(['sku' => $skuId])->first();

            if ($product) {
                $stat = 'updated';
                $entityData = [
                    'shop_category_id' => $categoryId,
                    'title' => $title,
                    'teaser_html' => $desc,
                    //'featured_image_file' => $image,
                    'price_net' => $price,
                    'tax_rate' => 20.0,
                    'priority' => $orderPos,
                ];
            } else {
                $stat = 'added';
                $product = $this->ShopProducts->newEmptyEntity();
                $entityData = [
                    'shop_category_id' => $categoryId,
                    'title' => $title,
                    'teaser_html' => $desc,
                    'sku' => $skuId,
                    //'featured_image_file' => $image,
                    'is_published' => true,
                    'price_net' => $price,
                    'tax_rate' => 20.0,
                    'priority' => $orderPos,
                ];
            }

            if ($image) {
                $entityData['featured_image_file'] = $image;
            }

            $product = $this->ShopProducts->patchEntity($product, $entityData);
            if ($product->getErrors()) {
                $this->_importError($i, 'Product has errors', 'fail');
                debug($product->getErrors());
                continue;
            }

            if (!$product->isDirty()) {
                $this->_import['clean']++;
                continue;
            }

            //debug($product->toArray());
            //continue;
            if (!$this->ShopProducts->save($product)) {
                $this->_importError($i, 'Saving failed', 'fail');
                continue;
            }

            $this->_import[$stat]++;
        }

        fclose($file);

        $this->out(__d(
            'shop',
            "Added {0} | Failed {1} | Updated {2} | Skipped {3} | Processed {4} | Clean {5}",
            $this->_import['added'],
            $this->_import['fail'],
            $this->_import['updated'],
            $this->_import['skipped'],
            $i,
            $this->_import['clean']
        ));
    }

    protected function _createCategory($categoryName, $parentId = null)
    {
        $category = $this->ShopCategories->newEntity([
            'name' => $categoryName,
            'parent_id' => $parentId,
            'is_published' => true,
        ]);

        if ($category->getErrors()) {
            $this->out("Category $categoryName has errors");
            debug($category->getErrors());

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

    protected function _importError($line, $msg, $stat = 'skipped')
    {
        $this->_import[$stat]++;
        $this->err("Import error on line $line: $msg");
    }

    public function convertProductsToCategories($categoryId = null)
    {
        $this->loadModel('Shop.ShopCategories');
        $this->loadModel('Shop.ShopProducts');

        $category = $this->ShopCategories->get($categoryId);
        if (!$category) {
            $this->abort("Category #$categoryId not found");
        }

        $products = $this->ShopProducts->find()->where(['shop_category_id' => $categoryId]);

        foreach ($products as $product) {
            // check if already migrated
            $cat = $this->ShopCategories
                ->find()
                ->where(['ShopCategories.custom5' => 'ShopProduct:' . $product->id])
                ->first();

            // create new category from product
            if (!$cat) {
                $cat = $this->ShopCategories->newEmptyEntity();

                $this->out("Creating category for product " . $product->id . " in Category $categoryId");
            }

            $cat = $this->ShopCategories->patchEntity($cat, [
                'parent_id' => $categoryId,
                'name' => $product->title,
                'slug' => $product->slug,
                'teaser_html' => $product->teaser_html,
                'desc_html' => $product->desc_html,
                'preview_image_file' => $product->preview_image_file,
                'featured_image_file' => $product->featured_image_file,
                'image_files' => $product->image_files,
                'is_published' => true,
                //'view_template' => null,
                'custom5' => 'ShopProduct:' . $product->id,
            ]);

            if ($cat->getErrors()) {
                $this->err("Category $categoryId has errors");
                continue;
            }

            if (!$this->ShopCategories->save($cat)) {
                $this->err("Category $categoryId failed");
                continue;
            }

            // update product category
            $product->shop_category_id = $cat->id;

            if (!$this->ShopProducts->save($product)) {
                $this->err("Product $product->id has errors");
                continue;
            }

            $this->out("Product " . $product->id . " has been updated with category " . $cat->id);
        }
    }

    public function convertProductAttachments()
    {
        $this->loadModel('Shop.ShopCategories');
        $this->loadModel('Attachment.Attachments');

        $categories = $this->ShopCategories->find()->where('custom5 IS NOT NULL')->all();

        foreach ($categories as $c) {
            if (!preg_match('/^ShopProduct\:([0-9]+)/', $c->custom5, $matches)) {
                continue;
            }

            $productId = $matches[1];
            $this->out("Detected converted product with ID $productId");

            $updated = $this->Attachments->updateAll(['model' => 'Shop.ShopCategories', 'modelid' => $c->id], ['model' => 'ShopProducts', 'modelid' => $productId]);
            $this->out("Updated $updated rows for product $productId");
        }
    }
}
