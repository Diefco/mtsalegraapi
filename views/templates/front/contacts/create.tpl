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
                            check button
                        </td>
                        <td>
                            {if isset($customer.name) || isset($customer.name)}
                                {$customer.name|escape:'htmlall':'UTF-8'}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_name">
                            {/if}
                        </td>
                        <td>
                            {if isset($customer.dniUnique) && $customer.dniUnique == true}
                                {if $addressExist}
                                    {$customer.addressData.0.dni|escape:'htmlall':'UTF-8'}
                                {else}
                                    <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_dni">
                                {/if}
                            {elseif $addressExist}
                                <select>
                                    {*{foreach from=$customer.addressData item=address}*}
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.$counter.dni}">{$customer.addressData.$counter.dni}</option>
                                        {/for}
                                    {*{/foreach}*}
                                </select>
                            {/if}
                        </td>
                        <td>
                            {$customer.email}
                        </td>
                        <td>
                            {if $addressExist && $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.{$counter}.alias}">{$customer.addressData.{$counter}.alias}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.alias}
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_alias">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.{$counter}.phone}">{$customer.addressData.{$counter}.phone}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.phone}
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.{$counter}.phone_mobile}">{$customer.addressData.{$counter}.phone_mobile}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.phone_mobile}
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_phone_mobile">
                            {/if}
                        </td>
                        <td>
                            {if isset($customer.addressData) && !empty($customer.addressData)}
                                {if {$customer.addressData|@count} > 1}
                                    <select>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.{$counter}.address1}">{$customer.addressData.{$counter}.address1}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.address1}
                                    {if isset($customer.addressData.0.address2) && !empty($customer.addressData.0.address2)}
                                        , {$customer.addressData.0.address2}

                                    {/if}
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_address">
                            {/if}
                        </td>
                        <td>
                            {if $addressExist}
                                {if {$customer.addressData|@count} > 1}
                                    <select>
                                        {for $counter=0 to {$customer.addressData|@count}-1}
                                            <option value="{$customer.addressData.{$counter}.city}/{$customer.addressData.{$counter}.state}/{$customer.addressData.{$counter}.country}">{$customer.addressData.{$counter}.city}/{$customer.addressData.{$counter}.state}/{$customer.addressData.{$counter}.country}</option>
                                        {/for}
                                    </select>
                                {else}
                                    {$customer.addressData.0.city}/{$customer.addressData.0.state}/{$customer.addressData.0.country}
                                {/if}
                            {else}
                                <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_location">
                            {/if}
                        </td>
                        <td>
                            <input type="text" id="contact_{$idCustomer|escape:'htmlall':'UTF-8'}_observations">
                        </td>
                    </tr>

                    {/foreach}
                </table>
            </div>
            <br>
            <input type="submit" value="Enviar" class="btn btn-success">
        </form>
    </fieldset>
{else}
    <h2>{l s='Ningún contacto para subir' mod='mtsalegraapi'}</h2>
{/if}
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
