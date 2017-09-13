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

class MtsAlegraApiProductConsultOneModuleFrontController extends ModuleFrontController
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
         * https://app.alegra.com/api/v1/items/<id_product>
         * @var int id_product      Required       Must contain the Product ID registered on ALEGRA
         */

        $id_product = filter_var(Tools::getValue('id_product'), FILTER_SANITIZE_NUMBER_INT);

        if ($id_product != null && $id_product != 0 && $id_product != '') {
            $url = 'https://app.alegra.com/api/v1/items/'.$id_product;
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . base64_encode(Configuration::get('mts_AlgApi_Email') . ':' . Configuration::get('mts_AlgApi_Token'))
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $jsonRequest = curl_exec($ch);

            $product = json_decode($jsonRequest, true);

            $this->context->smarty->assign('product', $product);
        } elseif ($id_product == 0) {
            $this->context->smarty->assign('errorBO', true);
        }

        $this->context->smarty->assign('backLink', $this->context->link->getModuleLink('mtsalegraapi', 'home', array(), Configuration::get('PS_SSL_ENABLED')));
        $this->setTemplate('products/consultOne.tpl');
    }
}
