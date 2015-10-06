<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 10/5/2015
 * Time: 9:41 a.m.
 */

namespace Kod3r\LogBundle\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class BacktraceLoggerListener
 *
 * Define our backtrace listener
 *
 * The backtrace always comes in handy, lets configure an event listener to
 * add this to the logger object.
 *
 * @package Kod3r\LogBundle\EventListener
 */
class BacktraceLoggerListener
{
    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException( GetResponseForExceptionEvent $event )
    {
        $this->logger->error($event->getException());
    }
}
