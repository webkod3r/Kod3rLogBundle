<?php
namespace Kod3r\LogBundle\Utils;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoggerUtils
 *
 * @author  Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\Utils
 */
class LoggerUtils
{
    /**
     * Funcion que retorna un arreglo con la información de contexto en la que se genró el log.
     *
     * @param Controller $controller Controlador desde el que se está emitiendo el log
     * @param Request    $request    Objeto que contiene la información de la petición a la acción
     *
     * @return array    Arreglo con la información de la ruta, URL, parámetros de la petición e
     *                  información del usuario.
     */
    public static function getContext( Controller $controller, Request $request )
    {
        // Array that contains context information about log
        $context = array(
            'route_parameters' => $request->attributes->all(),
            'request_uri'      => $request->getUri(),
        );

        $user = $controller->getUser();
        if( $user ){
            $context[ 'username' ] = $controller->getUser()->getUsername();
            $context[ 'email' ]    = $controller->getUser()->getEmail();
        } else {
            $context[ 'username' ] = 'Anonymous';
        }

        return $context;
    }

    /**
     * Retrieve the Looger instance for custom handler
     *
     * @param Controller $controller
     *
     * @return \Symfony\Bridge\Monolog\Logger
     */
    public static function getLogger( Controller $controller )
    {
        return $controller->get( 'monolog.logger.backtrace' );
    }

    /**
     * @param Controller $controller
     * @param Request    $request
     *
     * @return \stdClass
     */
    public static function getLoggerContext( Controller $controller, Request $request )
    {
        $result          = new \stdClass();
        $result->logger  = $controller->get( 'monolog.logger.backtrace' );
        $result->context = self::getContext( $controller, $request );

        return $result;
    }

    /**
     * Mensaje predeterminado para los logs cuando un usuario accede a una página.
     *
     * @param UserInterface $user       Usuario que solicita la acción.
     * @param Request       $request    Objeto con la petición actual.
     *
     * @return string Mensaje formateado especificando la información del usuario y la página que visitó.
     */
    public static function getRequestMsg( UserInterface $user, Request $request )
    {
        return sprintf(
            'El usuario %s con identificador %d, visitó la página %s',
            $user->getUsername(),
            $user->getId(),
            $request->getUri()
        );
    }

    /**
     * Mensaje predeterminado para los logs de adición.
     *
     * @param UserInterface $user
     * @param string        $objectName
     * @param string        $objectId
     *
     * @return string Mensaje formateado
     */
    public static function getAdditionMsg( UserInterface $user, $objectName, $objectId )
    {
        return sprintf(
            'El usuario %s con identificador %d, adicionó el objeto %s con identificador %d.',
            $user->getUsername(),
            $user->getId(),
            $objectName,
            $objectId
        );
    }

    /**
     * Mensaje predeterminado para los logs de modificación.
     *
     * @param UserInterface $user
     * @param string        $objectName
     * @param string        $objectId
     *
     * @return string Mensaje formateado
     */
    public static function getModificationMsg( UserInterface $user, $objectName, $objectId )
    {
        return sprintf(
            'El usuario %s con identificador %d, modificó el objeto %s con identificador %d.',
            $user->getUsername(),
            $user->getId(),
            $objectName,
            $objectId
        );
    }

    /**
     * Mensaje predeterminado para los logs de eliminación.
     *
     * @param UserInterface $user
     * @param string        $objectName
     * @param string        $objectId
     *
     * @return string
     */
    public static function getDeletionMsg( UserInterface $user, $objectName, $objectId )
    {
        return sprintf(
            'El usuario %s con identificador %d, eliminó el objeto %s con identificador %d.',
            $user->getUsername(),
            $user->getId(),
            $objectName,
            $objectId
        );
    }
}
