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

class MtsAlegraApiLoginModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        // include_once(_PS_MODULE_DIR_.'../config/config.inc.php');
        // include_once(_PS_MODULE_DIR_.'../config/settings.inc.php');

        parent::initContent();

        $cookie = new Cookie('session');

        if (Tools::getValue('user') == Configuration::get('mts_AlgApi_User') &&
            password_verify(Tools::getValue('password'), Configuration::get('mts_AlgApi_Password'))
        ) {
            $cookie->auth = true;
            Tools::redirect($this->context->link->getModuleLink(
                'mtsalegraapi',
                'home',
                array(),
                Configuration::get('PS_SSL_ENABLED')
            ));
        } else {
            $cookie->auth = false;
            $this->context->smarty->assign('error', 'Ingrese un usuario y/o contraseña.');
        }

        $url = $this->context->link->getModuleLink(
            'mtsalegraapi',
            'login',
            array(),
            Configuration::get('PS_SSL_ENABLED')
        );
        $this->context->smarty->assign('urlForm', $url);
        $this->setTemplate('login.tpl');
    }
}
