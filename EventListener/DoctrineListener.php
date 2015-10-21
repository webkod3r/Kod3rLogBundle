<?php
namespace Kod3r\LogBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DoctrineListener
{
    /**
     * @var $tokenStorage TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var $container Container
     */
    protected $container;

    public function __construct( Container $container )
    {
        $this->container    = $container;
        $this->tokenStorage = $container->get('security.token_storage');
    }

    /**
     * Log doctrine event after persist entity.
     *
     * @param LifecycleEventArgs $event
     */
    public function postPersist( LifecycleEventArgs $event )
    {
        $msg       = null;
        $className = str_replace("\\", "\\\\", get_class( $event->getEntity() ));

        if( !( $event->getEntity() instanceof \Kod3r\LogBundle\Entity\SystemLog ) ){
            $token = $this->tokenStorage->getToken();
            $logger = $this->container->get('monolog.logger.backtrace');

            // Obtener el identificador de la entidad que acaba de ser adicionada
            $entityIdArray = $event->getEntityManager()->getUnitOfWork()->getEntityIdentifier($event->getEntity());
            $context = $event->getEntityManager()->getUnitOfWork()->getOriginalEntityData($event->getEntity());

            if( $token instanceof AnonymousToken ){
                $msg = sprintf(
                    'El usuario %s agregó la entidad %s con identificador(es) %s',
                    $token->getUser(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->notice( $msg, $context );
            }
            elseif( $token !== null && $token->getUser() instanceof UserInterface ) {
                $msg = sprintf(
                    'El usuario %s con identificador %d agregó la entidad %s con identificador(es) %s',
                    $token->getUser()->getUsername(),
                    $token->getUser()->getId(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->notice( $msg, $context );
            }
        }
    }

    /**
     * Log doctrine event after update an entity
     *
     * @param LifecycleEventArgs $event
     */
    public function postUpdate( LifecycleEventArgs $event )
    {
        $msg       = null;
        $className = str_replace("\\", "\\\\", get_class( $event->getEntity() ));

        if( !( $event->getEntity() instanceof \Kod3r\LogBundle\Entity\SystemLog ) ){
            $token = $this->tokenStorage->getToken();
            $logger = $this->container->get('monolog.logger.backtrace');

            // Obtener el identificador de la entidad que acaba de ser adicionada
            $entityIdArray = $event->getEntityManager()->getUnitOfWork()->getEntityIdentifier($event->getEntity());
            $context = $event->getEntityManager()->getUnitOfWork()->getOriginalEntityData($event->getEntity());

            if( $token instanceof AnonymousToken ){
                $msg = sprintf(
                    'El usuario %s actualizó la entidad %s con identificador(es) %s',
                    $token->getUser(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->notice( $msg, $context );
            }
            elseif( $token !== null && $token->getUser() instanceof UserInterface ) {
                $msg = sprintf(
                    'El usuario %s con identificador %d actualizó la entidad %s con identificador(es) %s',
                    $token->getUser()->getUsername(),
                    $token->getUser()->getId(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->notice( $msg, $context );
            }
        }
    }

    /**
     * Log doctrine event before remove an entity.
     *
     * @param LifecycleEventArgs $event
     */
    public function preRemove( LifecycleEventArgs $event )
    {
        $msg       = null;
        $className = str_replace("\\", "\\\\", get_class( $event->getEntity() ));

        if( !( $event->getEntity() instanceof \Kod3r\LogBundle\Entity\SystemLog ) ){
            $token = $this->tokenStorage->getToken();
            $logger = $this->container->get('monolog.logger.backtrace');

            // Obtener el identificador de la entidad que acaba de ser adicionada
            $entityIdArray = $event->getEntityManager()->getUnitOfWork()->getEntityIdentifier($event->getEntity());
            $context = $event->getEntityManager()->getUnitOfWork()->getOriginalEntityData($event->getEntity());

            if( $token instanceof AnonymousToken ){
                $msg = sprintf(
                    'El usuario %s eliminó la entidad %s con identificador(es) %s',
                    $token->getUser(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->warning( $msg, $context );
            }
            elseif( $token !== null && $token->getUser() instanceof UserInterface ) {
                $msg = sprintf(
                    'El usuario %s con identificador %d eliminó la entidad %s con identificador(es) %s',
                    $token->getUser()->getUsername(),
                    $token->getUser()->getId(),
                    $className,
                    join(' y ', $entityIdArray)
                );
                $logger->warning( $msg, $context );
            }
        }
    }
}
