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

<h1>{l s='Consultar Varios Productos' mod='mtsalegraapi'}</h1>

<fieldset>
    <form action="" method="post" class="form-inline">
        <h3>{l s='Por ID de productos' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="start">{l s='Buscar a partir del producto con ID:' mod='mtsalegraapi'}</label>
            <input type="number" class="form-control" name="start" id="start">
        </div>
        <div class="form-group">
            <label for="limit">{l s='Cantidad de productos a consultar:' mod='mtsalegraapi'}</label>
            <input type="number" class="form-control" name="limit" id="limit">
        </div>
        <h3>{l s='Por texto' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="query">{l s='Texto a buscar (ID, Nombre, Referencia o Descripción)' mod='mtsalegraapi'}</label>
            <input type="text" class="form-control" name="query" id="query">
        </div>
        <h3>{l s='Otras opciones' mod='mtsalegraapi'}</h3>
        <div class="form-group">
            <label for="order_field">{l s='Ordenar por:' mod='mtsalegraapi'}</label>
            <select name="order_field" id="order_field" class="form-control">
                <option value="id">{l s='ID' mod='mtsalegraapi'}</option>
                <option value="name">{l s='Nombre' mod='mtsalegraapi'}</option>
                <option value="reference">{l s='Referencia' mod='mtsalegraapi'}</option>
                <option value="description">{l s='Descripción' mod='mtsalegraapi'}</option>
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
{if    isset ($productList)}
    <table class=" table table-bordered table-condensed table-striped">
        <tr>
            <th>
                ID
            </th>
            <th>
                Nombre
            </th>
            <th>
                Precio
            </th>
        </tr>
        {foreach from=$productList item=product}
            <tr>
                <td>
                    {$product.id|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$product.name|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$product.price.0.price|number_format:0|escape:'htmlall':'UTF-8'}
                </td>
            </tr>
        {/foreach}

    </table>
{else}

{/if}
<br>
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
