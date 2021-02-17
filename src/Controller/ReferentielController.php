<?php

namespace App\Controller;

use App\Entity\GroupeCompetences;
use App\Entity\Referentiel;
use App\Repository\CompetencesRepository;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\ReferentielRepository;
use App\Service\ReferentielService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReferentielController extends AbstractController
{
    private $validator;
    private $serializer;
    private $manager;
    private $referentielService;
    private $repository;
    public  function  __construct(ValidatorInterface $validator, ReferentielRepository $repository, ReferentielService $referentielService, SerializerInterface $serializer, EntityManagerInterface $manager){
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->referentielService= $referentielService;
        $this->repository= $repository;
    }

    /**
     * @Route(
     * path="api/admin/referentiels",
     * name="getprofilsorties",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\ReferentielController::showReferentiel",
     * "_api_resource_class"=Referentiel::class,
     * "_api_collection_operation_name"="get_profilsorties"
     * }
     * )
     */
    public function showReferentiel()
    {
        $repo= $this->repository->findAll("Referentiel");
        return $this->json($repo,Response::HTTP_OK);
    }

    /**
     * @Route("api/admin/referentiels", name="referentiel", methods={"POST"})
     */

        public function addReferentiel(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, GroupeCompetencesRepository $repoGroupeComp)
    {
        $ReferenceTAb= $request->request->all();
        $programme= $request->files->get("programme");
         if ($programme != null) {
           $programmes = fopen($programme->getRealPath(), 'rb');
         }

        $reference=$this->serializer->denormalize($ReferenceTAb,Referentiel::class,'json');
         $reference->setProgramme($programmes);
       // if(is_array($ReferenceTAb['groupeCompetence']) || is_object($ReferenceTAb['groupeCompetence'])){
        foreach ($ReferenceTAb['groupeCompetence'] as $groupeCompetence){
            $groupe= $this->manager->getRepository(GroupeCompetences::class)->find($groupeCompetence);
            $reference->addGroupeCompetence($groupe);
         //}
        }
            $this->manager->persist($reference);
            $this->manager->flush();

        return new JsonResponse("le Referentiel a été ajouté avec succés",Response::HTTP_CREATED);
    }



        /*$data = $request->request->all();
        $referentiel = $serializer->denormalize($data, Referentiel::class, true);

        if (empty($data['groupeCompetence'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
       // dd($data['groupeCompetence']);
       // if(is_array($data['groupeCompetence']) || is_object($data['groupeCompetence'])) {
            foreach ($data['groupeCompetence'] as $value) {
                if ($value != "") {
                    $groupeCompetence = $repoGroupeComp->findBy(array('libelle' => $value));
                    if (!empty($groupeCompetence)) {
                        $referentiel->addGroupeCompetence($groupeCompetence[0]);
                    }
                }
            }
        //}
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

       /* $file = $request->files;
        if (is_null($file->get('programme'))) {
            return new JsonResponse("Le programme est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $fileType = explode("/", $file->get('programme')->getMimeType())[1];
        $filePath = $file->get('programme')->getRealPath();

        $programme = file_get_contents($filePath, 'programme.'.$fileType);
        $referentiel->setProgramme($programme);*/


        /*$em->persist($referentiel);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        */


    /**
     * @Route(
     * name="grpecompetence_by_id",
     * path="/api/admin/referentiels/{id1}/grpecompetences/{id2}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\ReferentielController::getCbyG",
     * "_api_resource_class"=Referentiel::class,
     * "_api_collection_operation_name"="get_grpecompetence_by_id"
     * }
     * )
     */

    public function getCbyG (CompetencesRepository $repo, int $id1, int $id2 )
    {
        $ref= $repo->getCbyGbyRef($id1,$id2);
        //dd($ref);
        return $this->json($ref,Response::HTTP_OK);
    }

    /**
     * @Route("api/admin/referentiels/{id}", name="put_reference",methods={"PUT"})
     */
    public function ModifyReference(Request $request,int $id){
        $this->referentielService->updateReference($request, $id);
        return new JsonResponse("le Reference a été modifié avec succés",Response::HTTP_CREATED);
    }
}
