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

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mtsalegraapi extends Module
{
    protected $config_form = false;
    private $_postErrors = array();

    public function __construct()
    {
        $this->name = 'mtsalegraapi';
        $this->tab = 'billing_invoicing';
        $this->version = '1.1.0';
        $this->author = 'Metasysco S.A.S.';
        $this->need_instance = 1;
        $this->charset = 'UTF-8';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Metasysco.com - Facturación electrónica a través de Alegra');
        $this->description = $this->l('Plataforma de Facturación electrónica a través de la API de Alegra.');

        $this->confirmUninstall = $this->l('¿Está seguro que desea desinstalar? Se borrará toda la información relacionada con la configuración del módulo.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.1.17');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('mts_AlgApi_Tooltips', 'false');
        Configuration::updateValue('mts_AlgApi_User', '');
        Configuration::updateValue('mts_AlgApi_Password', '');
        Configuration::updateValue('mts_AlgApi_Email', '');
        Configuration::updateValue('mts_AlgApi_Token', '');
        Configuration::updateValue('mts_AlgApi_limitQuery', '5');

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('mts_AlgApi_Tooltips');
        Configuration::deleteByName('mts_AlgApi_User');
        Configuration::deleteByName('mts_AlgApi_Password');
        Configuration::deleteByName('mts_AlgApi_Email');
        Configuration::deleteByName('mts_AlgApi_Token');
        Configuration::deleteByName('mts_AlgApi_limitQuery');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * Declaration of variable who will contain all messages.
         */

        $messages = null;

        /**
         * If the Tooltip configuration was sent, confirm the settings saved.
         * If have errors, will be showed in the BO, else will show a confirmation message.
         */

        if (Tools::isSubmit('btnTooltipSubmit')) {
            // $this->postValidation('form_data');

            if (!count($this->_postErrors)) {
                $this->postProcess();
                $messages .= $this->displayConfirmation($this->l('Configuración de ayudas actualizada.'));
            } else {
                foreach ($this->_postErrors as $err) {
                    $messages .= $this->displayError($err);
                }
            }
        }

        /**
         * If the Platform Login Form was sent, confirm if all fields was filled.
         * If have errors, will be showed in the BO, else will show a confirmation message.
         */

        if (Tools::isSubmit('btnPlatformSubmit')) {
            $this->postValidation('form_data');

            if (!count($this->_postErrors)) {
                $this->postProcess();
                $messages .= $this->displayConfirmation($this->l('Información de inicio de sesión actualizada.'));
            } else {
                foreach ($this->_postErrors as $err) {
                    $messages .= $this->displayError($err);
                }
            }
        }

        /**
         * If the API Data Form was sent, confirm if all fields was filled.
         * If have errors, will be showed in the BO, else will show a confirmation message.
         */

        if (Tools::isSubmit('btnAPISubmit')) {
            $this->postValidation('api_data');

            if (!count($this->_postErrors)) {
                $this->postProcess();
                $messages .= $this->displayConfirmation($this->l('Información de la API actualizada.'));
            } else {
                foreach ($this->_postErrors as $err) {
                    $messages .= $this->displayError($err);
                }
            }
        }

        if (Tools::getValue('mts_AlgApi_Tooltips')) {
            $defineShowTooltip = "true";
        } else {
            $defineShowTooltip = "false";
        }

        $this->context->smarty->assign('displayTooltip', $defineShowTooltip);

        /**
         * Set in a smarty variable, the module dir path
         */
        $this->context->smarty->assign('moduleLoginLink', $this->context->link->getModuleLink('mtsalegraapi', 'login', array(), Configuration::get('PS_SSL_ENABLED')));
        $this->context->smarty->assign('module_dir', $this->_path);

        /**
         * Define a variable with a template HTML content
         */

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $messages . $this->renderForm();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if ($key == 'mts_AlgApi_Password') {
                Configuration::updateValue($key, password_hash(Tools::getValue($key), PASSWORD_BCRYPT));
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $config = Configuration::getMultiple(
            array(
                'mts_AlgApi_Tooltips',
                'mts_AlgApi_User',
                'mts_AlgApi_Password',
                'mts_AlgApi_limitQuery',
                'mts_AlgApi_Email',
                'mts_AlgApi_Token',
            )
        );

        return $config;
    }

    private function postValidation($form)
    {
        if ($form == 'form_data') {
            $user = Tools::getValue('mts_AlgApi_User');
            if (!$user || Tools::strlen($user) <= 7) {
                $this->_postErrors[] = $this->l('El Usuario es requerido para el inicio de sesión. No puede ser vacío ni contener menos de 8 carácteres.');
            }

            $pass = Tools::getValue('mts_AlgApi_Password');
            if (!$pass || Tools::strlen($pass) <= 7) {
                $this->_postErrors[] = $this->l('La Contraseña es requerida para el inicio de sesión. No puede ser vacío ni contener menos de 8 carácteres.');
            }
        }

        if ($form == 'api_data') {
            if (!Tools::getValue('mts_AlgApi_Email')) {
                $this->_postErrors[] = $this->l('El Email es requerido para la API. Sin esta información no podrá comunicarse con la plataforma de Alegra.');
            }

            if (!Tools::getValue('mts_AlgApi_Token')) {
                $this->_postErrors[] = $this->l('El Token es requerido para la API. Sin esta información no podrá comunicarse con la plataforma de Alegra.');
            }
        }
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMtspayuapiModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($this->getConfigForm());
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        $limitOptions = array(
            'query' => array(
                array(
                    'id_limit' => '5',
                    'name' => '5'
                ),
                array(
                    'id_limit' => '10',
                    'name' => '10'
                ),
                array(
                    'id_limit' => '20',
                    'name' => '20'
                ),
                array(
                    'id_limit' => '30',
                    'name' => '30'
                ),
            ),
            'id' => 'id_limit',
            'name' => 'name'
        );

        $tooltip_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuración de ayudas'),
                    'icon' => 'icon-info',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('¿Desea ocultar las ayudas?'),
                        'name' => 'mts_AlgApi_Tooltips',
                        'is_bool' => true,
                        'desc' => $this->l('Active o desactive esta opción para ocultar las ayudas la próxima vez que ingrese a la configuración de este módulo. Puede volver a reactivarlas cuando lo necesite'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Aplicar'),
                    'name' => 'btnTooltipSubmit'
                )
            )
        );

        $platform_form = array(
            'form' => array(

                'legend' => array(
                    'title' => $this->l('Configuración de acceso a la plataforma'),
                    'icon' => 'icon-key'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Usuario'),
                        'desc' => $this->l('Ingrese un ID de Usuario con el que se conectará a la plataforma. No debe ser el mismo usuario de Alegra'),
                        'class' => 'md',
                        'name' => 'mts_AlgApi_User',
                        'required' => true
                    ),
                    array(
                        'type' => 'password',
                        'label' => $this->l('Contraseña'),
                        'desc' => $this->l('Ingrese una contraseña de Usuario con el que se conectará a la plataforma. No debe ser la misma contraseña de Alegra'),
                        'name' => 'mts_AlgApi_Password',
                        'required' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                    'name' => 'btnPlatformSubmit'
                )
            )
        );

        $alegra_api_form = array(
            'form' => array(

                'legend' => array(
                    'title' => $this->l('Configuración de la API de Alegra'),
                    'icon' => 'icon-sign-in'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email'),
                        'desc' => $this->l('Ingrese el correo con el que se registro en la API de Alegra.'),
                        'class' => 'md',
                        'name' => 'mts_AlgApi_Email',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Token'),
                        'desc' => $this->l('Ingrese el Token brindado por la plataforma de Alegra. Si no cuenta con uno, solicítelo a través de la misma plataforma.'),
                        'name' => 'mts_AlgApi_Token',
                        'required' => true
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Limite de resultados'),
                        'desc' => $this->l('Seleccione el limite de resultados que desea ver en la creación de productos, contactos o facturas. Entre menor sea el límite, mejor será el rendimiento.'),
                        'name' => 'mts_AlgApi_limitQuery',
                        'required' => false,
                        'options' => $limitOptions
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                    'name' => 'btnAPISubmit'
                )
            )
        );

        return array($tooltip_form, $platform_form, $alegra_api_form);
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name || Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if (Tools::getValue('module') == $this->name) {
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
            $this->context->controller->addJS($this->_path . '/views/js/front.js');
        }
    }
}
