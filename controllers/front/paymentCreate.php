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

class MtsAlegraApiPaymentCreateModuleFrontController extends ModuleFrontController
{
    public $urlApi = 'invoices';

    public function initContent()
    {
        parent::initContent();

        $cookie = new Cookie('session');

        // Validate if the current user is Authorized.
        $this->validateCookieAuth($cookie);

        // Execute the auto-ignore for Demo products
        $this->firstInvoiceCall();

        $mts_join = $this->dbQueryJoin(
            'ps_orders.id_order, 
            ps_orders.id_customer, 
            ps_orders.id_cart, 
            ps_orders.payment, 
            ps_orders.module, 
            ps_orders.total_paid_tax_incl, 
            ps_orders.total_paid_tax_excl',
            'orders',
            array(
                array(
                    'table' => 'mtsalegraapi_invoices',
                    'alias' => null,
                    'on' => 'ps_orders.id_order = ps_mtsalegraapi_invoices.id_order_store'
                )
            ),
            'ps_mtsalegraapi_invoices.id_order_alegra is NULL AND
            (ps_orders.current_state = ' . Configuration::get('PS_OS_PAYMENT') . ' OR 
            ps_orders.current_state = ' . Configuration::get('PS_OS_WS_PAYMENT') .')'
        );

        $invoices = array();

        foreach ($mts_join as $invoiceData) {
            if (array_key_exists($invoiceData['id_order'], $invoices) === false) {
                $invoices[$invoiceData['id_order']] = array();
            }

            if (array_key_exists($invoiceData['id_customer'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['id_customer'] = $invoiceData['id_customer'];
            }

            if (array_key_exists($invoiceData['id_cart'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['id_cart'] = $invoiceData['id_cart'];
            }

            if (array_key_exists($invoiceData['payment'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['payment'] = $invoiceData['payment'];
            }

            if (array_key_exists($invoiceData['module'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['module'] = $invoiceData['module'];
            }

            if (array_key_exists($invoiceData['total_paid_tax_incl'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['total_paid_tax_incl'] = filter_var($invoiceData['total_paid_tax_incl'], FILTER_VALIDATE_FLOAT);
            }

            if (array_key_exists($invoiceData['total_paid_tax_excl'], $invoices[$invoiceData['id_order']]) === false) {
                $invoices[$invoiceData['id_order']]['total_paid_tax_excl'] = filter_var($invoiceData['total_paid_tax_excl'], FILTER_VALIDATE_FLOAT);
            }
        }

        if (count($invoices) > 0) {
            $invoiceData = $this->prepareData($invoices);
            $this->context->smarty->assign('invoices', $invoiceData);

            if (Tools::isSubmit('InvoiceCreate')) {
                $this->processInvoiceCreate($invoiceData);
                Tools::redirect($this->context->link->getModuleLink(
                    'mtsalegraapi',
                    'invoiceCreate',
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

    private function firstInvoiceCall()
    {
        $mts_invoice = $this->dbQuery(
            'id_order_store',
            'mtsalegraapi_invoices',
            null,
            null,
            1
        );

        if (count($mts_invoice) == 0) {
            $store_invoice = $this->dbQuery(
                'id_order, id_customer',
                'orders',
                null,
                'id_order',
                5
            );

            //  First Execution (Module recently installed)
            if (count($store_invoice) > 0 &&
                $store_invoice[0]['id_order'] == 1 && $store_invoice[0]['id_customer'] = 1 &&
                    $store_invoice[1]['id_order'] == 2 && $store_invoice[1]['id_customer'] = 1 &&
                        $store_invoice[2]['id_order'] == 3 && $store_invoice[2]['id_customer'] = 1 &&
                            $store_invoice[3]['id_order'] == 4 && $store_invoice[3]['id_customer'] = 1 &&
                                $store_invoice[4]['id_order'] == 5 && $store_invoice[4]['id_customer'] = 1) {
                $products = array();
                for ($i = 1; $i <= 5; $i++) {
                    $products[] = array(
                        'id_order_store' => $i,
                        'id_order_alegra' => 0,
                        'invoice_ignored' => true
                    );
                }

                Db::getInstance()->insert('mtsalegraapi_invoices', $products);
            }
        }
    }

    private function dbQueryJoin($select, $from, $leftJoin = null, $where = null, $orderBy = null, $limit = null)
    {
        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        $sql = new DbQuery();
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

        $invoices = array();
        foreach ($joinResult as $id_order => $order_detail) {

            $date = date('Y-m-d ');
            $dueDate = date('Y-m-d', strtotime('+1 day'));

            $clientAlegra = $this->dbQuery(
                'id_contact_alegra',
                'mtsalegraapi_contacts',
                'id_contact_store = ' . $order_detail['id_customer'],
                'id_contact_store',
                1
            );

            $clientStore = $this->dbQuery(
                'id_customer, company, ape, siret',
                'customer',
                'id_customer = ' . $order_detail['id_customer'],
                'id_customer',
                1
            );

            $itemsPrev = $this->dbQueryJoin(
                'ps_cart_product.id_product, 
                ps_cart_product.id_product_attribute, 
                ps_cart_product.quantity, 
                ps_product.price as productPrice, 
                ps_product_attribute.price as attributePrice,
                ps_tax_rule.id_tax',
                'cart_product',
                array(
                    array(
                        'table' => 'product',
                        'alias' => null,
                        'on' => 'ps_product.id_product = ps_cart_product.id_product',
                    ),
                    array(
                        'table' => 'product_attribute',
                        'alias' => null,
                        'on' => 'ps_cart_product.id_product_attribute = ps_product_attribute.id_product_attribute',
                    ),
                    array(
                        'table' => 'orders',
                        'alias' => null,
                        'on' => 'ps_orders.id_order  = ' . $id_order,
                    ),
                    array(
                        'table' => 'tax_rule',
                        'alias' => null,
                        'on' => 'ps_tax_rule.id_tax_rules_group  = ps_product.id_tax_rules_group',
                    ),
                ),
                'ps_cart_product.id_product_attribute = ps_product_attribute.id_product_attribute AND
                ps_orders.id_order = ' . $id_order . ' AND 
                ps_orders.id_cart = ps_cart_product.id_cart'
            );

            $items = array();

            foreach ($itemsPrev as $item) {
                $query = $this->dbQuery(
                    'id_product_alegra',
                    'mtsalegraapi_products',
                    'id_product_store = ' . $item['id_product'] . ' AND id_attribute_store = ' . $item['id_product_attribute'],
                    'id_product_alegra',
                    1
                );

                $id_tax = null;

                foreach ($relatedTaxes as $tax) {
                    if ($tax['id_tax_store'] == $item['id_tax']) {
                        $id_tax = $tax['id_tax_alegra'];
                    }
                }

                $items[] = array(
                    'id' => $query[0]['id_product_alegra'],
                    'price' => (filter_var($item['productPrice'], FILTER_VALIDATE_FLOAT) + filter_var($item['attributePrice'], FILTER_VALIDATE_FLOAT)),
                    'tax' => array(
                        array(
                            'id' => $id_tax
                        )
                    ),
                    'quantity' => filter_var($item['quantity'], FILTER_VALIDATE_FLOAT)
                );
            }

            $invoices[$id_order] = array(
                'date' => $date,
                'dueDate' => $dueDate,
                'client' => $clientAlegra[0]['id_contact_alegra'],
                'items' => $items,
                'payments' => array(
                    'date' => $date,
                    'account' => array(
                        'id' => 1
                    ),
                    'amount' => $order_detail['total_paid_tax_incl'],
                    'paymentMethod' => 'deposit',
                    'anotation' => 'Pagado por medio de ' . $order_detail['payment'] . ' con el módulo ' . $order_detail['module']
                ),
                'customer_info' => $clientStore
            );
            return $invoices;
        }
        return false;
    }

    private function processInvoiceCreate($invoiceData)
    {
        $postValues = Tools::getAllValues();
        $keys = array_keys($postValues);

        $invoices = array();

        foreach ($keys as $key) {
            if (Tools::strrpos($key, 'invoice_option') !== false) {
                $value = explode('_', $key);

                if (Tools::getValue($key) == 'upload') {
                    $invoices[] = $value[2];
                } elseif (Tools::getValue($key) == 'ignore') {
                    $this->dbInsert(
                        'mtsalegraapi_invoices',
                        array(
                            'id_order_store' => $value[2],
                            'id_order_alegra' => 0,
                            'id_payment_alegra' => 0,
                            'invoice_ignored' => 1
                        )
                    );
                }
            }
        }

        foreach ($invoices as $id_order) {
            array_pop($invoiceData[$id_order]);

            $payment = array_pop($invoiceData[$id_order]);

            $request = $this->sendToApi($this->urlApi, 'post', $invoiceData[$id_order]);

            if ($request[0] == true) {
                $paymentComplete = array(
                    'date' => $payment['date'],
                    'invoices' => array(
                        array(
                            'id' => $request[1]['id'],
                            'amount' => $payment['amount']
                        ),
                    ),
                    'bankAccount' => 1
                );

                $paymentRequest = $this->sendToApi('payments', 'post', $paymentComplete);

                if ($paymentRequest[0] == true) {
                    $this->dbInsert(
                        'mtsalegraapi_invoices',
                        array(
                            'id_order_store' => $id_order,
                            'id_order_alegra' => $request[1]['id'],
                            'id_payment_alegra' => $paymentRequest[1]['id'],
                            'invoice_ignored' => 0
                        )
                    );
                } else {
                    $this->dbInsert(
                        'mtsalegraapi_invoices',
                        array(
                            'id_order_store' => $id_order,
                            'id_order_alegra' => $request[1]['id'],
                            'id_payment_alegra' => 0,
                            'invoice_ignored' => 0
                        )
                    );
                }
            }
        }
    }

    private function sendToApi($url, $method, $request = null)
    {
        $methodsAllowed = array(
            'POST',
            'GET',
            'PUT',
            'DELETE'
        );

        $method = Tools::strtoupper($method);

        if (array_search($method, $methodsAllowed) === false) {
            $this->printer('El método no es válido.', __LINE__, false);
            return false;
        } elseif (($method == 'POST' || $method == 'PUT') && $request == null) {
            $this->printer('Si el método es POST o PUT, $request no puede ser NULL', __LINE__, false);
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

        if (array_search(Tools::strtolower($url), $toValidateUrl) === false && $method == 'POST') {
            $this->printer('El ENDPOINT no es válido: ' . $url, __LINE__, false);
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
        if ($method == 'POST' || $method == 'PUT') {
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
