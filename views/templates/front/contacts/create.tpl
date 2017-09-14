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

<h1>{l s='Subir contactos' mod='mtsalegraapi'}</h1>
{if isset($customerBundle) && !empty($customerBundle)}
    <p>{l s='Seleccione los contactos que desea subir' mod='mtsalegraapi'}</p>
    <fieldset>
        <form action="" method="post" class="form-inline">
            <div class="form-group">
                <table class="table-bordered table-condensed table-striped">
                    <tr>
                        <th >
                            {l s='Subir' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Nombre' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Identificación' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Email' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Eleja' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Alias' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Teléfono(s) fijo(s)' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Celular{es}' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Dirección' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Ciudad/Provincia/Pais' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Observaciones' mod='mtsalegraapi'}
                        </th>
                    </tr>
                    {foreach from=$customerBundle item=customer}
                        {if isset($customer.address) && !empty($customer.address)}
                            {assign var="rowspan" value=$customer.address|@count}
                        {else}
                            {assign var="rowspan" value=0}
                        {/if}
                            <tr>
                                <td rowspan="{$rowspan|escape:'htmlall':'UTF-8'}">
                                    checkbox
                                </td>
                                <td rowspan="{$rowspan|escape:'htmlall':'UTF-8'}">
                                    {$customer.info.firstname|escape:'htmlall':'UTF-8'} {$customer.info.lastname|escape:'htmlall':'UTF-8'}
                                </td>
                                <td rowspan="{$rowspan|escape:'htmlall':'UTF-8'}">
                                    id
                                </td>
                                <td rowspan="{$rowspan|escape:'htmlall':'UTF-8'}">
                                    {$customer.info.email|escape:'htmlall':'UTF-8'}
                                </td>
                        {assign var="counter" value=0}
                        {if isset($customer.address) && !empty($customer.address)}
                            {foreach from=$customer.address item=address}
                                {if $counter != 0}
                                    </tr>
                                    <tr>
                                {/if}
                                    <td>
                                        radio button
                                    </td>
                                    <td>
                                        {if isset($address.alias) && !empty($address.alias)}
                                            {$address.alias|escape:'htmlall':'UTF-8'}
                                        {/if}
                                    </td>
                                    <td>
                                        {if isset($address.phone) && !empty($address.phone)}
                                            {$address.phone|escape:'htmlall':'UTF-8'}
                                        {/if}
                                    </td>
                                    <td>
                                        {if isset($address.phone_mobile) && !empty($address.phone_mobile)}
                                            {$address.phone_mobile|escape:'htmlall':'UTF-8'}
                                        {/if}
                                    </td>
                                    <td>
                                        {if isset($address.address1) && !empty($address.address1) || isset($address.address2) && !empty($address.address2)}
                                            {$address.address1|escape:'htmlall':'UTF-8'}
                                            {if isset($address.address2) && !empty($address.address2)}
                                                , {$address.address2|escape:'htmlall':'UTF-8'}
                                            {/if}
                                        {/if}
                                    </td>
                                    <td>
                                        {if isset($address.city) && !empty($address.city)}
                                            {$address.city|escape:'htmlall':'UTF-8'}, {$address.state_name|escape:'htmlall':'UTF-8'}, {$address.iso_code_country|escape:'htmlall':'UTF-8'}
                                        {/if}
                                    </td>
                                    <td >
                                        Holi
                                    </td>
                                </tr>
                                {assign var="counter" value=1}
                            {/foreach}
                        {/if}
                    {/foreach}
                </table>
            </div>
            <br>
            <input type="submit" value="Enviar" class="btn btn-success">
        </form>
    </fieldset>
    <pre>
        {$customerBundle|@print_r}
    </pre>
{else}
    <h2>{l s='Ningún contacto para subir' mod='mtsalegraapi'}</h2>
{/if}
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
