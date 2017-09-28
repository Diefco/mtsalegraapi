{*
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
*}

<h1>{l s='Consultar Un Pago' mod='mtsalegraapi'}</h1>
<fieldset>
    <form action="" method="post" class="form-inline">
        <div class="form-group">
            <label for="id_payment">Ingrese el ID del pago:</label>
            <input type="text" id="id_payment" name="id_payment" class="form-control">
            <input type="hidden" id="paymentRequest" name="paymentRequest" class="form-control">
        </div>
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</fieldset>
<br>
{if isset ($payment) && !isset ($payment.code)}
    <div id="detail_contact">
        <table class="table table-bordered table-condensed">
            <th colspan="2">{l s='Detalle del pago' mod='mtsalegraapi'}</th>
        </table>
    </div>
{elseif isset ($payment.code) && $payment.code == '404'}
    <p>{$payment.message|escape:'htmlall':'UTF-8'}</p>
{elseif isset ($errorBO) && $errorBO}
    <p>{l s='Ingrese un ID válido, diferente de 0 (cero).' mod='mtsalegraapi'}</p>
{/if}
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
