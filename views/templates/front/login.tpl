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

{if isset($error)}
    {l s='Error' mod='mtsalegraapi'}: {$error|escape:'htmlall':'UTF-8'}.
{/if}
<div>
    <form class="form-inline" action="{$urlForm|escape:'htmlall':'UTF-8'}" method="post" accept-charset="utf-8">
        <div class="form-group">
            <label class="sr-only" for="user">{l s='Usuario' mod='mtsalegraapi'}</label>
            <input type="text" class="form-control" id="user" name="user"
                   placeholder="{l s='Usuario' mod='mtsalegraapi'}">
        </div>
        <div class="form-group">
            <label class="sr-only" for="password">{l s='Contraseña' mod='mtsalegraapi'}</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="{l s='Contraseña' mod='mtsalegraapi'}">
        </div>
        <button type="submit" class="btn btn-default">{l s='Ingresar' mod='mtsalegraapi'}</button>
    </form>
</div>
