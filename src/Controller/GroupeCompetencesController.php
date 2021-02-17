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
          $groupeCompObjet=$groupeCompetencesRepository->find($id);
          if(isset($groupeCompTab['competences'])){
             foreach ($groupeCompTab['competences'] as $competence){
                 if(isset($competence['libelle'])){
                     $requestCompetence=$competencesRepository->findBy(["libelle"=>$competence['libelle']]);
                     if(!$requestCompetence){
                       $newCompetence= new Competences();
                       $newCompetence->setLibelle($competence['libelle']);
                       $this->manager->persist($newCompetence);
                       $groupeCompObjet->addCompetence($newCompetence);
                       // dd($groupeCompObjet);
                     }else{
                       $groupeCompObjet->addCompetence($requestCompetence[0]);
                     }
                 }
                 if(isset($competence['id'])){
                     $requestCompetenceId=$competencesRepository->find($competence['id']);
                     //dd($requestCompetenceId);
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

    /**
     * @Route("api/admin/groupeCompetence", name="add_groupcomp",methods={"POST"})
     */
    public function addGroupCompetence(CompetencesRepository $competencesRepository, Request $request){
        $groupeComp= $request->getContent();
        $groupeCompTab = $this->serializer->decode($groupeComp,"json");
        //dd($groupeCompTab);
        if(isset($groupeCompTab['competences'])){
            $Competences=$groupeCompTab['competences'];
        }
        $groupeCompTab['competences'] = [];
        $groupeCompObjet = $this->serializer->denormalize($groupeCompTab, "App\Entity\GroupeCompetences");
        //dd($groupeCompObjet);
        if(count($Competences)){
            foreach ($Competences as $competence){
                if(isset($competence['id'])){
                    $competencefind=$competencesRepository->findOneBy(["id"=>$competence['id']]);
                   //dd($competencefind);
                    if (!$competencefind) {
                        $newCompetence= new Competences();
                        $newCompetence->setLibelle($competence['libelle']);
                        $this->manager->persist($newCompetence);
                        $groupeCompObjet->addCompetence($newCompetence);
                        //dd($groupeCompObjet);
                    }
                    $groupeCompObjet->addCompetence($competencefind);
                   //dd($groupeCompObjet);
                }
                else{
                    $newCompetence=$this->serializer->denormalize($competence, "App\Entity\Competences");
                    //dd($newComp);
                    $error = $this->validator->validate($newCompetence);
                    if(count($error))
                    {
                        return $this->json($error,Response::HTTP_BAD_REQUEST);
                    }
                    $this->manager->persist($newCompetence);
                    $groupeCompObjet->addCompetence($newCompetence);
                    //dd($groupeTagObjet);
                }
            }

        }
        else {
            return $this->json(["message" => "Le competence est obligatoire."],Response::HTTP_NOT_FOUND);
        }
        $this->manager->persist($groupeCompObjet);
        $this->manager->flush();
        return $this->json($groupeCompObjet,Response::HTTP_CREATED);
    }

}
