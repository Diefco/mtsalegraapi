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

<h1>{l s='Subir Facturas o Pedidos' mod='mtsalegraapi'}</h1>

{if isset($customers) && !empty($customers)}
    <p>{l s='Seleccione los contactos que desea subir' mod='mtsalegraapi'}</p>
    <fieldset>
        <form action="" method="post" class="form-inline">
            <input type="hidden" id="CustomerCreate" name="CustomerCreate" value="Crear un usuario">
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
                            {l s='Compañia' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Identificación' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Email' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Teléfono fijo' mod='mtsalegraapi'}
                        </th>
                        <th>
                            {l s='Celular' mod='mtsalegraapi'}
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
                    {foreach from=$customers key=idCustomer item=customer}
                        <tr>
                            <td>
                                <input type="radio" name="customer_option_{$idCustomer|escape:'htmlall':'UTF-8'}"
                                       value="upload">
                            </td>
                            <td>
                                <input type="radio" name="customer_option_{$idCustomer|escape:'htmlall':'UTF-8'}"
                                       value="ignore">
                            </td>
                            <td>
                                {$customer.name|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.identification|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.email|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.phonePrimary|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.mobile|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.address.address|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                {$customer.address.city|escape:'htmlall':'UTF-8'}
                            </td>
                            <td>
                                <textarea
                                        name="customer_observations_{$idCustomer|escape:'htmlall':'UTF-8'}"></textarea>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <div>
                <br>
                <input type="submit" id="submitData" value="Enviar" class="btn btn-success">
            </div>
        </form>
    </fieldset>
{else}
    <h2>{l s='Ninguna factura o pedido para subir' mod='mtsalegraapi'}</h2>
{/if}
<br>
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
