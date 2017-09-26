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

<h1>{l s='Subir Productos' mod='mtsalegraapi'}</h1>

{if isset($products) && !empty($products)}
    <p>{l s='Seleccione los contactos que desea subir' mod='mtsalegraapi'}</p>
    <fieldset>
        <form action="" method="post" class="form-inline">
            <input type="hidden" id="ProductCreate" name="ProductCreate" value="Crear un producto">
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
                            {l s='Nombre' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Descripción' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Referencia' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Cantidad inicial' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Unidad de medida' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Costo' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Impuesto' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Precio de Venta' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Observaciones' mod='mtsalegraapi'}
                        </th>
                    </tr>
                    {foreach from=$products key=idProduct item=product}
                        <input type="hidden" id="product_id_{$idProduct|escape:'htmlall':'UTF-8'}"
                               name="product_id_{$idProduct|escape:'htmlall':'UTF-8'}"
                               value="product_id_{$product.id_product|escape:'htmlall':'UTF-8'}">
                        <tr>
                            <td>
                                <input type="radio" name="product_option_{$idProduct|escape:'htmlall':'UTF-8'}"
                                       value="upload">
                            </td>
                            <td>
                                <input type="radio" name="product_option_{$idProduct|escape:'htmlall':'UTF-8'}"
                                       value="ignore">
                            </td>
                            <td>
                                {$product.name|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$product.description|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$product.reference|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$product.inventory.initialQuantity|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                <select name="product_unit_{$idProduct|escape:'htmlall':'UTF-8'}"
                                        id="product_unit_{$idProduct|escape:'htmlall':'UTF-8'}">
                                    <option value="unit">{l s='Unidad' mod='mtsalegraapi'}</option>
                                    <option value="centimeter">{l s='Centímetro' mod='mtsalegraapi'}</option>
                                    <option value="meter">{l s='Metro' mod='mtsalegraapi'}</option>
                                    <option value="inch">{l s='Pulgada' mod='mtsalegraapi'}</option>
                                    <option value="centimeterSquared">{l s='Centímetro Cuadrado' mod='mtsalegraapi'}</option>
                                    <option value="meterSquared">{l s='Metro Cuadrado' mod='mtsalegraapi'}</option>
                                    <option value="inchSquared">{l s='Pulgada Cuadrada' mod='mtsalegraapi'}</option>
                                    <option value="liter">{l s='Litro' mod='mtsalegraapi'}</option>
                                    <option value="gallon">{l s='Galón' mod='mtsalegraapi'}</option>
                                    <option value="gram">{l s='Gramo' mod='mtsalegraapi'}</option>
                                    <option value="kilogram">{l s='Kilogramo' mod='mtsalegraapi'}</option>
                                    <option value="ton">{l s='Tonelada' mod='mtsalegraapi'}</option>
                                    <option value="pound">{l s='Libra' mod='mtsalegraapi'}</option>
                                    <option value="piece">{l s='Pieza' mod='mtsalegraapi'}</option>
                                    <option value="service">{l s='Servicio' mod='mtsalegraapi'}</option>
                                    <option value="notApplicable">{l s='No Aplica' mod='mtsalegraapi'}</option>
                                </select>
                            </td>
                            <td>
                                $ {$product.inventory.unitCost|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$product.tax.name|escape:'htmlall':'UTF-8'}
                                : {$product.tax.value|number_format:0|escape:'htmlall':'UTF-8'}%
                            </td>
                            <td>
                                $ {$product.price|number_format:0|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                <textarea name="product_observations_{$idProduct|escape:'htmlall':'UTF-8'}"></textarea>
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
