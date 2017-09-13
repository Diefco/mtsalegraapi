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

        /**
         * https://app.alegra.com/api/v1/invoices/<id_invoice>
         * @var int id_product      Required       Must contain the Invoice ID registered on ALEGRA
         */


        $url = 'https://app.alegra.com/api/v1/invoices/';
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . base64_encode(Configuration::get('mts_AlgApi_Email') . ':' . Configuration::get('mts_AlgApi_Token'))
        );

//        $array = array(
//            'date' => '2017-09-12',
//            'dueDate' => '2017-09-19',
//            'client' => 1,
//            'items' => array(
//                array(
//                    'id' => 1,
//                    'price' => 50000,
//                    'quantity' => 10
//                ),
//                array(
//                    'id' => 3,
//                    'price' => 35000,
//                    'quantity' => 7
//                )
//            )
//        );
//
//        $jsonArray = json_encode($array);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $jsonRequest = curl_exec($ch);
        $invoice = json_decode($jsonRequest, true);

        echo "<pre>";
        print_r($invoice);
        echo "</pre>";
        die();

        $this->context->smarty->assign('invoice', $invoice);


        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink('mtsalegraapi', 'home', array(), Configuration::get('PS_SSL_ENABLED')));
        $this->setTemplate('invoices/consultOne.tpl');
    }
}
