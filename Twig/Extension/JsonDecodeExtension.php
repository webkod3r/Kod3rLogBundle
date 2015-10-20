<?php
namespace Kod3r\LogBundle\Twig\Extension;

/**
 * Class JsonDecodeExtension
 *
 * @author  Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\Twig\Extension
 */
class JsonDecodeExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'json_decode';
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'json_decode',
                array( $this, 'twig_jsondecode_filter' ),
                array( 'is_safe' => array( 'html' ) )
            ),
        );
    }

    /**
     * Decodifica un string JSON.
     *
     * @param string    $value      The value to encode.
     * @param boolean   $assoc      Cuando es TRUE, los objects retornados se convertirán en arrays asociativos
     * @param int       $depth
     *
     * @return mixed The PHP variable from decoded JSON.
     */
    public function twig_jsondecode_filter($value, $assoc = false, $depth = 512)
    {
        return json_decode($value, $assoc, $depth);
    }
}
