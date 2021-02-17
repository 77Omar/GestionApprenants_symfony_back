<?php

namespace App\Service;

use App\Entity\GroupeCompetences;
use App\Entity\Referentiel;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferentielService
{

    private $manager;
    private $serializer;
    private $validator;
    private $referentielRepository;

    public function __construct(EntityManagerInterface $manager, ReferentielRepository $referentielRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->referentielRepository = $referentielRepository;

    }


    public function updateReference(Request $request, int $id){
        $dataReferentiel= $request->request->all();
        //dd($dataReferentiel);
        $programme=$request->files->get('programme');
        if ($programme){
            $programme= fopen($programme->getRealPath(),'rb');
        }
        $typeReferentielObject=$this->referentielRepository->find($id);
       //dd($typeReferentielObject);
        if(isset($dataReferentiel['libelle'])){
                $typeReferentielObject->setLibelle($dataReferentiel['libelle']);
                //dd($typeReferentielObject);
             }
        if(isset($dataReferentiel['presentation'])){
                $typeReferentielObject->setPresentation($dataReferentiel['presentation']);
        }
        if(isset($dataReferentiel['criteresAdmission'])){
                $typeReferentielObject->setCriteresAdmission($dataReferentiel['criteresAdmission']);

        }
        if(isset($dataReferentiel['criteresEvaluation'])){
                $typeReferentielObject->setCriteresEvaluation($dataReferentiel['criteresEvaluation']);
        }
        if(isset($dataReferentiel['programme'])){
                $typeReferentielObject->setProgramme($dataReferentiel['programme']);
                //dd($typeReferentielObject);
        }
        //dd($typeReferentielObject);
         foreach ($dataReferentiel['groupeCompetence'] as $key=>$value){
             $groupe= $this->manager->getRepository(GroupeCompetences::class)->find($value) ;
             //dd($groupe);
             if ($key == "add"){
                 $typeReferentielObject->addGroupeCompetence($groupe);
                 //dd($typeReferentiel);
             }
            else{
                $typeReferentielObject->removeGroupeCompetence($groupe);
             }
         }
        $this->manager->persist($typeReferentielObject);
        $this->manager->flush();
        if ($programme){
            fclose($programme);
        }
        return new JsonResponse("le Referentiel a été modifié avec succés",Response::HTTP_CREATED);
        //dd($typeReferentiel);

    }
}
