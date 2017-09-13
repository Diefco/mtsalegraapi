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

<h1>{l s='Consultar Un Producto' mod='mtsalegraapi'}</h1>
<fieldset>
    <form action="" method="post" class="form-inline">
        <div class="form-group">
            <label for="id_product">Ingrese el ID del producto:</label>
            <input type="text" id="id_product" name="id_product" class="form-control">
        </div>
        <input type="submit" value="Enviar" class="btn btn-success">
    </form>
</fieldset>
<br>
{if isset ($product) && !isset ($product.code)}
    <div id="detail_product">
        <table class="table table-bordered table-condensed">
            <th colspan="2">Detalle del producto</th>
            {if isset ($product.id)}
                <tr>
                    <td class="table-title">
                        {l s='ID del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.id|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.name)}
                <tr>
                    <td class="table-title">
                        {l s='Nombre del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.name|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.description)}
                <tr>
                    <td class="table-title">
                        {l s='Descripción del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.description|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.reference)}
                <tr>
                    <td class="table-title">
                        {l s='Referencia del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.reference|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.status)}
                <tr>
                    <td class="table-title">
                        {l s='Estado del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.status|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}

            {if isset ($product.inventory) && !empty ($product.inventory)}
                <th colspan="2">Detalle del inventario</th>
            {/if}

            {if isset ($product.inventory.unit)}
                <tr>
                    <td class="table-title">
                        {l s='Tipo de unidad del producto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.inventory.unit|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.inventory.availableQuantity)}
                <tr>
                    <td class="table-title">
                        {l s='Cantidad disponible' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.inventory.availableQuantity|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.inventory.unitCost)}
                <tr>
                    <td class="table-title">
                        {l s='Cantidad inicial' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.inventory.unitCost|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.inventory.initialQuantity)}
                <tr>
                    <td class="table-title">
                        {l s='Costo unitario' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.inventory.initialQuantity|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}

            {if isset ($product.tax) && !empty ($product.tax)}
                <th colspan="2">Detalle de impuestos</th>
            {/if}

            {if isset ($product.tax.id)}
                <tr>
                    <td class="table-title">
                        {l s='ID del Impuesto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.tax.id|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}

            {if isset ($product.tax.name)}
                <tr>
                    <td class="table-title">
                        {l s='Nombre del Impuesto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.tax.name|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.tax.percentage)}
                <tr>
                    <td class="table-title">
                        {l s='Porcentaje del Impuesto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.tax.percentage|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.tax.description)}
                <tr>
                    <td class="table-title">
                        {l s='Descripción del Impuesto' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.tax.description|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}

            {if isset ($product.category) && !empty ($product.category)}
                <th colspan="2">Detalle de la categoria</th>
            {/if}

            {if isset ($product.category.id)}
                <tr>
                    <td class="table-title">
                        {l s='ID de la categoría' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.category.id|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if isset ($product.category.name)}
                <tr>
                    <td class="table-title">
                        {l s='Nombre de la categoría' mod='mtsalegraapi'}
                    </td>
                    <td>
                        {$product.category.name|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/if}

            {if isset ($product.price) && !empty ($product.price)}
                <th colspan="2">Detalle de precios</th>
            {/if}

            {foreach key=key item=list from=$product.price}
                {if isset ($list.idPriceList)}
                    <tr>
                        <td class="table-title">
                            {l s='ID de la lista de precios' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$list.idPriceList|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($list.name)}
                    <tr>
                        <td class="table-title">
                            {l s='Nombre de la lista de precios' mod='mtsalegraapi'}
                        </td>
                        <td>
                            {$list.name|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if isset ($list.price)}
                    <tr>
                        <td class="table-title">
                            {l s='Precio' mod='mtsalegraapi'}
                        </td>
                        <td>
                            $ {$list.price|number_format:0|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
{elseif isset ($product.code) && $product.code == '404'}
    <p>{l s='Producto no encontrado, verifique que el ID sea válido.' mod='mtsalegraapi'}</p>
{elseif isset ($errorBO) && $errorBO}
    <p>{l s='Ingrese un ID válido, diferente de 0 (cero).' mod='mtsalegraapi'}</p>
{/if}
<a class="btn btn-primary" href="{$backLink|escape:'htmlall':'UTF-8'}">Volver</a>
