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
    $(document).on('submit', '#create-account_form', function (e) {
        e.preventDefault();
        submitFunction();
    });
    $('.is_customer_param').hide();
});

function submitFunction() {
    $('#create_account_error').html('').hide();
    $.ajax({
        type: 'POST',
        url: baseUri + '?rand=' + new Date().getTime(),
        async: true,
        cache: false,
        dataType: "json",
        headers: {"cache-control": "no-cache"},
        data:
            {
                controller: 'authentication',
                SubmitCreate: 1,
                ajax: true,
                email_create: $('#email_create').val(),
                back: $('input[name=back]').val(),
                token: token
            },
        success: function (jsonData) {
            console.log(jsonData);
            if (jsonData.hasError) {
                var errors = '';
                for (error in jsonData.errors)
                    //IE6 bug fix
                    if (error != 'indexOf')
                        errors += '<li>' + jsonData.errors[error] + '</li>';
                $('#create_account_error').html('<ol>' + errors + '</ol>').show();
            }
            else {
                // adding a div to display a transition
                $('#center_column').html('<div id="noSlide">' + $('#center_column').html() + '</div>');
                $('#noSlide').fadeOut('slow', function () {
                    $('#noSlide').html(jsonData.page);
                    $(this).fadeIn('slow', function () {
                        if (typeof bindUniform !== 'undefined')
                            bindUniform();
                        if (typeof bindStateInputAndUpdate !== 'undefined')
                            bindStateInputAndUpdate();
                        document.location = '#account-creation';
                    });
                });
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            error = "TECHNICAL ERROR: unable to load form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus;
            if (!!$.prototype.fancybox) {
                $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: "<p class='fancybox-error'>" + error + '</p>'
                        }],
                    {
                        padding: 0
                    });
            }
            else
                alert(error);
        }
    });
}