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

<div class="panel" style="{if isset($displayTooltip) && $displayTooltip == 'true'}display: none;{/if}">
	<h3><i class="icon icon-line-chart"></i> {l s='Metasysco.com - Facturación electrónica a través de Alegra' mod='mtsalegraapi'}</h3>
	<p>
		<strong>{l s='Módulo para la gestión administrativa de facturas, productos y clientes.' mod='mtsalegraapi'}</strong><br />
		{l s='A través de este módulo podrá realizar las siguientes operaciones:' mod='mtsalegraapi'}<br />
		<ul><strong>{l s='Facturas' mod='mtsalegraapi'}:</strong>
			<li>{l s='Cargar las facturas con las cuales ha confirmado el pago, de forma manual.' mod='mtsalegraapi'}</li>
			<li>{l s='Ignorar aquellas facturas que no fueron canceladas, o que se usaron con propositos de prueba (incluyendo compras demo).' mod='mtsalegraapi'}</li>		
		</ul>
		<ul><strong>{l s='Productos' mod='mtsalegraapi'}:</strong>
			<li>{l s='Cargar los productos que se hayan creado y que hayan sido adquiridos, de forma manual.' mod='mtsalegraapi'}</li>
			<li>{l s='Ignorar aquelloss productos que fueron creados con propositos de pruebas (incluyendo productos demo).' mod='mtsalegraapi'}</li>
		</ul>
		<ul><strong>{l s='Contactos o Clientes' mod='mtsalegraapi'}:</strong>
			<li>{l s='Cargar los clientes con las cuales ha confirmado el registro o su existencia, de forma manual.' mod='mtsalegraapi'}</li>
			<li>{l s='Ignorar aquellos clientes que fueron creados con propositos de pruebas (incluyendo clientes demo).' mod='mtsalegraapi'}</li>		
		</ul>
	</p>
</div>

<div class="panel" style="{if isset($displayTooltip) && $displayTooltip == 'true'}display: none;{/if}">
	<h3><i class="icon icon-tags"></i> {l s='Para tener en cuenta' mod='mtsalegraapi'}</h3>
	<p>
		&raquo; {l s='Estas son algunas de las recomendaciones que hacemos para el correcto uso del módulo' mod='mtsalegraapi'} :
		<ul>
			<li>{l s='El usuario debe contener mas de 8 carácteres para ser válido.' mod='mtsalegraapi'}</li>
			<li>{l s='El usuario puede contener mayúsculas, minúsculas, números, pero no carácteres especiales.' mod='mtsalegraapi'}</li>
			<li>{l s='La contraseña debe contener mas de 8 carácteres para ser válida.' mod='mtsalegraapi'}</li>
			<li>{l s='Por motivos de seguidad, la contraseña no será revelada al momento de guardar la información' mod='mtsalegraapi'}</li>
			<li>{l s='Asegúrese que al ingresar el Email con el cual esta registrado en la API de Alegra, sea exactamente igual.' mod='mtsalegraapi'}</li>
			<li>{l s='Asegúrese de que el Email y el Token no contengan espacios en blanco al principio o fin de cada uno.' mod='mtsalegraapi'}</li>
			<li>{l s='Tenga en cuenta que tanto el Email como el Token son sencibles a las mayúsculas y minúsculas.' mod='mtsalegraapi'}</li>
		</ul>
		{l s='Mientras cumpla las anteriores recomendaciones, la conexión con la API de Alegra será exitosa.' mod='mtsalegraapi'}
	</p>
</div>

<div class="panel">
	<h3><i class="icon icon-sign-in"></i> {l s='Acceso a la plataforma' mod='mtsalegraapi'}</h3>
	<p>
		&raquo; {l s='Para acceder a la plataforma, haga clic en el siguiente enlace:' mod='mtsalegraapi'} <a
                href="{$moduleLoginLink|escape:'htmlall':'UTF-8'}" target="_blank">Alegra API</a>.
	</p>
</div>
