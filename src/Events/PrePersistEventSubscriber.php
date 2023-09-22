<?php

namespace App\Events;


use App\Entity\SlugInterface;
use App\Entity\User;
use App\Service\TextService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PrePersistEventSubscriber implements EventSubscriber
{

    public function __construct(
        private TextService $textService,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void {
        $object = $eventArgs->getObject();

        if ($object instanceof SlugInterface) {
            $object->setSlug($this->textService->slugify($object->getName()));
        }
        if($object instanceof User){
            $plainPassword = $object->getPassword();
            $object->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $object,
                    $plainPassword
                )
            );
        }

    }

}
