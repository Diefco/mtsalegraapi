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

<h1>{l s='Plataforma' mod='mtsalegraapi'}</h1>
<div class="row container">
    <div class="col-md-1"></div>
    <div class="btn-group-vertical col-md-3" role="group">
        <h2>{l s='Productos' mod='mtsalegraapi'}</h2>
        <a href="{$urlArray.productConsultOne|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar un producto' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.productConsultMultiple|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar varios productos' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.productCreate|escape:'htmlall':'UTF-8'}" class="btn btn-primary">{l s='Crear productos' mod='mtsalegraapi'}</a>
        {*<a href="{$urlArray.productEdit|escape:'htmlall':'UTF-8'}" class="btn btn-warning">{l s='Editar productos' mod='mtsalegraapi'}</a>*}
        {*<a href="{$urlArray.productDelete|escape:'htmlall':'UTF-8'}" class="btn btn-danger">{l s='Eliminar productos' mod='mtsalegraapi'}</a>*}
    </div>

    <div class="btn-group-vertical col-md-3" role="group">
        <h2>{l s='Contactos' mod='mtsalegraapi'}</h2>
        <a href="{$urlArray.contactConsultOne|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar un cliente' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.contactConsultMultiple|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar varios clientes' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.contactCreate|escape:'htmlall':'UTF-8'}" class="btn btn-primary">{l s='Crear clientes' mod='mtsalegraapi'}</a>
        {*<a href="{$urlArray.contactEdit|escape:'htmlall':'UTF-8'}" class="btn btn-warning">{l s='Editar clientes' mod='mtsalegraapi'}</a>*}
        {*<a href="{$urlArray.contactDelete|escape:'htmlall':'UTF-8'}" class="btn btn-danger">{l s='Eliminar clientes' mod='mtsalegraapi'}</a>*}
    </div>

    <div class="btn-group-vertical col-md-3" role="group">
        <h2>{l s='Facturas' mod='mtsalegraapi'}</h2>
        <a href="{$urlArray.invoiceConsultOne|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar una factura' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.invoiceConsultMultiple|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Consultar varias facturas' mod='mtsalegraapi'}</a>
        <a href="{$urlArray.invoiceCreate|escape:'htmlall':'UTF-8'}" class="btn btn-primary">{l s='Crear facturas' mod='mtsalegraapi'}</a>
        {*<a href="{$urlArray.invoiceEdit|escape:'htmlall':'UTF-8'}" class="btn btn-warning">{l s='Editar facturas' mod='mtsalegraapi'}</a>*}
        {*<a href="{$urlArray.invoiceDelete|escape:'htmlall':'UTF-8'}" class="btn btn-danger">{l s='Eliminar facturas' mod='mtsalegraapi'}</a>*}
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row container">
    <form class="form-inline" action="{$urlLogOut|escape:'htmlall':'UTF-8'}" method="post" accept-charset="utf-8">
            <input type="hidden" class="form-control" id="logout" name="logout" value="true">
        <button type="submit" class="btn btn-default">{l s='Salir' mod='mtsalegraapi'}</button>
    </form>
</div>
