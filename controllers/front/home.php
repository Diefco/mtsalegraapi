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

class MtsAlegraApiHomeModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        // include_once(_PS_MODULE_DIR_.'../config/config.inc.php');
        // include_once(_PS_MODULE_DIR_.'../config/settings.inc.php');
        // include_once(_PS_MODULE_DIR_.'../classes/Cookie.php');

        parent::initContent();

        $cookie = new Cookie('session');

        if ($cookie->auth != true || Tools::getValue('logout') == true) {
            Tools::redirect($this->context->link->getModuleLink('mtsalegraapi', 'login', array(), Configuration::get('PS_SSL_ENABLED')));
        }

        //All URLs Array
        $url = array(
            //Products
            'productConsultOne' => $this->context->link->getModuleLink('mtsalegraapi', 'productConsultOne', array(), Configuration::get('PS_SSL_ENABLED')),
            'productConsultMultiple' => $this->context->link->getModuleLink('mtsalegraapi', 'productConsultMultiple', array(), Configuration::get('PS_SSL_ENABLED')),
            'productCreate' => $this->context->link->getModuleLink('mtsalegraapi', 'productCreate', array(), Configuration::get('PS_SSL_ENABLED')),
            'productEdit' => $this->context->link->getModuleLink('mtsalegraapi', 'productEdit', array(), Configuration::get('PS_SSL_ENABLED')),
            'productDelete' => $this->context->link->getModuleLink('mtsalegraapi', 'productDelete', array(), Configuration::get('PS_SSL_ENABLED')),
            //Contacts
            'contactConsultOne' => $this->context->link->getModuleLink('mtsalegraapi', 'contactConsultOne', array(), Configuration::get('PS_SSL_ENABLED')),
            'contactConsultMultiple' => $this->context->link->getModuleLink('mtsalegraapi', 'contactConsultMultiple', array(), Configuration::get('PS_SSL_ENABLED')),
            'contactCreate' => $this->context->link->getModuleLink('mtsalegraapi', 'contactCreate', array(), Configuration::get('PS_SSL_ENABLED')),
            'contactEdit' => $this->context->link->getModuleLink('mtsalegraapi', 'contactEdit', array(), Configuration::get('PS_SSL_ENABLED')),
            'contactDelete' => $this->context->link->getModuleLink('mtsalegraapi', 'contactDelete', array(), Configuration::get('PS_SSL_ENABLED')),
            //Invoices
            'invoiceConsultOne' => $this->context->link->getModuleLink('mtsalegraapi', 'invoiceConsultOne', array(), Configuration::get('PS_SSL_ENABLED')),
            'invoiceConsultMultiple' => $this->context->link->getModuleLink('mtsalegraapi', 'invoiceConsultMultiple', array(), Configuration::get('PS_SSL_ENABLED')),
            'invoiceCreate' => $this->context->link->getModuleLink('mtsalegraapi', 'invoiceCreate', array(), Configuration::get('PS_SSL_ENABLED')),
            'invoiceEdit' => $this->context->link->getModuleLink('mtsalegraapi', 'invoiceEdit', array(), Configuration::get('PS_SSL_ENABLED')),
            'invoiceDelete' => $this->context->link->getModuleLink('mtsalegraapi', 'invoiceDelete', array(), Configuration::get('PS_SSL_ENABLED')),
        );

        $this->context->smarty->assign('urlArray', $url);
        $this->context->smarty->assign('urlLogOut', $this->context->link->getModuleLink('mtsalegraapi', 'home', array(), Configuration::get('PS_SSL_ENABLED')));
        $this->setTemplate('home.tpl');
    }
}
