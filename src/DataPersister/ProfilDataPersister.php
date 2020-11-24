<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use function Matrix\trace;

class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;


    public function __construct(EntityManagerInterface $em){
        $this->_entityManager=$em;
    }
    public function supports($data, array $context = []): bool
    {
        //L'opérateur instanceof permet de vérifier si tel objet est une instance de telle classe.
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $profil=$data;
        $profil->setIsDeleted(true);
        $this->_entityManager->persist($profil);
        $users=$data->getUsers();
        foreach ($users as $user){
            $archivedUser=$user->setStatut(true);
            $this->_entityManager->persist($archivedUser);
        }
        $this->_entityManager->flush();
        return new Response("Profil archiver");
    }

}