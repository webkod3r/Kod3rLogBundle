<?php
namespace Kod3r\LogBundle\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BacktraceLoggerListener
 *
 * Define our backtrace listener
 *
 * The backtrace always comes in handy, lets configure an event listener to
 * add this to the logger object.
 *
 * @author  Pablo Molina <web.kod3r@gmail.com>
 * @package Kod3r\LogBundle\EventListener
 */
class BacktraceLoggerListener
{
    /**
     * @var $logger LoggerInterface
     */
    protected $logger;

    /**
     * @var $tokenStorage TokenStorage
     */
    protected $tokenStorage;

    public function __construct( LoggerInterface $logger, TokenStorage $tokenStorage )
    {
        $this->logger       = $logger;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException( GetResponseForExceptionEvent $event )
    {
        $this->logger->error( $event->getException()->getMessage() );
    }

    /**
     * Sel log message on every controller request.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest( GetResponseEvent $event )
    {
        $msg   = null;
        $token = $this->tokenStorage->getToken();

        if( $token instanceof AnonymousToken ){
            $msg = sprintf(
                'El usuario %s accedió a: %s',
                $token->getUser(),
                $event->getRequest()->getUri()
            );
            $this->logger->info( $msg );
        } elseif( $token !== null && $token->getUser() instanceof UserInterface ) {
            $msg = sprintf(
                'El usuario %s con identificador %d accedió a: %s',
                $token->getUser()->getUsername(),
                $token->getUser()->getId(),
                $event->getRequest()->getUri()
            );
            $this->logger->info( $msg );
        }
    }
}
