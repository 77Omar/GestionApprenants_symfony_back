<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\ProfilSorties;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{

    private $PS_repo;
    private $Promo_repo;
    private $em;

    public function __construct(
        ProfilSortiesRepository $PS_repo,
        PromoRepository $Promo_repo,
        EntityManagerInterface $em)
    {
        $this->PS_repo = $PS_repo;
        $this->Promo_repo = $Promo_repo;
        $this->em = $em;
    }

    /**
     * @Route(
     * path="api/admin/profilsorties",
     * name="getprofilsortie",
     * methods={"GET"},
     * defaults={
     * "_controller"="\App\Controller\ProfilSortieController::showProfilSortie",
     * "_api_resource_class"=ProfilSorties::class,
     * "_api_collection_operation_name"="get_profilsortie"
     * }
     * )
     */
    public function showProfilSortie()
    {
        $profilsorties= $this->PS_repo->findAll("ProfilSorties");
        return $this->json($profilsorties,Response::HTTP_OK);
    }

    /**
     * @Route(
     * path="api/admin/promo/{id}/profilsorties",
     * name="profil_sortie_by_promo_id",
     * methods={"GET"},

     * )
     */

    public function showApprenantPromoByProfilSortie($id, SerializerInterface $serializer)
    {


        $promo = $this->Promo_repo->find($id);
        if(!empty($promo)){
            $profilSorties = [];
            foreach ($promo->getGroupe() as $groupe) {
                foreach ($groupe->getApprenants() as $apprenant){
                    $PS = $apprenant->getProfilSorties();
                    foreach ($PS as $ps) {
                        if(!in_array($ps,$profilSorties)){
                            $profilSorties[] = $ps;
                        }
                    }
                }
            }
            return $this->json($profilSorties, 200,[],["groups"=>["groupe:ApprenantPromoByProfilSortie"]]);
        }else{
            return new Response("promo inexistante");
        }

    }

    /**
     * @Route(
     * "api/admin/profilsorties",
     * name="addProfilSortie",
     * methods={"POST"},
    * defaults={
     * "_controller"="\App\Controller\ProfilSortieController::AddProfilSortie",
     * "_api_resource_class"=ProfilSorties::class,
     * "_api_collection_operation_name"="add_ProfilSortie"
     * }
     * )
     */
    public function AddProfilSortie(SerializerInterface $serializer,Request $request)
    {
        $profilsortie = $request->getContent();
        $PS = $serializer->deserialize($profilsortie, ProfilSorties::class, 'json');
        $this->em->persist($PS);
        $this->em->flush();
        return new JsonResponse("vous avez ajouter un $profilsortie succes",Response::HTTP_CREATED);
    }


    /**
     * @Route(
     * "api/admin/profilsortie/{id}",
     * name="editeProfilSortie",
     * methods={"PUT"},
     * )
     */
    public function EditeProfilSortie($id ,Request $request, SerializerInterface $serializer)
    {

        $profilsortie =$this->PS_repo->find($id);
        $data_Tab = json_decode($request->getContent(),true);
        if(isset($data_Tab['libelle'])){
            $profilsortie->setLibelle($data_Tab['libelle']);
            //dd($profilsortie);
        }

        $this->em->persist($profilsortie);
        $this->em->flush();
        return new JsonResponse("vous avez modifie un profil de sortie succes",Response::HTTP_CREATED);
        // return $this->json($profilsorties,Response::HTTP_OK);
    }

    /**
     * @Route(
     * "api/admin/promo/{id}/profilsortie/{id1}",
     * name="profil_sortie_by_id",
     * methods={"GET"},
     * defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "profil_sortie_by_id"
     *  }
     * )
     */
    public function showApprenantPromoByProfilSortieById($id, $id1, PromoRepository $prom_rep)
    {
        //trouve identifiant du promo donné
        $promo = $prom_rep->find($id);
        if(!empty($promo)){
            $profilSorties = [];
            foreach ($promo->getGroupe() as $groupe) {
                foreach ($groupe->getApprenants() as $apprenant){
                    foreach ($apprenant->getProfilSorties() as $ps) {

                        if($ps->getId() == $id1){

                            $profilSorties = $this->PS_repo->find($id1);

                        }else{
                            $profilSorties = 0;
                        }
                    }
                }
            }

            return $this->json($profilSorties, 200,[],["groups"=>["groupe:ApprenantPromoByProfilSortieById"]]);
        }else{
            return new Response("promo inexistante");
        }

    }
}
