<?php
namespace Kod3r\LogBundle\Utils;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoggerUtils
 *
 * @author Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\Utils
 */
class LoggerUtils
{
    /**
     * Funcion que retorna un arreglo con la información de contexto en la que se genró el log.
     *
     * @param Controller $controller    Controlador desde el que se está emitiendo el log
     * @param Request    $request       Objeto que contiene la información de la petición a la acción
     *
     * @return array    Arreglo con la información de la ruta, URL, parámetros de la petición e información del usuario.
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
        }else{
            $context['username'] = 'Anonymous';
        }

        return $context;
    }
}
