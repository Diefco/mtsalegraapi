<?php
/**
 * 2017 Metasysco
 *
 * AVISO DE LICENCIA
 *
 * Este archivo fuente está sujeto a la Academic Free License (AFL 3.0)
 * El cual está incluido en el archivo LICENCE.txt.
 * También se encuentra disponible en línea, en la siguiente URL:
 * http://opensource.org/licenses/afl-3.0.php
 * Si por algún motivo usted no recibió una copia de esta licencia,
 * o no pudo obtenerlo a través de la URL, por favor envíe un correo a
 * info@metasysco.com, y en la brevedad de lo posible se le enviará una
 * copia inmediata.
 *
 * ADVERTENCIA
 *
 * No edite, modifique o altere el código de este archivo, si usted
 * tiene planeado a futuro actualizar la plataforma Prestashop a una
 * nueva versión (Aplicable para la versión de Prestashop 1.6.x.x).
 * Si usted desea modificar este módulo para su necesidad, por favor
 * contáctenos por medio del correo electrónico development@metasysco.com
 * o visite nuestra página web http://www.metasysco.com para mas información.
 *
 * @author Carlos Moreno <carlos.moreno@metasysco.com.co>
 * @copyright 2017 Metasysco S.A.S.
 * @license http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @version 1.1.0
 */

class MtsAlegraApiProductCreateModuleFrontController extends ModuleFrontController
{
    public $urlApi = 'items';

