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

class MtsAlegraApiInvoiceCreateModuleFrontController extends ModuleFrontController
{
    public $urlApi = 'items';

    public function initContent()
    {
        parent::initContent();

        $cookie = new Cookie('session');

        // Validate if the current user is Authorized.
        $this->validateCookieAuth($cookie);

        // Execute the auto-ignore for Demo products
        $this->firstInvoicesCall();

        $mts_join = $this->dbQueryJoin(
            'id_order',
            'orders',
            'id_order_store',
            'mtsalegraapi_invoices',
            'id_order_alegra',
            'order_ignored',
            'id_order'
        );

        if (count($mts_join) > 0) {
            $productData = $this->prepareData($mts_join);
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
        $this->setTemplate('invoices/create.tpl');
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

    private function firstInvoicesCall()
    {
        $mts_orders = $this->dbQuery(
            'id_order_store',
            'mtsalegraapi_invoices',
            null,
            null,
            1
        );

        if (count($mts_orders) == 0) {
            $store_orders = $this->dbQuery(
                'id_order',
                'orders',
                'id_customer = 1',
                'id_order',
                5
            );

            //  First Execution (Module recently installed)
            if (count($store_orders) > 0) {
                $orders = array();
                for ($i = 1; $i <= 5; $i++) {
                    $orders[] = array(
                        'id_order_store' => $i,
                        'id_order_alegra' => 0,
                        'order_ignored' => true
                    );
                }

                Db::getInstance()->insert('mtsalegraapi_invoices', $orders);
            }
        }
    }

    private function dbQueryJoin($select, $from, $leftColumn, $leftTable, $leftWhere_1, $leftWhere_2, $orderBy = null, $alias = null)
    {
        // Get the limit number to send a DB Query.
        $limitQuery = Configuration::get('mts_AlgApi_limitQuery');

        $rightTable = $from;

        if (is_array($select)) {
            $rightColumn = $select[0];
            $select_query = implode(', ', $select);
        } else {
            $select_query = $select;
            $rightColumn = $select;
        }


        $sql = new DbQuery();
        $sql
            ->select($select_query)
            ->from($from)
            ->leftJoin(
                $leftTable,
                $alias,
                _DB_PREFIX_ . $rightTable . '.' . $rightColumn . ' = ' .
                _DB_PREFIX_ . $leftTable . '.' . $leftColumn
            )
            ->where(
                _DB_PREFIX_ . $leftTable . '.' . $leftWhere_1 . ' is NULL ||' .
                _DB_PREFIX_ . $leftTable . '.' . $leftWhere_2 . ' is NULL'
            )
            ->limit($limitQuery);
        if ($orderBy != null) {
            $sql->orderBy($orderBy);
        }
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

        $productsArray = array();

        if ($taxesAlegraArray[0]) {
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

            foreach ($joinResult as $indexProduct => $product) {
                // Get the short description for each product
                $descriptionArray = $this->dbQuery(
                    array(
                        'description_short',
                        'name'
                    ),
                    'product_lang',
                    'id_product = ' . $product['id_product'] . ' && id_lang = 1',
                    null,
                    1
                );

                // Get the price for each product (without attributes)
                $priceArray = $this->dbQuery(
                    array(
                        'price',
                        'wholesale_price'
                    ),
                    'product_shop',
                    'id_product = ' . $product['id_product'],
                    null,
                    1
                );

                // Get the quantity for each product (without attributes)
                $quantityArray = $this->dbQuery(
                    'quantity',
                    'stock_available',
                    'id_product = ' . $product['id_product'],
                    null,
                    1
                );

                // Get the tax rules registered in the Store
                $taxRulesArray = $this->dbQuery(
                    array(
                        'id_tax_rules_group',
                        'id_tax',
                        'behavior'
                    ),
                    'tax_rule',
                    'id_tax_rules_group = ' . $product['id_tax_rules_group']
                );

                if (count($taxRulesArray) != 1 || (
                        $taxRulesArray[0]['behavior'] != 0 || $taxRulesArray[0]['behavior'] != '0'
                    )
                ) {
                    $taxException = true;
                } else {
                    $taxException = false;
                }

                if (!$taxException) {
                    foreach ($relatedTaxes as $indexRelatedTax => $relatedTax) {
                        if ($taxRulesArray[0]['id_tax'] == $relatedTax['id_tax_store']) {
                            $taxAlegra = filter_var(
                                $relatedTax['id_tax_alegra'],
                                FILTER_VALIDATE_INT
                            );
                            $taxStore = filter_var(
                                $relatedTax['id_tax_store'],
                                FILTER_VALIDATE_INT
                            );
                            $taxValue = filter_var(
                                $relatedTax['tax_value'],
                                FILTER_VALIDATE_INT
                            );
                            $taxName = $relatedTax['tax_name'];
                        }
                    }
                }

                $productsArray[$product['id_product']] = array(
                    'name' => filter_var(
                        strip_tags($descriptionArray[0]['name']),
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                        FILTER_FLAG_NO_ENCODE_QUOTES
                    ),
                    'description' => filter_var(
                        strip_tags($descriptionArray[0]['description_short']),
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                        FILTER_FLAG_NO_ENCODE_QUOTES
                    ),
                    'reference' => filter_var(
                        strip_tags($joinResult[$indexProduct]['reference']),
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                        FILTER_FLAG_NO_ENCODE_QUOTES
                    ),
                    'inventory' => array(
                        'unit' => null,
                        'unitCost' => filter_var(
                            $priceArray[0]['wholesale_price'],
                            FILTER_VALIDATE_FLOAT
                        ),
                        'initialQuantity' => filter_var(
                            $quantityArray[0]['quantity'],
                            FILTER_VALIDATE_INT
                        ),
                    ),
                    'tax' => array(
                        'alegra' => $taxAlegra,
                        'store' => $taxStore,
                        'value' => $taxValue,
                        'name' => $taxName,
                    ),
                    'price' => filter_var(
                        $priceArray[0]['price'],
                        FILTER_VALIDATE_FLOAT
                    )
                );
            }
        }

        return $productsArray;
    }

    private function processInvoiceCreate($productData)
    {
        $postValues = Tools::getAllValues();
        $keys = array_keys($postValues);

        $products = array();
        $observations = array();
        foreach ($keys as $key) {
            if ($key != 'fc' && $key != 'controller' && $key != 'module' && $key != 'ProductCreate') {
                if (Tools::strrpos($key, 'product_option') !== false) {
                    $value = explode('_', $key);
                    $productData[$value[2]]['tax'] = $productData[$value[2]]['tax']['alegra'];
                    $productData[$value[2]]['inventory']['unit'] = Tools::getValue('product_unit_' . $value[2]);
                    $observations[$value[2]] = 'Unidad: ' . Tools::getValue('product_unit_' . $value[2]) . ' | ' .
                        Tools::getValue('product_observations_' . $value[2]);
                    if (Tools::getValue($key) == 'upload') {
                        $products[] = $value[2];
                    } elseif (Tools::getValue($key) == 'ignore') {
                        $this->dbInsert(
                            'mtsalegraapi_products',
                            array(
                                'id_product_store' => $value[2],
                                'id_product_alegra' => 0,
                                'product_ignored' => 1,
                                'observations' => $observations[$value[2]]
                            )
                        );
                    }
                }
            }
        }

        $request = array();

        foreach ($products as $key) {
            $request[$key] = $this->sendToApi($this->urlApi, 'post', $productData[$key]);
        }

        if (count($request) > 0) {
            foreach ($request as $customer => $data) {
                if ($data[0] == true) {
                    $this->dbInsert(
                        'mtsalegraapi_products',
                        array(
                            'id_product_store' => $customer,
                            'id_product_alegra' => $data[1]['id'],
                            'product_ignored' => 0,
                            'observations' => $observations[$customer]
                        )
                    );
                }
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
