<?php
namespace Kod3r\LogBundle\Processor;


use Symfony\Bridge\Monolog\Processor\WebProcessor;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class RequestProcessor
 *
 * Define our request processor
 *
 * This class can be used to add additional data to the logging record. For
 * example we have access to the session object, we could inspect that object
 * for additional information to log. This could be used for a variety of
 * purposes, eg: adding server data, post data etc.
 *
 * @author Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\Processor
 */
class RequestProcessor extends WebProcessor
{
    /**
     * @var $session Session
     */
    private $session;

    public function __construct( Session $session, array $extraFields = null )
    {
        parent::__construct( $extraFields );
        $this->session = $session;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function processRecord( array $record )
    {
        //$record['extra']['serverData'] = "";

        if( is_array( $this->extraFields ) ){
            foreach( $this->extraFields as $key => $value ){

                // Retrieve real server data from extra fields
                $value = isset( $this->serverData[ $value ] ) ? $this->serverData[ $value ] : '';

                if( is_array( $value ) ){
                    $value = print_r( $value, true );
                }

                $record[ 'extra' ][ $key ] = $value;
            }
        }

        return $record;
    }
}
