Kod3rLogBundle
==============

Cómo usar este bundle:

* [Instalación](#instalacion)
* [Configuración](#configuracion)
* [Cómo Usar](#como-usar)
* [Historial](#historial)
* [TODO](#todo)


Instalación
-----------

De momento el bundle no esta disponible para ser instalado mediante composer.
Por lo tanto la instalación debe realizarse de forma manual.

Copie el directorio `Kod3r/LogBundle` en su directorio `src`

Registre el bundle en `app/AppKernel.php`:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Kod3r\LogBundle\Kod3rLogBundle(),
    );
}
```


Configuración
-------------

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


Cómo Usar
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

### v1.0.1
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
