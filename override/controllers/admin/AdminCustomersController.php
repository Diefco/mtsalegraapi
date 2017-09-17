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

/**
 * @property Customer $object
 */
class AdminCustomersController extends AdminCustomersControllerCore
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->required_database = true;
        $this->required_fields = array('newsletter', 'optin');
        $this->table = 'customer';
        $this->className = 'Customer';
        $this->lang = false;
        $this->deleted = true;
        $this->explicitSelect = true;

        $this->allow_export = true;

        $this->addRowAction('edit');
        $this->addRowAction('view');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->context = Context::getContext();

        $this->default_form_language = $this->context->language->id;

        $titles_array = array();
        $genders = Gender::getGenders($this->context->language->id);
        foreach ($genders as $gender) {
            /** @var Gender $gender */
            $titles_array[$gender->id_gender] = $gender->name;
        }

        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON (a.id_gender = gl.id_gender AND gl.id_lang = ' . (int)$this->context->language->id . ')';
        $this->_use_found_rows = false;
        $this->fields_list = array(
            'id_customer' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'title' => array(
                'title' => $this->l('Social title'),
                'filter_key' => 'a!id_gender',
                'type' => 'select',
                'list' => $titles_array,
                'filter_type' => 'int',
                'order_key' => 'gl!name'
            ),
            'firstname' => array(
                'title' => $this->l('First name')
            ),
            'lastname' => array(
                'title' => $this->l('Last name')
            ),
            'email' => array(
                'title' => $this->l('Email address')
            ),
        );

        if (Configuration::get('PS_B2B_ENABLE')) {
            $this->fields_list = array_merge($this->fields_list, array(
                'company' => array(
                    'title' => $this->l('Company')
                ),
            ));
        }

        $this->fields_list = array_merge($this->fields_list, array(
            'total_spent' => array(
                'title' => $this->l('Sales'),
                'type' => 'price',
                'search' => false,
                'havingFilter' => true,
                'align' => 'text-right',
                'badge_success' => true
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'text-center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'filter_key' => 'a!active'
            ),
            'newsletter' => array(
                'title' => $this->l('Newsletter'),
                'align' => 'text-center',
                'type' => 'bool',
                'callback' => 'printNewsIcon',
                'orderby' => false
            ),
            'optin' => array(
                'title' => $this->l('Opt-in'),
                'align' => 'text-center',
                'type' => 'bool',
                'callback' => 'printOptinIcon',
                'orderby' => false
            ),
            'date_add' => array(
                'title' => $this->l('Registration'),
                'type' => 'date',
                'align' => 'text-right'
            ),
            'connect' => array(
                'title' => $this->l('Last visit'),
                'type' => 'datetime',
                'search' => false,
                'havingFilter' => true
            ),
            'connect' => array(
                'title' => $this->l('Last visit'),
                'type' => 'datetime',
                'search' => false,
                'havingFilter' => true
            ),
        ));

        $this->shopLinkType = 'shop';
        $this->shopShareDatas = Shop::SHARE_CUSTOMER;

        parent::__construct();

        $this->_select = '
        a.date_add, gl.name as title, (
            SELECT SUM(total_paid_real / conversion_rate)
            FROM ' . _DB_PREFIX_ . 'orders o
            WHERE o.id_customer = a.id_customer
            ' . Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o') . '
            AND o.valid = 1
        ) as total_spent, (
            SELECT c.date_add FROM ' . _DB_PREFIX_ . 'guest g
            LEFT JOIN ' . _DB_PREFIX_ . 'connections c ON c.id_guest = g.id_guest
            WHERE g.id_customer = a.id_customer
            ORDER BY c.date_add DESC
            LIMIT 1
        ) as connect';

        // Check if we can add a customer
        if (Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $this->can_add_customer = false;
        }

        self::$meaning_status = array(
            'open' => $this->l('Open'),
            'closed' => $this->l('Closed'),
            'pending1' => $this->l('Pending 1'),
            'pending2' => $this->l('Pending 2')
        );
    }
}
