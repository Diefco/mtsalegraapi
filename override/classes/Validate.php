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

class Validate extends ValidateCore
{
    /**
     * Validate DNI Number
     *
     * @param string $dni_number DNI Number
     * @return bool Return true if is valid
     */
    public static function isDniNumber($dni_number)
    {
        $only_numbers = explode('-', $dni_number);

        if (count($only_numbers) <= 2) {
            $validNumber = true;

            foreach ($only_numbers as $number) {
                if (!filter_var($number, FILTER_VALIDATE_INT)) {
                    $validNumber = false;
                }
            }

            if ($validNumber) {
                return $dni_number;
            }
        }
        return false;
    }

    /**
     * Validate Document Type
     *
     * @param string $dni_type Document Type Code
     * @return bool Return true if is valid
     */
    public static function isDniType($dni_type)
    {
        if ($dni_type == '--') {
            return false;
        } elseif ($dni_type == 'CC' ||
            $dni_type == 'CE' ||
            $dni_type == 'NIT' ||
            $dni_type == 'TI' ||
            $dni_type == 'PP' ||
            $dni_type == 'IDC' ||
            $dni_type == 'CEL' ||
            $dni_type == 'RC' ||
            $dni_type == 'DE'
        ) {
            return $dni_type;
        }

        return false;
    }

    /**
     * Validate Legal Type
     *
     * @param string $legal_type Code
     * @return bool Return true if is valid
     */
    public static function isLegalType($legal_type)
    {
        if ($legal_type == '--') {
            return false;
        } elseif ($legal_type == 'PN' || $legal_type == 'PJ') {
            return $legal_type;
        }

        return false;
    }
}
