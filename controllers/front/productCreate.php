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
    public function initContent()
    {
        // include_once(_PS_MODULE_DIR_.'../config/config.inc.php');
        // include_once(_PS_MODULE_DIR_.'../config/settings.inc.php');
        // include_once(_PS_MODULE_DIR_.'../classes/Cookie.php');

        parent::initContent();

        $cookie = new Cookie('session');

        if ($cookie->auth != true) {
            Tools::redirect($this->context->link->getModuleLink(
                'mtsalegraapi',
                'login',
                array(),
                Configuration::get('PS_SSL_ENABLED')
            ));
        }

        /**
         * !!!DISCLAIMER!!!
         * https://developer.alegra.com/v1/docs/autenticacion
         * Base64 encoding required from ALegra API: Must be used to generate an Authentication Token.
         * Otherwise, this module will not work properly.
         */

        $authToken = base64_encode(
            Configuration::get('mts_AlgApi_Email') . ':' . Configuration::get('mts_AlgApi_Token')
        );

        $limitQuery = Configuration::get('mts_AlgApi_limitQuery');

        $sql = new DbQuery();
        $sql->select('id_product_store')
            ->from('mtsalegraapi_products')
            ->limit('1');
        $mts_product = Db::getInstance()->executeS($sql);

        if (count($mts_product) == 0) {
            $sql = new DbQuery();
            $sql->select('id_product, reference')
                ->from('product')
                ->limit('7')
                ->orderBy('id_product');
            $store_product = Db::getInstance()->executeS($sql);

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
                    $products[] = array (
                        'id_product_store'  => $i,
                        'id_product_alegra' => 0,
                        'product_ignored' => true
                    );
                }

                Db::getInstance()->insert('mtsalegraapi_products', $products);
            }
        }

        $sql = new DbQuery();
        $sql->select('id_product, reference, id_tax_rules_group')
            ->from('product')
            ->leftJoin(
                'mtsalegraapi_products',
                null,
                'ps_product.id_product = ps_mtsalegraapi_products.id_product_store'
            )
            ->where(
                'ps_mtsalegraapi_products.id_product_alegra is NULL || 
                ps_mtsalegraapi_products.product_ignored is NULL'
            )
            ->limit($limitQuery)
            ->orderBy('id_product');
        $mts_join = Db::getInstance()->executeS($sql);


        // Get the list with all taxes registered in the Store
        $sql = new DbQuery();
        $sql->select('id_tax, rate')
            ->from('tax')
            ->orderBy('id_tax');
        $taxesStoreArray = Db::getInstance()->executeS($sql);

        // Get the name of the registered taxes in the Store
        $sql = new DbQuery();
        $sql->select('id_tax, name')
            ->from('tax_lang')
            ->where('id_lang = 1')
            ->orderBy('id_tax');
        $nameTaxes = Db::getInstance()->executeS($sql);

        foreach ($taxesStoreArray as $index => $tax) {
            if ($tax['id_tax'] === $nameTaxes[$index]['id_tax']) {
                $taxesStoreArray[$index]['name'] = $nameTaxes[$index]['name'];
            }
        }

        $taxesAlegraArray = $this->sendToApi($authToken, 'taxes', 'get', null);

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

            foreach ($mts_join as $indexProduct => $product) {
                $productsArray[$product['id_product']] = array(
                    'name' => null,
                    'description' => null,
                    'reference' => null,
                    'inventory' => array(
                        'unit' => null,
                        'unitCost' => null,
                        'initialQuantity' => null,
                    ),
                    'tax' => array(
                        'alegra' => null,
                        'store' => null,
                        'value' => null,
                        'name' => null,
                    ),
                    'price' => null
                );
                // Get the short description for each product
                $sql = new DbQuery();
                $sql->select('description_short, name')
                    ->from('product_lang')
                    ->where('id_product = ' . $product['id_product'] . ' && id_lang = 1')
                    ->limit('1');
                $descriptionArray = Db::getInstance()->executeS($sql);

                // Get the price for each product (without attributes)
                $sql = new DbQuery();
                $sql->select('price, wholesale_price')
                    ->from('product_shop')
                    ->where('id_product = ' . $product['id_product'])
                    ->limit('1');
                $priceArray = Db::getInstance()->executeS($sql);

                // Get the quantity for each product (without attributes)
                $sql = new DbQuery();
                $sql->select('quantity')
                    ->from('stock_available')
                    ->where('id_product = ' . $product['id_product'])
                    ->limit('1');
                $quantityArray = Db::getInstance()->executeS($sql);

                // Get the tax rules registered in the Store
                $sql = new DbQuery();
                $sql->select('id_tax_rules_group, id_tax, behavior')
                    ->from('tax_rule')
                    ->where('id_tax_rules_group = ' . $product['id_tax_rules_group']);
                $taxRulesArray = Db::getInstance()->executeS($sql);

                if (count($taxRulesArray) != 1 || (
                        $taxRulesArray[0]['behavior'] != 0 || $taxRulesArray[0]['behavior'] != '0'
                    )
                ) {
                    $taxException = true;
                } else {
                    $taxException = false;
                }

                $productsArray[$product['id_product']]['name'] = filter_var(
                    strip_tags($descriptionArray[0]['name']),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    FILTER_FLAG_NO_ENCODE_QUOTES
                );
                $productsArray[$product['id_product']]['description'] = filter_var(
                    strip_tags($descriptionArray[0]['description_short']),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    FILTER_FLAG_NO_ENCODE_QUOTES
                );
                $productsArray[$product['id_product']]['reference'] = filter_var(
                    strip_tags($mts_join[$indexProduct]['reference']),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    FILTER_FLAG_NO_ENCODE_QUOTES
                );
                $productsArray[$product['id_product']]['inventory']['unitCost'] = filter_var(
                    $priceArray[0]['wholesale_price'],
                    FILTER_VALIDATE_FLOAT
                );
                $productsArray[$product['id_product']]['inventory']['initialQuantity'] = filter_var(
                    $quantityArray[0]['quantity'],
                    FILTER_VALIDATE_INT
                );

                if (!$taxException) {
                    foreach ($relatedTaxes as $indexRelatedTax => $relatedTax) {
                        if ($taxRulesArray[0]['id_tax'] == $relatedTax['id_tax_store']) {
                            $productsArray[$product['id_product']]['tax']['alegra'] = filter_var(
                                $relatedTax['id_tax_alegra'],
                                FILTER_VALIDATE_INT
                            );
                            $productsArray[$product['id_product']]['tax']['store'] = filter_var(
                                $relatedTax['id_tax_store'],
                                FILTER_VALIDATE_INT
                            );
                            $productsArray[$product['id_product']]['tax']['value'] = filter_var(
                                $relatedTax['tax_value'],
                                FILTER_VALIDATE_INT
                            );
                            $productsArray[$product['id_product']]['tax']['name'] = $relatedTax['tax_name'];
                        }
                    }
                }
                $productsArray[$product['id_product']]['price'] = filter_var(
                    $priceArray[0]['price'],
                    FILTER_VALIDATE_FLOAT
                );
            }

            $postValues = Tools::getAllValues();

            if (count($postValues) > 3) {
                $apiResponse = $this->validatePostValues(array_keys($productsArray), $authToken);

                foreach ($apiResponse as $idProduct => $response) {
                    $this->printer($response, false, false);
                    if (gettype($response) == 'array') {
                        // Errores o respuesta de la API
                        if ($response[0] == false) {
                            $this->context->smarty->assign(
                                'errorMsg',
                                $response[1]['message'] . '. ID Product: ' . $idProduct
                            );
                        } else {
                            $products = array(
                                'id_product_store' => $idProduct,
                                'id_product_alegra' => $response[1]['id'],
                                'product_ignored' => false
                            );
                            Db::getInstance()->insert('mtsalegraapi_products', $products);
                        }
                    } elseif (gettype($response) == 'string' && $response == 'ignored') {
                        // Ignorar producto
                        $products = array(
                            'id_product_store' => $idProduct,
                            'id_product_alegra' => 0,
                            'product_ignored' => true
                        );
                        Db::getInstance()->insert('mtsalegraapi_products', $products);
                    } else {
                        $this->context->smarty->assign('errorMsg', 'No se ha realizado ninguna acción.');
                    }
                }
                Tools::redirect($this->context->link->getModuleLink(
                    'mtsalegraapi',
                    'productCreate',
                    array(),
                    Configuration::get('PS_SSL_ENABLED')
                ));
            }

            $this->context->smarty->assign('products', $productsArray);
        }

        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink(
            'mtsalegraapi',
            'home',
            array(),
            Configuration::get('PS_SSL_ENABLED')
        ));
        $this->setTemplate('products/create.tpl');
    }

    private function validatePostValues($productsKeys, $authToken)
    {
        $productToSend = array();
        $respuesta = array();

        foreach ($productsKeys as $idProduct) {
            if (Tools::getIsset('product_' . $idProduct . '_option') &&
                Tools::getValue('product_' . $idProduct . '_option') == 'upload'
            ) {
                $productToSend[$idProduct] = array(
                    'name' => null,
                    'description' => null,
                    'reference' => null,
                    'inventory' => array(
                        'unit' => null,
                        'unitCost' => null,
                        'initialQuantity' => null,
                    ),
                    'tax' => null,
                    'price' => null,
                );

                if (Tools::getIsset('product_' . $idProduct . '_name')) {
                    $productToSend[$idProduct]['name'] =
                        Tools::getValue('product_' . $idProduct . '_name');
                }

                if (Tools::getIsset('product_' . $idProduct . '_description')) {
                    $productToSend[$idProduct]['description'] =
                        Tools::getValue('product_' . $idProduct . '_description');
                }

                if (Tools::getIsset('product_' . $idProduct . '_reference')) {
                    $productToSend[$idProduct]['reference'] =
                        Tools::getValue('product_' . $idProduct . '_reference');
                }

                if (Tools::getIsset('product_' . $idProduct . '_unit')) {
                    $productToSend[$idProduct]['inventory']['unit'] =
                        Tools::getValue('product_' . $idProduct . '_unit');
                }

                if (Tools::getIsset('product_' . $idProduct . '_unitCost')) {
                    $productToSend[$idProduct]['inventory']['unitCost'] =
                        (int)Tools::getValue('product_' . $idProduct . '_unitCost');
                }

                if (Tools::getIsset('product_' . $idProduct . '_initialQuantity')) {
                    $productToSend[$idProduct]['inventory']['initialQuantity'] =
                        (int)Tools::getValue('product_' . $idProduct . '_initialQuantity');
                }

                if (Tools::getIsset('product_' . $idProduct . '_tax')) {
                    $productToSend[$idProduct]['tax'] =
                        (int)Tools::getValue('product_' . $idProduct . '_tax');
                }

                if (Tools::getIsset('product_' . $idProduct . '_price')) {
                    $productToSend[$idProduct]['price'] =
                        (int)Tools::getValue('product_' . $idProduct . '_price');
                }

                $respuesta[$idProduct] = $this->sendToApi($authToken, 'items', 'post', $productToSend[$idProduct]);
            } elseif (Tools::getIsset('product_' . $idProduct . '_option') &&
                Tools::getValue('product_' . $idProduct . '_option') == 'ignore'
            ) {
                $respuesta[$idProduct] = 'ignored';
            } else {
                    $respuesta[$idProduct] = 'nothing';
            }
        }
        return $respuesta;
    }

    private function sendToApi($authToken, $url, $method, $request = null)
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

        $urlRequest = 'https://app.alegra.com/api/v1/'.$url.'/';
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

    public function printer($var, $line = false, $die = true, $debug = false)
    {
        echo "<pre>";
        if ($debug) {
            var_dump($var);
        } else {
            print_r($var);
        }
        if ($line) {
            print_r("<br>" .gettype($var) . ' en la línea ' . $line);
        }
        echo "<br></pre>";
        if ($die) {
            die();
        }
    }
}
