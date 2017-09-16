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

class MtsAlegraApiContactConsultMultipleModuleFrontController extends ModuleFrontController
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

        /**
         * https://app.alegra.com/api/v1/contacts/<GET_params>
         * The next parameters can be sent by GET method
         * @var int     start               Opcional    Must contain the Contact ID registered on Alegra. This ID will
         *                                              be the product when the consult start
         * @var int     limit               Opcional    Must contain the quantity of products to be consulted. Max
         *                                              number is 30
         * @var string  order_direction     Opcional    (ASC o DESC) Sort ascending or descending the products listed in
         *                                              the query
         * @var string  order_field         Opcional    (id, name, reference, description) Order the products listed in
         *                                              the query, according to the selected column
         * @var string  query               Opcional    String of characters that will be part of the search
         */

        $queryArray = array();

        if (
            Tools::getIsset('start') &&
            Tools::getValue('start') != ''
        ) {
            $queryArray['start'] = Tools::getValue('start');
        }

        if (Tools::getIsset('limit') &&
            Tools::getValue('limit') != '' &&
            Tools::getValue('limit') <= 30
        ) {
            $queryArray['limit'] = Tools::getValue('limit');
        }

        if (Tools::getIsset('order_direction') && (
                Tools::getValue('order_direction') == 'ASC' ||
                Tools::getValue('order_direction') == 'DESC'
            )
        ) {
            $queryArray['order_direction'] = Tools::getValue('order_direction');
        }

        if (
            Tools::getIsset('order_field') &&
            Tools::getValue('order_field') != ''
        ) {
            $queryArray['order_field'] = Tools::getValue('order_field');
        }

        if (
            Tools::getIsset('query') &&
            Tools::getValue('query') != ''
        ) {
            $queryArray['query'] = Tools::getValue('query');
        }

        if (
            Tools::getIsset('type') &&
            Tools::getValue('type') != ''
        ) {
            $queryArray['type'] = Tools::getValue('type');
        }

        if (
            Tools::getIsset('metadata') &&
            Tools::getValue('metadata') != ''
        ) {
            $queryArray['metadata'] = Tools::getValue('metadata');
        }

        $queryTemp = array();

        foreach ($queryArray as $key => $value) {
            $queryTemp[] = $key . '=' . $value;
        }

        if (count($queryArray) >= 1) {
            $queryURL = implode('&', $queryTemp);
            $url = 'https://app.alegra.com/api/v1/contacts/?' . $queryURL;
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $authToken
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $jsonRequest = curl_exec($ch);

            $contactList = json_decode($jsonRequest, true);

            $this->context->smarty->assign('contactList', $contactList);
        }
        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink(
            'mtsalegraapi',
            'home',
            array(),
            Configuration::get('PS_SSL_ENABLED')
        ));
        $this->setTemplate('contacts/consultMultiple.tpl');
    }
}
