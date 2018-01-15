# ScriptsCiscoContactCenter
Generador de Scripts y Consulta de llamadas para Contact Center Enterprise.

# Generador de Scripts para Cisco & Consulta de llamadas ANI DNIS en Contact Center Enterprise

Esto es un Script hecho con CodeIgniter, código antiguo que posiblemente es necesario actualizarlo pero completamente funcional.

Hecho en PHP versión 5.1.6 y con CodeIgniter 2.1.3

## Instalación

Para el caso de Consultas de llamadas:
Necesitan cambiar la configuración para acceder la Base de Datos de SQL Server,  recordando que Cisco Contact Center Enterprise usa como Base Datos Windows Server 2012 y SQL Server. Dicha configuración se encuentra:

.\application\config\database.php

Para el caso de Generador de Scripts para Cisco:
En este caso necesitan adaptarlo a sus necesidades, en este script que desarrolle hace lo siguiente:
El archivo en cuestión se localiza en:

.\application\controllers\search.php

## Uso
Solo necesitan abrir el Index.php y realizar las pruebas.
Recuerdan cambiar la configuración.

El scripts realiza lo siguiente:

 - Niega los comandos dial-peer & h323-gateway
 - Verifica la posicion del FXO y FXS para aplicar la configuracion en la posición correcta.
 - Incluye un template General la cual no aplica configuracion o
   modificacion.
  - Verifica si la IP es correcta.
  - Dependiendo de la terminación del ANI aplica las descripcion correspondiente.
  - Genera Scripts también para Switch Cisco.
 
## Contribución

Cualquier tipo de ayuda es Bienvenida. Tengo planes de actualizar en un futuro para mas general y actualizarlo para que funcione con el PHP & CodeIgniter para reciente. No tengo fechas pero espero encontrarle uso futuro para empezar hacerlo.


# Generator Scripts to Cisco & Get Calls History in Contact Center Enterprise
