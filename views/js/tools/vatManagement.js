/*
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
$(document).ready(function () {
    vat_number();
    vat_number_ajax();

    $(document).on('input', '#company, #company_invoice', function () {
        vat_number();
    });
});

function vat_number() {
    if ($('#company').length && ($('#company').val() != ''))
        $('#vat_number, #vat_number_block').show();
    else
        $('#vat_number, #vat_number_block').hide();

    if ($('#company_invoice').length && ($('#company_invoice').val() != ''))
        $('#vat_number_block_invoice').show();
    else
        $('#vat_number_block_invoice').hide();
}

function vat_number_ajax() {
    $(document).on('change', '#id_country', function () {
        if ($('#company').length && !$('#company').val())
            return;
        if (typeof vatnumber_ajax_call !== 'undefined' && vatnumber_ajax_call)
            $.ajax({
                type: 'POST',
                headers: {"cache-control": "no-cache"},
                url: baseDir + 'modules/vatnumber/ajax.php?id_country=' + parseInt($(this).val()) + '&rand=' + new Date().getTime(),
                success: function (isApplicable) {
                    if (isApplicable == "1") {
                        $('#vat_area').show();
                        $('#vat_number').show();
                    }
                    else
                        $('#vat_area').hide();
                }
            });
    });

    $(document).on('change', '#id_country_invoice', function () {
        if ($('#company_invoice').length && !$('#company_invoice').val())
            return;
        if (typeof vatnumber_ajax_call !== 'undefined' && vatnumber_ajax_call)
            $.ajax({
                type: 'POST',
                headers: {"cache-control": "no-cache"},
                url: baseDir + 'modules/vatnumber/ajax.php?id_country=' + parseInt($(this).val()) + '&rand=' + new Date().getTime(),
                success: function (isApplicable) {
                    if (isApplicable == "1") {
                        $('#vat_area_invoice').show();
                        $('#vat_number_invoice').show();
                    }
                    else
                        $('#vat_area_invoice').hide();
                }
            });
    });
}
