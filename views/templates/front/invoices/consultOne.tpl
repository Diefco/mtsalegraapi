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

<h1>{l s='Consultar una Factura' mod='mtsalegraapi'}</h1>
<fieldset>
    <form action="" method="post" class="form-inline">
        <div class="form-group">
            <label for="id_invoice">Ingrese el ID del producto:</label>
            <input type="text" id="id_invoice" name="id_invoice" class="form-control">
        </div>
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</fieldset>

{if isset ($invoice) && !isset ($invoice.code)}
    <div id="detail_invoice">
        <table class="table table-bordered table-condensed">
            <th colspan="2">{l s='Detalle de la factura' mod='mtsalegraapi'}</th>
            {if isset ($invoice.id)}
                <tr>
                    <td class="table-title">
                        {l s='ID' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.id|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.date)}
                <tr>
                    <td class="table-title">
                        {l s='Fecha de la factura' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.date|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.dueDate)}
                <tr>
                    <td class="table-title">
                        {l s='Fecha de vencimiento' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.dueDate|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.observations)}
                <tr>
                    <td class="table-title">
                        {l s='Observaciones' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.observations|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.anotations)}
                <tr>
                    <td class="table-title">
                        {l s='Anotaciones' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.anotations|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.termsConditions)}
                <tr>
                    <td class="table-title">
                        {l s='Términos y Condiciones' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$invoice.termsConditions|truncate:50|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($invoice.status)}
                <tr>
                    <td class="table-title">
                        {l s='Anotaciones' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {if $invoice.status == 'draft'}
                            {l s='Borrador' mod='mtsalegraapi'}
                        {elseif $invoice.status == 'open'}
                            {l s='Abierta' mod='mtsalegraapi'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if isset($invoice.client) && ($invoice.client != null || $invoice.client != '')}
                <th colspan="2">{l s='Detalles del cliente' mod='mtsalegraapi'}</th>
                {if isset ($invoice.client.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Nombre' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$invoice.client.name|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.client.identification)}
                    <tr>
                        <td class="table-title">
                            {l s='Identificacion' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$invoice.client.identification|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.client.phonePrimary) || isset ($invoice.client.phoneSecondary) || isset ($invoice.client.mobile)}
                    <tr>
                        <td class="table-title">
                            {l s='Teléfono(s)' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {if isset($invoice.client.phonePrimary) && ($invoice.client.phonePrimary != null || $invoice.client.phonePrimary != '')}
                                {$invoice.client.phonePrimary|escape:'htmlall':'UTF-8'}
                                <br>
                            {/if}
                            {if isset($invoice.client.phoneSecondary) && ($invoice.client.phoneSecondary != null || $invoice.client.phoneSecondary != '')}
                                {$invoice.client.phoneSecondary|escape:'htmlall':'UTF-8'}
                                <br>
                            {/if}
                            {if isset($invoice.client.mobile) && ($invoice.client.mobile != null || $invoice.client.mobile != '')}
                                {$invoice.client.mobile|escape:'htmlall':'UTF-8'}
                                <br>
                            {/if}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.client.email)}
                    <tr>
                        <td class="table-title">
                            {l s='Email' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$invoice.client.email|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.client.address.address)}
                    <tr>
                        <td class="table-title">
                            {l s='Dirección' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$invoice.client.address.address|escape:'htmlall':'UTF-8'}
                            {if isset($invoice.client.address.city)}
                                , {$invoice.client.address.city|escape:'htmlall':'UTF-8'}
                            {/if}
                        </td>
                    </tr>
                {/if}
            {/if}
            {if isset ($invoice.items) && ($invoice.items != null || $invoice.items != '')}
                <th colspan="2">{l s='Detalles de Productos' mod='mtsalegraapi'}</th>
                <tr></tr>
                {foreach key=key item=item from=$invoice.items}
                    <th colspan="2">{l s='Producto #' mod='mtsalegraapi'} {$key+1|escape:'htmlall':'UTF-8'}</th>
                    {if isset ($item.id)}
                        <tr>
                            <td class="table-title">
                                {l s='ID' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$item.id|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.name)}
                        <tr>
                            <td class="table-title">
                                {l s='Nombre' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$item.name|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.reference)}
                        <tr>
                            <td class="table-title">
                                {l s='Referencia' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$item.reference|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.description)}
                        <tr>
                            <td class="table-title">
                                {l s='Descripción' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$item.description|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.price)}
                        <tr>
                            <td class="table-title">
                                {l s='Precio unitario' mod='mtsalegraapi'}
                            </td>
                            <td>
                                $ {$item.price|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.discount)}
                        <tr>
                            <td class="table-title">
                                {l s='Descuentos' mod='mtsalegraapi'}
                            </td>
                            <td>
                                $ {$item.discount|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.quantity)}
                        <tr>
                            <td class="table-title">
                                {l s='Cantidad' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$item.quantity|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($item.total)}
                        <tr>
                            <td class="table-title">
                                {l s='Subtotal' mod='mtsalegraapi'}
                            </td>
                            <td>
                                $ {$item.total|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
                <th colspan="2">{l s='Detalles de Totales' mod='mtsalegraapi'}</th>
                {if isset ($invoice.total)}
                    <tr>
                        <td class="table-title">
                            {l s='Total a pagar' mod='mtsalegraapi'}
                        </td>
                        <td>
                            $ {$invoice.total|number_format:0|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.total)}
                    <tr>
                        <td class="table-title">
                            {l s='Total pagado' mod='mtsalegraapi'}
                        </td>
                        <td>
                            $ {$invoice.totalPaid|number_format:0|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($invoice.total)}
                    <tr>
                        <td class="table-title">
                            {l s='Saldo pendiente' mod='mtsalegraapi'}
                        </td>
                        <td>
                            $ {$invoice.balance|number_format:0|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
            {/if}
        </table>
    </div>
{/if}

<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
