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

{if isset($customers) && !empty($customers)}
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
                            {l s='Opción' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Identificación' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Email' mod='mtsalegraapi'}
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
                    {foreach from=$customers item=customer}
                        {if isset($customer.addressData) && !empty($customer.addressData)}
                            {assign var="addressExist" value=true}
                            {assign var="addressCount" value=$customer.addressData|@count}
                        {else}
                            {assign var="addressExist" value=false}
                            {assign var="addressCount" value=0}
                        {/if}

                        {if isset($customer.id) && !empty($customer.id)}
                            {assign var="idCustomer" value=$customer.id}
                        {else}
                            {assign var="idCustomer" value=''}
                        {/if}
                    <tr>
                        <td>
                            <input type="checkbox" name="customer_{$idCustomer|escape:'htmlall':'UTF-8'}_check" value="true">
                        </td>
                        <td>
                            {if isset($customer.name) || isset($customer.name)}
                                {$customer.name|escape:'htmlall':'UTF-8'}
                                <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_name" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_name" value="{$customer.name|escape:'htmlall':'UTF-8'}">
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_name" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_name">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist && $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_list" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_list" value="selector">
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option"  class="selectorProfile" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$counter+1|escape:'htmlall':'UTF-8'}</option>
                                        {/for}
                                    </select>
                                {else}
                                    1
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option" value="0">
                                {/if}
                            {else}
                                1
                                <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_option" value="0">
                            {/if}
                        </td>
                        <td>
                            {if isset($customer.dniUnique) && $customer.dniUnique == true}
                                {if $addressExist}
                                    {$customer.addressData.0.dni|escape:'htmlall':'UTF-8'}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni" value="{$customer.addressData.0.dni|escape:'htmlall':'UTF-8'}">
                                {else}
                                    <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni">
                                {/if}
                            {elseif $addressExist}
                                <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni">
                                    <option value="0">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                    {*{foreach from=$customer.addressData item=address}*}
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.$counter.dni}</option>
                                        {/for}
                                    {*{/foreach}*}
                                </select>
                            {/if}
                        </td>
                        <td>
                            {$customer.email|escape:'htmlall':'UTF-8'}
                            <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_email" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_email" value="{$customer.email|escape:'htmlall':'UTF-8'}">
                        </td>
                        <td>
                            {if $addressExist && $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.{$counter}.alias}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.alias|escape:'htmlall':'UTF-8'}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias" value="{$customer.addressData.0.alias|escape:'htmlall':'UTF-8'}">
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.{$counter}.phone}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.phone|escape:'htmlall':'UTF-8'}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone" value="{$customer.addressData.0.phone|escape:'htmlall':'UTF-8'}">
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.{$counter}.phone_mobile}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.phone_mobile|escape:'htmlall':'UTF-8'}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile" value="{$customer.addressData.0.phone|escape:'htmlall':'UTF-8'}">

                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.{$counter}.address1|escape:'htmlall':'UTF-8'}
                                                {if isset($customer.addressData.0.address2) && !empty($customer.addressData.0.address2)}
                                                    , {$customer.addressData.0.address2|escape:'htmlall':'UTF-8'}
                                                {/if}
                                            </option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.address1|escape:'htmlall':'UTF-8'}
                                    {if isset($customer.addressData.0.address2) && !empty($customer.addressData.0.address2)}
                                        , {$customer.addressData.0.address2|escape:'htmlall':'UTF-8'}
                                    {/if}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address" value="{$customer.addressData.0.address1|escape:'htmlall':'UTF-8'}{if isset($customer.addressData.0.address2) && !empty($customer.addressData.0.address2)}, {$customer.addressData.0.address2|escape:'htmlall':'UTF-8'}{/if}">
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location">
                                        <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$counter|escape:'htmlall':'UTF-8'}">{$customer.addressData.{$counter}.city|escape:'htmlall':'UTF-8'}/{$customer.addressData.{$counter}.state|escape:'htmlall':'UTF-8'}/{$customer.addressData.{$counter}.country|escape:'htmlall':'UTF-8'}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.city|escape:'htmlall':'UTF-8'}/{$customer.addressData.0.state|escape:'htmlall':'UTF-8'}/{$customer.addressData.0.country|escape:'htmlall':'UTF-8'}
                                    <input type="hidden" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location" value="{$customer.addressData.0.city|escape:'htmlall':'UTF-8'}/{$customer.addressData.0.state|escape:'htmlall':'UTF-8'}/{$customer.addressData.0.country|escape:'htmlall':'UTF-8'}">
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location">
                            {/if}
                        </td>
                        <td>
                            <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_observations" name="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_observations">
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
    <h2>{l s='Ningún contacto para subir' mod='mtsalegraapi'}</h2>
{/if}
<br>
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
