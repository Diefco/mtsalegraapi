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
            Tools::redirect($this->context->link->getModuleLink('mtsalegraapi', 'login', array(), Configuration::get('PS_SSL_ENABLED')));
        }

        echo "<pre>";
        print_r(Tools::getAllValues());
        echo "</pre>";

        $storeSql = new DbQuery();
        $storeSql->select('id_customer, firstname, lastname, email')
                 ->from('customer');
        $store_contact = Db::getInstance()->executeS($storeSql);

        $mtsSql = new DbQuery();
        $mtsSql->select('id_contact_store')
            ->from('mtsalegraapi_contacts');
        $mts_contact = Db::getInstance()->executeS($mtsSql);

        //  First Execution (Module recently installed)
        if (count($store_contact) > 0 && count($mts_contact) == 0 && $store_contact[0]['id_customer'] == 1 && $store_contact[0]['firstname'] = "John" && $store_contact[0]['lastname'] = "DOE" && $store_contact[0]['email'] = "pub@prestashop.com") {
            Db::getInstance()->insert('mtsalegraapi_contacts', array(
                'id_contact_store'  => 1,
                'id_contact_alegra' => 0,
                'contact_ignored' => true
            ));
        }

        $mtsQuery = new DbQuery();
        $mtsQuery->select('id_customer')
            ->from('customer')
            ->leftJoin('mtsalegraapi_contacts',null,'ps_customer.id_customer = ps_mtsalegraapi_contacts.id_contact_store')
            ->where('ps_mtsalegraapi_contacts.id_contact_alegra is NULL');
        $mts_join = Db::getInstance()->executeS($mtsQuery);

        $customerBundle = array();

        foreach ($mts_join as $key => $valueInfo){
            //  Requesting necessary customer information
            $customerInfoQuery = new DbQuery();
            $customerInfoQuery->select('id_customer, firstname, lastname, email, company, date_upd')
                              ->from('customer')
                              ->where('id_customer='.$valueInfo['id_customer']);
            $customerInfo = Db::getInstance()->executeS($customerInfoQuery);

            //  Requesting necessary customer address
            $customerAddressQuery = new DbQuery();
            $customerAddressQuery->select('dni, phone, phone_mobile, alias, company, address1, address2, city, id_country, id_state, date_upd')
                                 ->from('address')
                                 ->where('id_customer='.$valueInfo['id_customer']);
            $customerAddress = Db::getInstance()->executeS($customerAddressQuery);

            foreach ($customerAddress as $keyAddress => $valueAddress) {
                //  Requesting country of customer address
                $countryAddressQuery = new DbQuery();
                $countryAddressQuery->select('iso_code')
                                     ->from('country')
                                     ->where('id_country='.$valueAddress['id_country']);
                $countryAddress = Db::getInstance()->executeS($countryAddressQuery);

                //  Requesting state of customer address
                $stateAddressQuery = new DbQuery();
                $stateAddressQuery->select('name')
                    ->from('state')
                    ->where('id_state='.$valueAddress['id_state']);
                $stateAddress = Db::getInstance()->executeS($stateAddressQuery);

                $customerAddress[$keyAddress]['iso_code_country'] = $countryAddress[0]['iso_code'];
                $customerAddress[$keyAddress]['state_name'] = $stateAddress[0]['name'];
            }

            $customerInfo = array('info' => $customerInfo[0]);

            $customerInfo['address'] = $customerAddress;

            $customerBundle[] = $customerInfo;
        }

        $customersArray = array();
        $dniCompilation = array();

        foreach ($customerBundle as $key => $customer) {
            $customersArray[$key] = array();
            if (count($customer) > 0) {
                $customersArray[$key]['id'] = $customer['info']['id_customer'];
                $customersArray[$key]['name'] = $customer['info']['firstname'] . ' ' . $customer['info']['lastname'];
                $customersArray[$key]['email'] = $customer['info']['email'];
                $customersArray[$key]['addressData'] = $this->joinInlineData(array(
                    'alias' => $this->uniqueDataArray($customer, 'address', 'alias'),
                    'dni' => $this->uniqueDataArray($customer, 'address', 'dni'),
                    'address1' => $this->uniqueDataArray($customer, 'address', 'address1'),
                    'address2' => $this->uniqueDataArray($customer, 'address', 'address2'),
                    'city' => $this->uniqueDataArray($customer, 'address', 'city'),
                    'state' => $this->uniqueDataArray($customer, 'address', 'state_name'),
                    'country' => $this->uniqueDataArray($customer, 'address', 'iso_code_country'),
                    'phone' => $this->uniqueDataArray($customer, 'address', 'phone'),
                    'phone_mobile' => $this->uniqueDataArray($customer, 'address', 'phone_mobile'),
                ));
            }


        }

        for ($i = 0; $i < count($customersArray); $i++){
            $dniCompilation[$i] = array();
            for ($k = 0; $k < count($customersArray[$i]['addressData']); $k++){
                $dniCompilation[$i][$k] = $customersArray[$i]['addressData'][$k]['dni'];
            }
        }

        foreach ($dniCompilation as $customerKey => $customer) {
            if (count($customer) > 0 && count(array_unique($customer)) <= 1) {
                $customersArray[$customerKey]['dniUnique'] = 'true';
            } else {
                $customersArray[$customerKey]['dniUnique'] = 'false';
            }
        }

        $this->context->smarty->assign('customers', $customersArray);
        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink('mtsalegraapi', 'home', array(), Configuration::get('PS_SSL_ENABLED')));
        $this->setTemplate('contacts/create.tpl');
    }

    private function uniqueDataArray($array, $index, $subIndex) {
        $arrayData = array();
        foreach ($array[$index] as $subArray) {
            if (count($subArray) > 0) {
                $arrayData[] = $subArray[$subIndex];
            }
        }
        return $arrayData;
    }

    private function joinInlineData($metaArray) {
        $indexedArray = array();
        $arrayKeys = array();
        $condensed = array();

        foreach ($metaArray as $keyMeta => $valueMeta) {
            $indexedArray[$keyMeta] = count($valueMeta);
            $arrayKeys[] = $keyMeta;
        }

        $maxArray = max($indexedArray);

        for ($i = 0; $i < $maxArray; $i++) {
            foreach ($arrayKeys as $value){
                $condensed[$i][$value] = $metaArray[$value][$i];
            }
        }

        return $condensed;
    }
}
