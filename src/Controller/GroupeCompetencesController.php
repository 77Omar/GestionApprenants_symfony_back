<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Repository\CompetencesRepository;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupeCompetencesController extends AbstractController
{
    private $validator;
    private $serializer;
    private $manager;
    public  function  __construct(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $manager){
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
    }
    /**
     * @Route("api/admin/groupeCompetence/{id}", name="put_groupeCompetence",methods={"PUT"})
     */
    public function putCompetenceinGroupCompetence(GroupeCompetencesRepository $groupeCompetencesRepository, CompetencesRepository $competencesRepository, int $id, Request $request)
    {
          $groupeCompJson=$request->getContent();
          $groupeCompTab=$this->serializer->decode($groupeCompJson, "json");
          //dd($groupeCompTab);
          $groupeCompObjet=$groupeCompetencesRepository->find($id);
          //dd($groupeCompObjet);
          if(isset($groupeCompTab['competences'])){
             foreach ($groupeCompTab['competences'] as $competence){
                 if(isset($competence['libelle'])){
                     $requestCompetence=$competencesRepository->findBy(["libelle"=>$competence['libelle']]);
                     //dd($requestCompetence);
                     if(!$requestCompetence){
                       $newCompetence= new Competences();
                       $newCompetence->setLibelle($competence['libelle']);
                       $this->manager->persist($newCompetence);
                       $groupeCompObjet->addCompetence($newCompetence);
                       //dd($newCompetence);
                     }else{
                       $groupeCompObjet->addCompetence($requestCompetence[0]);
                       //dd($groupeCompObjet);
                     }
                 }
                 if(isset($competence['id'])){
                     $requestCompetenceId=$competencesRepository->find($competence['id']);
                     $groupeCompObjet->removeCompetence($requestCompetenceId);
                     $this->manager->persist($groupeCompObjet);
                     //dd($groupeCompObjet);
                 }
             }
          }
        $this->manager->persist($groupeCompObjet);
        $this->manager->flush();
        return $this->json($groupeCompObjet,Response::HTTP_CREATED);

    }
}
