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

<h1>{l s='Subir Facturas' mod='mtsalegraapi'}</h1>

{if isset($invoices) && !empty($invoices)}
    <p>{l s='Seleccione las facturas que desea subir' mod='mtsalegraapi'}</p>
    <fieldset>
        <form action="" method="post" class="form-inline">
            <input type="hidden" id="InvoiceCreate" name="InvoiceCreate" value="Crear una factura">
            <div class="form-group">
                <table class="table-bordered table-condensed table-striped">
                    <tr>
                        <th>
                            {l s='Subir' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Ignorar' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Razón Social' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Tipo de Documento' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Número de Documento' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Total pagado' mod='mtsalegraapi'}
                        </th>
                    </tr>
                    {foreach from=$invoices key=idOrder item=invoice}
                        <input type="hidden" id="invoice_id_{$idOrder|escape:'htmlall':'UTF-8'}"
                               name="invoice_id_{$idOrder|escape:'htmlall':'UTF-8'}"
                               value="invoice_id_{$invoice.id_product|escape:'htmlall':'UTF-8'}">
                        <tr>
                            <td>
                                <input type="radio" name="invoice_option_{$idOrder|escape:'htmlall':'UTF-8'}"
                                       value="upload">
                            </td>
                            <td>
                                <input type="radio" name="invoice_option_{$idOrder|escape:'htmlall':'UTF-8'}"
                                       value="ignore">
                            </td>
                            <td>
                                {$invoice.customer_info.0.company|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$invoice.customer_info.0.ape|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$invoice.customer_info.0.siret|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                $ {$invoice.payments.amount|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <div>
                <br>
                <input type="submit" value="Enviar" class="btn btn-success">
            </div>
        </form>
    </fieldset>
{else}
    <h2>{l s='Ningún producto para subir' mod='mtsalegraapi'}</h2>
{/if}
<br>
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
