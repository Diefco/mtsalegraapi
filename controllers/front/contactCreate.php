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

        $mtsSql = new DbQuery();
        $mtsSql->select('*')
               ->from('mtsalegraapi_contacts');
        $mts_contact = Db::getInstance()->executeS($mtsSql);

        $storeSql = new DbQuery();
        $storeSql->select('*')
                 ->from('customer');
        $store_contact = Db::getInstance()->executeS($storeSql);

        if (count($mts_contact) != 0) {
            echo "To do <br>";

        } else {
            //  First Execution (Module recently installed)
            if (count($store_contact) > 0 && $store_contact[0]['id_customer'] == 1 && $store_contact[0]['firstname'] = "John") {
                echo "Have to ignore this guy <br>";
                $response = Db::getInstance()->insert('mtsalegraapi_contacts', array(
                    'id_contact_store'  => 1,
                    'id_contact_alegra' => 0,
                    'contact_ignored' => true
                ));

                echo "<pre>";
                print_r($response);
                echo "</pre>";
            }
        }

//        echo "<pre>";
//        print_r($mts_contact);
//        echo "<br>";
//        print_r($store_contact);
//        echo "</pre>";
        echo "<br>";

        die('ContactCreate');
    }
}
