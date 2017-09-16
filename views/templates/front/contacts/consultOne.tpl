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

<h1>{l s='Consultar Un Contacto' mod='mtsalegraapi'}</h1>
<fieldset>
    <form action="" method="post" class="form-inline">
        <div class="form-group">
            <label for="id_contact">Ingrese el ID del producto:</label>
            <input type="text" id="id_contact" name="id_contact" class="form-control">
        </div>
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</fieldset>
<br>
{if isset ($contact) && !isset ($contact.code)}
    <div id="detail_contact">
        <table class="table table-bordered table-condensed">
            <th colspan="2">{l s='Detalle del contacto' mod='mtsalegraapi'}</th>
            {if isset ($contact.id)}
                <tr>
                    <td class="table-title">
                        {l s='ID' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.id|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.name)}
                <tr>
                    <td class="table-title">
                        {l s='Nombre' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.name|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.identification)}
                <tr>
                    <td class="table-title">
                        {l s='Identificación' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.identification|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.email)}
                <tr>
                    <td class="table-title">
                        {l s='Email' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.email|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.phonePrimary)}
                <tr>
                    <td class="table-title">
                        {l s='Teléfono principal' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.phonePrimary|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.phoneSecondary)}
                <tr>
                    <td class="table-title">
                        {l s='Teléfono secundario' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.phoneSecondary|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.mobile)}
                <tr>
                    <td class="table-title">
                        {l s='Celular' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.mobile|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.fax)}
                <tr>
                    <td class="table-title">
                        {l s='Fax' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.fax|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.observations)}
                <tr>
                    <td class="table-title">
                        {l s='Observaciones' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.observations|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.type.0) || isset ($contact.type.1)}
                <tr>
                    <td class="table-title">
                        {l s='Tipo' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {if isset ($contact.type.0) && $contact.type.0 == 'client'}
                            {l s='Cliente' mod='mtsalegraapi'}
                        {/if}
                        {if isset ($contact.type.0) && isset ($contact.type.1)}
                            {l s=' y ' mod='mtsalegraapi'}
                        {/if}
                        {if isset ($contact.type.1) && $contact.type.1 == 'provider'}
                            {l s='Proveedor' mod='mtsalegraapi'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.address.address)}
                <tr>
                    <td class="table-title">
                        {l s='Dirección' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.address.address|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.address.city)}
                <tr>
                    <td class="table-title">
                        {l s='Ciudad' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$contact.address.city|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($contact.seller)}
                <th colspan="2">{l s='Vendedor' mod='mtsalegraapi'}</th>
                {if isset ($contact.seller.id)}
                    <tr>
                        <td class="table-title">
                            {l s='ID del vendedor' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.seller.id|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($contact.seller.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Nombre' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.seller.name|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
            {/if}
            {if isset ($contact.term)}
                <th colspan="2">{l s='Términos' mod='mtsalegraapi'}</th>
                {if isset ($contact.term.id)}
                    <tr>
                        <td class="table-title">
                            {l s='ID del término' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.term.id|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($contact.term.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Nombre' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.term.name|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($contact.term.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Número de días' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.term.days|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
            {/if}
            {if isset ($contact.priceList)}
                <th colspan="2">{l s='Lista de precios' mod='mtsalegraapi'}</th>
                {if isset ($contact.priceList.id)}
                    <tr>
                        <td class="table-title">
                            {l s='ID de la lista' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.priceList.id|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($contact.priceList.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Nombre' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$contact.priceList.name|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
            {/if}
            {if isset ($contact.internalContacts.0)}
                <th colspan="2">{l s='Contactos internos' mod='mtsalegraapi'}</th>
                {foreach key=key item=list from=$contact.internalContacts}
                    {if isset ($list.id)}
                        <tr>
                            <td class="table-title">
                                {l s='ID' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.id|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($list.name)}
                        <tr>
                            <td class="table-title">
                                {l s='Nombre de la lista de precios' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.name|escape:'htmlall':'UTF-8'} {if isset ($list.lastName)}{$list.lastName|escape:'htmlall':'UTF-8'}{/if}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($list.email)}
                        <tr>
                            <td class="table-title">
                                {l s='Email' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.email|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($list.phone)}
                        <tr>
                            <td class="table-title">
                                {l s='Teléfono principal' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.phone|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($list.mobile)}
                        <tr>
                            <td class="table-title">
                                {l s='Celular' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.mobile|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if isset ($list.sendNotifications)}
                        <tr>
                            <td class="table-title">
                                {l s='¿Enviar notificaciones?' mod='mtsalegraapi'}
                            </td>
                            <td>
                                {$list.sendNotifications|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            {/if}
        </table>
    </div>
{elseif isset ($contact.code) && $contact.code == '404'}
    <p>{l s='Contacto no encontrado, verifique que el ID sea válido.' mod='mtsalegraapi'}</p>
{elseif isset ($errorBO) && $errorBO}
    <p>{l s='Ingrese un ID válido, diferente de 0 (cero).' mod='mtsalegraapi'}</p>
{/if}
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
