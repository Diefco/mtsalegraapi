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
        $sql
            ->select('id_contact_store')
            ->from('mtsalegraapi_contacts')
            ->limit('1')
            ->orderBy('id_contact_store');
        $mts_contact = Db::getInstance()->executeS($sql);

        if (count($mts_contact) == 0) {
            $sql = new DbQuery();
            $sql
                ->select('id_customer, firstname, lastname, email')
                ->from('customer')
                ->limit('1')
                ->orderBy('id_customer');
            $store_contact = Db::getInstance()->executeS($sql);

            //  First Execution (Module recently installed)
            if (count($store_contact) > 0 &&
                $store_contact[0]['id_customer'] == 1 &&
                $store_contact[0]['firstname'] = "John" &&
                    $store_contact[0]['lastname'] = "DOE" &&
                        $store_contact[0]['email'] = "pub@prestashop.com"
            ) {
                Db::getInstance()->insert('mtsalegraapi_contacts', array(
                    'id_contact_store' => 1,
                    'id_contact_alegra' => 0,
                    'contact_ignored' => true
                ));
            }
        }

        $sql = new DbQuery();
        $sql
            ->select('id_customer')
            ->from('customer')
            ->leftJoin(
                'mtsalegraapi_contacts',
                null,
                'ps_customer.id_customer = ps_mtsalegraapi_contacts.id_contact_store'
            )
            ->where(
                'ps_mtsalegraapi_contacts.id_contact_alegra is NULL ||
                ps_mtsalegraapi_contacts.contact_ignored is NULL '
            )
            ->limit($limitQuery)
            ->orderBy('id_customer');
        $mts_join = Db::getInstance()->executeS($sql);

        $contactArray = array();

        $contactIdWhereQuery = '';

        if (count($mts_join) == 1) {
            $contactIdWhereQuery .= 'id_customer=' . $mts_join[0]['id_customer'];
        } elseif (count($mts_join) > 1) {
            for ($i = 0; $i < count($mts_join) - 1; $i++) {
                $contactIdWhereQuery .= 'id_customer=' . $mts_join[$i]['id_customer'] . ' || ';
            }
            $contactIdWhereQuery .= 'id_customer=' . $mts_join[count($mts_join) - 1]['id_customer'];
        }

        // Get the list with all taxes registered in the Store
        $sql = new DbQuery();
        $sql->select('id_customer, firstname, lastname, email')
            ->from('customer')
            ->where($contactIdWhereQuery)
            ->orderBy('id_customer');
        $contactBasicInfo = Db::getInstance()->executeS($sql);

        if (count($contactBasicInfo) > 0) {
            foreach ($mts_join as $indexContact => $contact) {
                $contactArray[$contact['id_customer']] = array(
                    'name' => null,
                    'identification' => null,
                    'email' => null,
                    'phonePrimary' => null,
                    'phoneSecondary' => null,
                    'mobile' => null,
                    'type' => null,
                    'address' => array(
                        'address' => null,
                        'city' => null
                    ),
                );

                // Get the short description for each product
                $sql = new DbQuery();
                $sql->select('id_address, id_country, id_state, alias, address1, address2, postcode, city, phone,
                        phone_mobile')
                    ->from('address')
                    ->where('id_customer = ' . $contact['id_customer'])
                    ->orderBy('id_address');
                $addressArray = Db::getInstance()->executeS($sql);
                $this->printer($addressArray, __LINE__, false);
            }
        }

        $this->printer($contactBasicInfo, __LINE__);
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

    public function printer($var, $line = false, $die = true, $debug = false)
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
