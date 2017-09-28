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

$sql = array();
$excludedSql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'mtsalegraapi_invoices` (
    `id_order_store` INT(11) NOT NULL,
    `id_order_alegra` INT(11) NULL,
    `id_payment_alegra` INT(11) NULL,
    `order_ignored` BOOLEAN,
    PRIMARY KEY  (`id_order_store`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'mtsalegraapi_products` (
    `id_product` INT(11) NOT NULL AUTO_INCREMENT,
    `id_product_store` INT(11) NOT NULL,
    `id_attribute_store` INT(11) NOT NULL,
    `id_product_alegra` INT(11) NULL,
    `product_ignored` BOOLEAN,
    `observations` VARCHAR(255) NULL,
    PRIMARY KEY  (`id_product`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'mtsalegraapi_contacts` (
    `id_contact_store` INT(11) NOT NULL,
    `id_contact_alegra` INT(11) NULL,
    `contact_ignored` BOOLEAN,
    `observations` VARCHAR(255) NULL,
    PRIMARY KEY  (`id_contact_store`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = "UPDATE `" . _DB_PREFIX_ . "address_format`
    SET `format`='firstname
    lastname 
    address1 
    address2 
    postcode 
    city 
    Country:name 
    phone 
    phone_mobile 
    Customer:siret'
    WHERE `id_country`=69";

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
