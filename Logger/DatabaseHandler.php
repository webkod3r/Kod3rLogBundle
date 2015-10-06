<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 10/3/2015
 * Time: 3:33 p.m.
 */

namespace Kod3r\LogBundle\Logger;


use Doctrine\ORM\EntityManagerInterface;
use Kod3r\LogBundle\Entity\SystemLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class DatabaseHandler
 *
 * Handler to store logs in database.
 *
 * @author  Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\Logger
 */
class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param integer $level  The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct( $level = Logger::DEBUG, $bubble = true )
    {
        parent::__construct( $level, $bubble );
    }

    /**
     * @param EntityManagerInterface $em Object Manager to persists records
     */
    public function setEntityManager( EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write( array $record )
    {
        // Ensure the doctrine channel is ignored (unless its greater than a warning error), otherwise you will create an infinite loop, as doctrine like to log.. a lot..
        if( 'doctrine' == $record[ 'channel' ] ){
            if( (int)$record[ 'level' ] >= Logger::WARNING ){
                error_log( $record[ 'message' ] );
            }

            return;
        }

        // Only log errors greater than a warning
        //@todo - you could ideally add this into configuration variable
        if( (int)$record[ 'level' ] >= Logger::INFO ){
            try {
                // Create entity and fill with data
                $entity = new SystemLog();
                $entity
                    ->setChannel( $record[ 'channel' ] )
                    ->setLog( $record[ 'message' ] )
                    ->setLevel( $record[ 'level' ] )
                    ->setFormattedMsg( $record[ 'formatted' ] )
                    ->setCreatedAt( new \DateTime() );

                $this->em->persist( $entity );
                $this->em->flush();

            } catch( \Exception $e ){
                // Fallback to just writing to php error logs if something really bad happens
                error_log( $record[ 'message' ] );
                error_log( $e->getMessage() );
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultFormatter()
    {
        return new LineFormatter('[%datetime%] %channel%.%level_name%: %message% {"context":%context%, "extra": %extra%}');
    }
}
