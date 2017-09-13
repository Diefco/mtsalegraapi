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

<h1>{l s='Consultar Varios Contactos' mod='mtsalegraapi'}</h1>

<fieldset>
    <form action="" method="post" class="form-inline">
        <h3>{l s='Por ID del contacto' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="start">{l s='Buscar a partir del Contacto con ID:' mod='mtsalegraapi'}</label>
            <input type="number" class="form-control" name="start" id="start">
        </div>
        <div class="form-group">
            <label for="limit">{l s='Cantidad de contactos a consultar:' mod='mtsalegraapi'}</label>
            <input type="number" class="form-control" name="limit" id="limit">
        </div>
        <h3>{l s='Por texto' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="query">{l s='Texto a buscar (ID, Nombre, Identificación o Email)' mod='mtsalegraapi'}</label>
            <input type="text" class="form-control" name="query" id="query">
        </div>
        <h3>{l s='Otras opciones' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="provider">{l s='Proveedor' mod='mtsalegraapi'}</label>
            <input type="radio" class="form-control" name="type" value="provider" id="provider">

            <label for="client">{l s='Cliente' mod='mtsalegraapi'}</label>
            <input type="radio" class="form-control" name="type" value="client" id="client">
        </div>
        <div class="form-group">
            <label for="order_field">{l s='Ordenar por:' mod='mtsalegraapi'}</label>
            <select name="order_field" id="order_field" class="form-control">
                <option value="">{l s='Seleccione una opción' mod='mtsalegraapi'}</option>
                <option value="id">{l s='ID' mod='mtsalegraapi'}</option>
                <option value="name">{l s='Nombre' mod='mtsalegraapi'}</option>
                <option value="identification">{l s='Identificación' mod='mtsalegraapi'}</option>
                <option value="email">{l s='Email' mod='mtsalegraapi'}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="order_direction">{l s='Ordenar de forma:' mod='mtsalegraapi'}</label>
            <select name="order_direction" id="order_direction" class="form-control">
                <option value="ASC">{l s='Ascendente' mod='mtsalegraapi'}</option>
                <option value="DESC">{l s='Descendente' mod='mtsalegraapi'}</option>
            </select>
        </div>
        <br>
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</fieldset>

{if	isset ($contactList)}
    <table class=" table table-bordered table-condensed table-striped">
        <tr>
            <th>
                {l s='ID' mod='mtsalegraapi'}
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
                {l s='Teléfonos' mod='mtsalegraapi'}
            </th>
            <th>
                {l s='Tipo' mod='mtsalegraapi'}
            </th>
            <th>
                {l s='Dirección' mod='mtsalegraapi'}
            </th>
        </tr>
        {foreach from=$contactList item=contact}
            <tr>
                <td>
                    {$contact.id|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$contact.name|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$contact.identification|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$contact.email|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {if isset($contact.phonePrimary) && ($contact.phonePrimary != '' || !is_null($contact.phonePrimary))}
                        {$contact.phonePrimary|escape:'htmlall':'UTF-8'}
                        <br>
                    {/if}
                    {if isset($contact.phoneSecondary) && ($contact.phoneSecondary != '' || !is_null($contact.phoneSecondary))}
                        {$contact.phoneSecondary|escape:'htmlall':'UTF-8'}
                        <br>
                    {/if}
                    {if isset($contact.mobile) && ($contact.mobile != '' || !is_null($contact.mobile))}
                        {$contact.mobile|escape:'htmlall':'UTF-8'}
                    {/if}
                </td>
                <td>
                    {if isset($contact.type.0)}
                        {foreach from=$contact.type item=type}
                            {if $type == "client"}
                                {l s='Cliente' mod='mtsalegraapi'}
                            {/if}
                            {if $type == "provider"}
                                {l s='Proveedor' mod='mtsalegraapi'}
                            {/if}
                            <br>
                        {/foreach}
                    {else}
                        -
                    {/if}
                </td>
                <td>
                    {if isset($contact.address.address) && isset($contact.address.city)}
                        {$contact.address.address|escape:'htmlall':'UTF-8'}, {$contact.address.city|escape:'htmlall':'UTF-8'}
                    {elseif isset($contact.address.address)}
                        {$contact.address.address|escape:'htmlall':'UTF-8'}
                    {else}
                        -
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{else}

{/if}
<br>
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