    public function initContent()
    {
        parent::initContent();

        $cookie = new Cookie('session');

        // Validate if the current user is Authorized.
        $this->validateCookieAuth($cookie);

        // Execute the auto-ignore for Demo products
        $this->firstProductsCall();

        $mts_join = $this->dbQueryJoin(
            'ps_orders.id_order, ps_cart_product.id_product, ps_cart_product.id_product_attribute',
            'cart_product',
            array(
                array(
                    'table' => 'orders',
                    'alias' => null,
                    'on' => 'ps_orders.id_cart = ps_cart_product.id_cart'
                ),
                array(
                    'table' => 'mtsalegraapi_products',
                    'alias' => null,
                    'on' => 'ps_cart_product.id_product_attribute = ps_mtsalegraapi_products.id_attribute_store'
                )
            ),
            'ps_mtsalegraapi_products.id_product_alegra is NULL AND (ps_orders.current_state = 2 OR ps_orders.current_state = 12)'
        );

        $products = array();

        foreach ($mts_join as $productData) {
            if (array_key_exists($productData['id_order'], $products) === false) {
                $products[$productData['id_order']] = array();
            }

            if (array_key_exists($productData['id_product'], $products[$productData['id_order']]) === false) {
                $products[$productData['id_order']][$productData['id_product']] = array();
            }

            if (array_search($productData['id_product_attribute'], $products[$productData['id_order']][$productData['id_product']]) === false ) {
                $products[$productData['id_order']][$productData['id_product']][] = $productData['id_product_attribute'];
            }

        }

        if (count($products) > 0) {
            $productData = $this->prepareData($products);
            $this->context->smarty->assign('products', $productData);

            if (Tools::isSubmit('ProductCreate')) {
                $this->processProductCreate($productData);
                Tools::redirect($this->context->link->getModuleLink(
                    'mtsalegraapi',
                    'productCreate',
                    array(),
                    Configuration::get('PS_SSL_ENABLED')
                ));
            }
        }

        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink(
            'mtsalegraapi',
            'home',
            array(),
            Configuration::get('PS_SSL_ENABLED')
        ));
        $this->setTemplate('products/create.tpl');
    }

    private function validateCookieAuth($cookie)
    {
        if ($cookie->auth != true) {
            Tools::redirect($this->context->link->getModuleLink(
                'mtsalegraapi',
                'login',
                array(),
                Configuration::get('PS_SSL_ENABLED')
            ));
        }
    }

    private function getApiAuthToken()
    {
        /**
         * !!!DISCLAIMER!!!
         * https://developer.alegra.com/v1/docs/autenticacion
         * Base64 encoding required from ALegra API: Must be used to generate an Authentication Token.
         * Otherwise, this module will not work properly.
         */

        $authToken = base64_encode(
            Configuration::get('mts_AlgApi_Email') . ':' . Configuration::get('mts_AlgApi_Token')
        );

        return $authToken;
    }

    private function firstProductsCall()
    {
        $mts_product = $this->dbQuery(
            'id_product_store',
            'mtsalegraapi_products',
            null,
            null,
            1
        );

        if (count($mts_product) == 0) {
            $store_product = $this->dbQuery(
                'id_product, reference',
                'product',
                null,
                'id_product',
                7
            );

            //  First Execution (Module recently installed)
            if (count($store_product) > 0 &&
                $store_product[0]['id_product'] == 1 && $store_product[0]['reference'] = "demo_1" &&
                    $store_product[1]['id_product'] == 2 && $store_product[1]['reference'] = "demo_2" &&
                        $store_product[2]['id_product'] == 3 && $store_product[2]['reference'] = "demo_3" &&
                            $store_product[3]['id_product'] == 4 && $store_product[3]['reference'] = "demo_4" &&
                                $store_product[4]['id_product'] == 5 && $store_product[4]['reference'] = "demo_5" &&
                                    $store_product[5]['id_product'] == 6 && $store_product[5]['reference'] = "demo_6" &&
                                        $store_product[6]['id_product'] == 7 && $store_product[6]['reference'] = "demo_7") {
                $products = array();
                for ($i = 1; $i <= 7; $i++) {
                    $products[] = array(
                        'id_product_store' => $i,
                        'id_product_alegra' => 0,
                        'product_ignored' => true,
                        'observations' => 'Product Demo'
                    );
                }

                Db::getInstance()->insert('mtsalegraapi_products', $products);
            }
        }
    }

    private function dbQueryJoin($select, $from, $leftJoin = null, $where = null, $orderBy = null, $limit = null)
    {
        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        $sql =  new DbQuery();
        $sql->select($select)
            ->from($from);

        if ($leftJoin != null) {
            foreach ($leftJoin as $query) {
                $sql->leftJoin($query['table'], $query['alias'], $query['on']);
            }
        }

        $sql->where($where);

        if ($orderBy != null) {
            $sql->orderBy($orderBy);
        }

        if ($limit != null) {
            $sql->limit($limit);
        }

//        $sql->limit($limitQuery);

        return Db::getInstance()->executeS($sql);
    }

    private function dbQuery($select, $from, $where = null, $orderBy = null, $limit = null)
    {
        if (is_array($select)) {
            $select_query = implode(', ', $select);
        } else {
            $select_query = $select;
        }

        $sql = new DbQuery();
        $sql->select($select_query)
            ->from($from);

        if ($where != null) {
            $sql->where($where);
        }

        if ($orderBy != null) {
            $sql->orderBy($orderBy);
        }

        if ($limit != null) {
            $sql->limit($limit);
        }

        return Db::getInstance()->executeS($sql);
    }

    private function dbInsert($table, $data)
    {
        return Db::getInstance()->insert($table, $data);
    }

    private function prepareData($joinResult)
    {
        $taxesStoreArray = $this->dbQuery(
            array(
                'id_tax',
                'rate'
            ),
            'tax',
            null,
            'id_tax'
        );

        $nameTaxes = $this->dbQuery(
            array(
                'id_tax',
                'name'
            ),
            'tax_lang',
            'id_lang = 1',
            'id_tax'
        );

        foreach ($taxesStoreArray as $index => $tax) {
            if ($tax['id_tax'] === $nameTaxes[$index]['id_tax']) {
                $taxesStoreArray[$index]['name'] = $nameTaxes[$index]['name'];
            }
        }

        $taxesAlegraArray = $this->sendToApi('taxes', 'get', null);

        if ($taxesAlegraArray[0]) {
            $productsArray = array();
            $relatedTaxes = array();

            foreach ($taxesAlegraArray[1] as $alegraIndex => $alegraTax) {
                foreach ($taxesStoreArray as $storeIndex => $storeTax) {
                    $storeTaxRate = filter_var($storeTax['rate'], FILTER_VALIDATE_FLOAT);
                    $alegraTaxRate = filter_var($alegraTax['percentage'], FILTER_VALIDATE_FLOAT);
                    if (stristr($storeTax['name'], $alegraTax['name']) !== false &&
                        $storeTaxRate == $alegraTaxRate) {
                        $relatedTaxes[] = array(
                            'id_tax_alegra' => $alegraTax['id'],
                            'id_tax_store' => $storeTax['id_tax'],
                            'tax_value' => $storeTaxRate,
                            'tax_name' => $alegraTax['name'],
                        );
                    }
                }
            }

            $newJoin = array();
            foreach ($joinResult as $id_order => $order_products) {
                foreach ($order_products as $id_product => $product) {
                    foreach ($product as $attribute) {
                        $newJoin[$attribute] = $this->dbQueryJoin(
                            array(
                                'ps_order_detail.product_name',
                                'ps_product_lang.description_short AS product_description',
                                'ps_product.price AS product_price',
                                'ps_product.wholesale_price AS product_wholesale_price',
                                'ps_product.reference AS product_reference',
                                'ps_product.id_tax_rules_group AS product_tax',
                                'ps_product_attribute.reference AS attribute_reference',
                                'ps_product_attribute.price AS attribute_price',
                                'ps_product_attribute.wholesale_price AS attribute_wholesale_price',
                                'ps_stock_available.quantity AS attribute_quantity',
                            ),
                            'product',
                            array(
                                array(
                                    'table' => 'order_detail',
                                    'alias' => null,
                                    'on' => 'ps_order_detail.product_attribute_id = ' . $attribute
                                ),
                                array(
                                    'table' => 'product_attribute',
                                    'alias' => null,
                                    'on' => 'ps_product_attribute.id_product = ' . $id_product . ' AND ps_product_attribute.id_product_attribute = ' . $attribute
                                ),
                                array(
                                    'table' => 'stock_available',
                                    'alias' => null,
                                    'on' => 'ps_stock_available.id_product = ' . $id_product . ' AND ps_stock_available.id_product_attribute = ' . $attribute
                                ),
                                array(
                                    'table' => 'mtsalegraapi_products',
                                    'alias' => null,
                                    'on' => 'ps_product_attribute.id_product_attribute = ps_mtsalegraapi_products.id_attribute_store'
                                ),
                                array(
                                    'table' => 'orders',
                                    'alias' => null,
                                    'on' => 'ps_orders.id_order = ps_order_detail.id_order'
                                ),
                                array(
                                    'table' => 'product_lang',
                                    'alias' => null,
                                    'on' => 'ps_product.id_product = ps_product_lang.id_product'
                                ),
                                array(
                                    'table' => 'tax_rule',
                                    'alias' => null,
                                    'on' => 'ps_product.id_tax_rules_group = ps_tax_rule.id_tax'
                                )
                            ),
                            '
                            ps_mtsalegraapi_products.id_product_alegra is NULL AND 
                            (ps_orders.current_state = 2 OR ps_orders.current_state = 12) AND 
                            ps_product.id_product = ps_order_detail.product_id AND
                            ps_orders.id_order = ' . $id_order,
                            null
                        );

                        $productsArray[$attribute]['name'] = filter_var(
                            $newJoin[$attribute][0]['product_name'],
                            FILTER_SANITIZE_STRING,
                            FILTER_FLAG_NO_ENCODE_QUOTES
                        );
                        $productsArray[$attribute]['description'] = filter_var(
                            $newJoin[$attribute][0]['product_description'],
                            FILTER_SANITIZE_STRING,
                            FILTER_FLAG_NO_ENCODE_QUOTES
                        );
                        $productsArray[$attribute]['reference'] = filter_var(
                            $newJoin[$attribute][0]['attribute_reference'],
                            FILTER_SANITIZE_STRING,
                            FILTER_FLAG_NO_ENCODE_QUOTES
                        );
                        $productsArray[$attribute]['inventory']['unitCost'] = (
                            filter_var(
                                $newJoin[$attribute][0]['product_wholesale_price'],
                                FILTER_VALIDATE_FLOAT
                            ) +
                            filter_var(
                                $newJoin[$attribute][0]['attribute_wholesale_price'],
                                FILTER_VALIDATE_FLOAT
                            )
                        );
                        $productsArray[$attribute]['inventory']['initialQuantity'] = filter_var(
                            $newJoin[$attribute][0]['attribute_quantity'],
                            FILTER_VALIDATE_FLOAT
                        );

                        foreach ($relatedTaxes as $tax) {
                            if ($tax['id_tax_store'] = filter_var($newJoin[$attribute][0]['product_tax'],FILTER_VALIDATE_FLOAT)) {
                                $productsArray[$attribute]['tax']['name'] = $tax['tax_name'];
                                $productsArray[$attribute]['tax']['value'] = $tax['tax_value'];
                                $productsArray[$attribute]['tax']['alegra'] = $tax['id_tax_alegra'];
                                $productsArray[$attribute]['tax']['store'] = $tax['id_tax_store'];
                            }
                        }

                        $productsArray[$attribute]['price'] = (
                            filter_var(
                                $newJoin[$attribute][0]['product_price'],
                                FILTER_VALIDATE_FLOAT
                            ) +
                            filter_var(
                                $newJoin[$attribute][0]['attribute_price'],
                                FILTER_VALIDATE_FLOAT
                            )
                        );

                        $productsArray[$attribute]['id_product'] = $id_product;
                    }
                }
            }
            return $productsArray;
        }

        return false;
    }

    private function processProductCreate($productData)
    {
        $postValues = Tools::getAllValues();
        $keys = array_keys($postValues);

        $products = array();
        $observations = array();
        foreach ($keys as $key) {
            if (Tools::strrpos($key, 'product_option') !== false) {
                $attribute = explode('_', $key);
                $productData[$attribute[2]]['tax'] = $productData[$attribute[2]]['tax']['alegra'];
                $productData[$attribute[2]]['inventory']['unit'] = Tools::getValue('product_unit_' . $attribute[2]);
                $observations[$attribute[2]] = 'Unidad: ' . Tools::getValue('product_unit_' . $attribute[2]) . ' | ' .
                    Tools::getValue('product_observations_' . $attribute[2]);
                if (Tools::getValue($key) == 'upload') {
                    $products[$productData[$attribute[2]]['id_product']][] = $attribute[2];
                } elseif (Tools::getValue($key) == 'ignore') {
                    $this->dbInsert(
                        'mtsalegraapi_products',
                        array(
                            'id_product_store' => $productData[$attribute[2]]['id_product'],
                            'id_attribute_store' => $attribute[2],
                            'id_product_alegra' => 0,
                            'product_ignored' => 1,
                            'observations' => $observations[$attribute[2]]
                        )
                    );
                }
                array_pop($productData[$attribute[2]]);
            }
        }

        foreach ($products as $key => $id_attribute) {
            $request = $this->sendToApi($this->urlApi, 'post', $productData[$id_attribute[0]]);

            if ($request[0] == true) {
                $this->dbInsert(
                    'mtsalegraapi_products',
                    array(
                        'id_product_store' => $key,
                        'id_attribute_store' => $id_attribute[0],
                        'id_product_alegra' => $request[1]['id'],
                        'product_ignored' => 0,
                        'observations' => $observations[$key]
                    )
                );
            }
        }
    }

    private function sendToApi($url, $method, $request = null)
    {
        $method = Tools::strtoupper($method);
        if (!($method != 'POST' || $method != 'GET') || $method == null) {
            $this->printer('El método debe ser POST o GET.', __LINE__, false);
            return false;
        } elseif ($method == 'POST' && $request == null) {
            $this->printer('Si el método es POST, $request no puede ser NULL', __LINE__, false);
            return false;
        }

        $toValidateUrl = array(
            'items',            // Products or Services
            'contacts',         // Contacts or Customers
            'invoices',         // Invoices
            'estimates',        // Pre-invoices or Estimates
            'number-templates', // Registered Invoices numerations
            'taxes',            // Taxes
            'bank-accounts',    // Bank Accounts
            'company',          // Company
            'payments',         // Payments
            'retentions',       // Retentions
            'categories',       // Categories
            'sellers',          // Sellers
            'price-lists',      // Price Lists
            'warehouses',       // Warehouses or Storage
        );

        $validatedUrl = false;
        foreach ($toValidateUrl as $endpoint) {
            if (Tools::strtolower($url) == $endpoint) {
                $validatedUrl = true;
            }
        }

        if (!$validatedUrl) {
            $this->printer('El ENDPOINT no es válido', __LINE__, false);
            return false;
        }

        $jsonRequest = json_encode($request);
        $authToken = $this->getApiAuthToken();

        $urlRequest = 'https://app.alegra.com/api/v1/' . $url . '/';
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $authToken
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRequest);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $requestExec = curl_exec($ch);

        $requestData = json_decode($requestExec, true);

        if ($requestData == null ||
            gettype($requestData) != 'array' ||
            array_key_exists('code', $requestData) ||
            array_key_exists('error', $requestData)
        ) {
            return array(false, $requestData);
        }
        return array(true, $requestData);
    }

    private function printer($var, $line = false, $die = true, $debug = false)
    {
        echo "<pre>";
        if ($debug) {
            var_dump($var);
        } else {
            print_r($var);
        }
        if ($line) {
            print_r("<br>" . gettype($var) . ' en la línea ' . $line);
        }
        echo "<br></pre>";
        if ($die) {
            die();
        }
    }
}
