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

class MtsAlegraApiContactCreateModuleFrontController extends ModuleFrontController
{
    public $urlApi = 'contacts';

    public function initContent()
    {
        parent::initContent();

        $cookie = new Cookie('session');

        // Validate if the current user is Authorized.
        $this->validateCookieAuth($cookie);

        // Execute the auto-ignore for Demo Customer
        $this->firstCustomerCall();

        $mts_join = $this->dbQueryJoin(
            'ps_customer.id_customer',
            'customer',
            array(
                array(
                    'table' => 'orders',
                    'alias' => null,
                    'on' => 'ps_orders.id_customer = ps_customer.id_customer'
                ),
                array(
                    'table' => 'mtsalegraapi_contacts',
                    'alias' => null,
                    'on' => 'ps_customer.id_customer = ps_mtsalegraapi_contacts.id_contact_store'
                )
            ),
            'ps_mtsalegraapi_contacts.id_contact_alegra is NULL AND (ps_orders.current_state = 2 OR ps_orders.current_state = 12)'
        );

        if (count($mts_join) > 0) {
            $customerData = $this->prepareData($mts_join);
            $this->context->smarty->assign('customers', $customerData);

            if (Tools::isSubmit('CustomerCreate')) {
                $this->processCustomerCreate($customerData);
                Tools::redirect($this->context->link->getModuleLink(
                    'mtsalegraapi',
                    'contactCreate',
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
        $this->setTemplate('contacts/create.tpl');
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

    private function firstCustomerCall()
    {
        $mts_contact = $this->dbQuery(
            'id_contact_store',
            'mtsalegraapi_contacts',
            null,
            'id_contact_store',
            1
        );

        if (count($mts_contact) == 0) {
            $store_contact = $this->dbQuery(
                array(
                    'id_customer',
                    'firstname',
                    'lastname',
                    'email'
                ),
                'customer',
                null,
                'id_customer',
                1
            );

            //  First Execution (Module recently installed)
            if (count($store_contact) > 0 &&
                $store_contact[0]['id_customer'] == 1 &&
                $store_contact[0]['firstname'] = "John" &&
                    $store_contact[0]['lastname'] = "DOE" &&
                        $store_contact[0]['email'] = "pub@prestashop.com"
            ) {
                $this->dbInsert(
                    'mtsalegraapi_contacts',
                    array(
                        'id_contact_store' => 1,
                        'id_contact_alegra' => 0,
                        'contact_ignored' => true,
                        'observations' => 'Customer Demo'
                    )
                );
            }
        }
    }

    private function dbQueryJoin($select, $from, $leftJoin, $where = null, $orderBy = null)
    {
        // Get the limit number to send a DB Query.
        $limitQuery = Configuration::get('mts_AlgApi_limitQuery');

        if (is_array($select)) {
            $select = implode(', ', $select);
        }

        $sql =  new DbQuery();
        $sql->select($select)
            ->from($from);

        foreach ($leftJoin as $query) {
            $sql->leftJoin($query['table'], $query['alias'], $query['on']);
        }

        $sql->where($where);

        if ($orderBy != null) {
            $sql->orderBy($orderBy);
        }

        $sql->limit($limitQuery);

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
        $contactArray = array();

        $contactIdWhereQuery = '';

        if (count($joinResult) == 1) {
            $contactIdWhereQuery .= 'id_customer=' . $joinResult[0]['id_customer'];
        } elseif (count($joinResult) > 1) {
            for ($i = 0; $i < count($joinResult) - 1; $i++) {
                $contactIdWhereQuery .= 'id_customer=' . $joinResult[$i]['id_customer'] . ' || ';
            }
            $contactIdWhereQuery .= 'id_customer=' . $joinResult[count($joinResult) - 1]['id_customer'];
        }

        $contactBasicInfo = $this->dbQuery(
            array(
                'id_customer',
                'firstname',
                'lastname',
                'email',
                'siret',
                'ape',
                'company'
            ),
            'customer',
            $contactIdWhereQuery,
            'id_customer'
        );

        if (count($contactBasicInfo) > 0) {
            foreach ($contactBasicInfo as $indexContact => $contact) {
                $addressArray = $this->dbQuery(
                    array(
                        'id_address',
                        'id_country',
                        'id_state',
                        'alias',
                        'address1',
                        'address2',
                        'postcode',
                        'city',
                        'phone',
                        'phone_mobile'
                    ),
                    'address',
                    'id_customer = ' . $contact['id_customer'],
                    'id_address'
                );

                $addressComplete = $addressArray[0]['address1'];
                if ($addressArray[0]['address2'] != null || $addressArray[0]['address2'] != '') {
                    $addressComplete .= $addressArray[0]['address2'];
                }

                $cityComplete = $addressArray[0]['city'];

                if ($addressArray[0]['id_state'] != null ||
                    $addressArray[0]['id_state'] != '' ||
                    $addressArray[0]['id_state'] != 0
                ) {
                    $stateArray = $this->dbQuery(
                        'name',
                        'state',
                        'id_state = ' . $addressArray[0]['id_state']
                    );
                    $cityComplete .= ' / ' . $stateArray[0]['name'];
                }

                if ($addressArray[0]['id_country'] != null ||
                    $addressArray[0]['id_country'] != '' ||
                    $addressArray[0]['id_country'] != 0
                ) {
                    $countryArray = $this->dbQuery(
                        'iso_code',
                        'country',
                        'id_country = ' . $addressArray[0]['id_country']
                    );
                    $cityComplete .= ' / ' . $countryArray[0]['iso_code'];
                }

                $contactArray[$contact['id_customer']] = array(
                    'name' => $contact['company'],
                    'identification' => $contact['siret'],
                    'email' => $contact['email'],
                    'phonePrimary' => $addressArray[0]['phone'],
                    'mobile' => $addressArray[0]['phone_mobile'],
                    'type' => 'client',
                    'address' => array(
                        'address' => $addressComplete,
                        'city' => $cityComplete
                    ),
                    'internalContacts' => array(
                        array(
                            'name' => $contact['firstname'],
                            'lstName' => $contact['lastname'],
                            'email' => $contact['email'],
                            'phone' => $addressArray[0]['phone'],
                            'mobile' => $addressArray[0]['phone_mobile'],
                            'sendNotifications' => false
                        ),
                    ),
                    'observations' => 'Tipo de Documento: ' . $contact['ape'] . ' | ',
                );
            }
        }

        return $contactArray;
    }

    private function processCustomerCreate($customerData)
    {
        $postValues = Tools::getAllValues();
        $keys = array_keys($postValues);

        $customers = array();
        foreach ($keys as $key) {
            if ($key != 'fc' && $key != 'controller' && $key != 'module' && $key != 'CustomerCreate') {
                if (Tools::strrpos($key, 'customer_option') !== false) {
                    $value = explode('_', $key);
                    $customerData[$value[2]]['observations'] .= Tools::getValue('customer_observations_' . $value[2]);
                    if (Tools::getValue($key) == 'upload') {
                        $customers[] = $value[2];
                    } elseif (Tools::getValue($key) == 'ignore') {
                        $this->dbInsert(
                            'mtsalegraapi_contacts',
                            array(
                                'id_contact_store' => $value[2],
                                'id_contact_alegra' => 0,
                                'contact_ignored' => 1,
                                'observations' => $customerData[$value[2]]['observations']
                            )
                        );
                    }
                }
            }
        }

        $request = array();

        foreach ($customers as $key) {
            $request[$key] = $this->sendToApi($this->urlApi, 'post', $customerData[$key]);
        }

        if (count($request) > 0) {
            foreach ($request as $customer => $data) {
                if ($data[0] == true) {
                    $this->dbInsert(
                        'mtsalegraapi_contacts',
                        array(
                            'id_contact_store' => $customer,
                            'id_contact_alegra' => $data[1]['id'],
                            'contact_ignored' => 0,
                            'observations' => $data[1]['observations']
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
