# Kod3lLogBundle
Symfony2 logger bundle saved in database using Doctrine2 ORM

* [Instalación](#instalacion)
* [Cómo Usar](#como-usar)
* [Historial](#historial)
* [TODO](#todo)


Instalacion
-----------

1. [Descargar Kod3rLogBundle using composer](#descargar-kod3rlogbundle)
2. [Habilitar el Bundle](#habilitar-el-bundle)
3. [Configure el bundle en su fichero config.yml](#configuracion-del-bundle)


#### Descargar Kod3rLogBundle

Ejecute el siguiente comando en la raiz de su proyecto:

```
$php composer require kod3r/log:dev-master
```

O adicione el bundle `kod3r/log` en su fichero `composer.json` como se muestra a continuación:

``` js
"require": {
    ...
    "kod3r/log": "dev-master"
}
```

Actualice o instale el bundle ejecutando 

```
$php composer update kod3r/log
```


#### Habilitar el Bundle

Registre el bundle en `app/AppKernel.php`:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        ...
        new Kod3r\LogBundle\Kod3rLogBundle(),
    );
}
```


#### Configuracion del Bundle

Después de registrar el bundle, diríjase al fichero `config.yml` si desea establecer
una configuración general para todos los entornos de producción. Si sólo desea
establecer la configuración en entorno de produción agregue esta configuración en
`config_prod.yml`

Normalmente la configuración de los logs no se encuentra establecida en el
fichero de configuración, en caso de que esté definida mezcle las opciones de
`monolog` definidas con las que acá se describen.

``` yaml
# app/config/config.yml
monolog:
    handlers:
        backtrace:
            type: service
            level: warning # Mínimo nivel de log que se desea almacenar
            id: kod3r_log.logger_database # Servicio para inicializar el manejador de BD
            channels: ["!doctrine"] # Excluir el canal de doctrine
```


Como Usar
---------

Para incluir logs en su aplicación emplee el siguiente ejemplo dentro de las acciones
de su controlador.

``` php
use Symfony\Component\HttpFoundation\Request;

public function indexAction( Request $request ){
  // Obtener el manejador de logs
  $logger = $this->get( 'monolog.logger.backtrace' );

  // Obtener información de contexto a traves del servicio definido
  $context = $this->get('kod3r_log.logger_utils')->getContext($this, $request);

  // Definir parámetros extras del contexto
  $context = array(
    'custom': 'value'
  );

  // Agregar el log y el contexto
  $logger->warning( 'Este es un mensaje de WARNING!!!', $context );
}
```


Historial
---------

### v1.0.2 (2015-10-06)
- Agregando soporte para instalación mediante composer.
- Actualizando dependencias en `composer.json`

### v1.0.1 (2015-10-01)
- Cambiando el formateador de los mensajes de error, en vez de `JsonFormatter`
  ahora se usa `LineFormatter` y la información extendida se almacena como una
  cadena compuesta en JSON.
- Agregando la clase `LoggerUtils` para facilitar la captura de información de
  contexto a través del servicio `$context = $this->get('kod3r_log.logger_utils')->getContext($this, $request);`

### v1.0.0
- Versión inicial


TODO
----

- Definir árbol de configuración del bundle.
- Permitir al desarrollador definir el nombre del canal de logs que desee.
