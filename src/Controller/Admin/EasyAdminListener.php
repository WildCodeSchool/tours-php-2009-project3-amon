<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class EasyAdminListener implements EventSubscriberInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    /**
     * @return string[][]
     */
    #[ArrayShape([AfterEntityPersistedEvent::class => "string[]",
        AfterEntityUpdatedEvent::class => "string[]",
        AfterEntityDeletedEvent::class => "string[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['flashMessageAfterPersist'],
            AfterEntityUpdatedEvent::class => ['flashMessageAfterUpdate'],
            AfterEntityDeletedEvent::class => ['flashMessageAfterDelete'],
        ];
    }

    public function flashMessageAfterPersist(AfterEntityPersistedEvent $event): void
    {
        $this->session->getFlashBag()->add(
            'success',
            '"' . $event->getEntityInstance() . '"' . ' a été crée avec succès.'
        );
    }

    public function flashMessageAfterUpdate(AfterEntityUpdatedEvent $event): void
    {
        $this->session->getFlashBag()->add(
            'success',
            '"' . $event->getEntityInstance() . '"'  . ' a été mis à jour.'
        );
    }

    public function flashMessageAfterDelete(AfterEntityDeletedEvent $event): void
    {
        $this->session->getFlashBag()->add(
            'success',
            '"' . $event->getEntityInstance() . '"'  . ' a été supprimée.'
        );
    }
}
