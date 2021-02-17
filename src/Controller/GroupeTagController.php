<?php

namespace App\Controller;


use App\Entity\Tag;
use App\Entity\GroupeTag;
use App\Repository\GroupeTagRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupeTagController extends AbstractController
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
     * @Route("api/admin/groupstags", name="add_grouptag",methods={"POST"})
     */
    public function addGroupTag(TagRepository $tagRepository, Request $request){
        $groupeTag= $request->getContent();
        $groupeTagTab = $this->serializer->decode($groupeTag,"json");
        if(isset($groupeTagTab['tags'])){
            $Tag=$groupeTagTab['tags'];
        }
        //dd($Tag);
        $groupeTagTab['tags'] = [];
        $groupeTagObjet = $this->serializer->denormalize($groupeTagTab, "App\Entity\GroupeTag");
        //dd($groupeTagObjet);
        if(count($Tag)){
            foreach ($Tag as $tags){
                if(isset($tags['id'])){
                    $TagId=$tags['id'];
                    $tagsfind=$tagRepository->findOneBy(["id"=>$TagId]);
                    //dd($tagsfind);
                    if (!$tagsfind) {
                        return $this->json(["message" => "Ce Tag n'existe pas."],Response::HTTP_NOT_FOUND);
                    }
                    $groupeTagObjet->addTag($tagsfind);
                    //dd($groupeTagObjet);
                }
                else{
                    $newtag=$this->serializer->denormalize($tags, "App\Entity\Tag");
                    //dd($newtag);
                    $error = $this->validator->validate($newtag);
                    if(count($error))
                    {
                        return $this->json($error,Response::HTTP_BAD_REQUEST);
                    }
                    $this->manager->persist($newtag);
                    $groupeTagObjet->addTag($newtag);
                    //dd($groupeTagObjet);
                }
            }
        }
        else {
            return $this->json(["message" => "Le tag est obligatoire."],Response::HTTP_NOT_FOUND);
        }
        $this->manager->persist($groupeTagObjet);
        $this->manager->flush();
        return $this->json($groupeTagObjet,Response::HTTP_CREATED);
    }


    /**
     * @Route("api/admin/groupstags/{id}/tags", name="add_tagsGroup",methods={"GET"})
     */

    public function getTagingroupTag(GroupeTagRepository $groupeTagRepository, int $id){
        $groupTagObjet=$groupeTagRepository->find($id);
        //dd($groupTagObjet);
        $groupTagJson=$this->serializer->serialize($groupTagObjet,"json",["groups"=>["tagsInGrpeTag:read"]]);
        return new JsonResponse($groupTagJson, Response::HTTP_OK, [], true);

    }


    /**
     * @Route("api/admin/groupstags/{id}", name="put_tagsGroup",methods={"PUT"})
     */

    public function putTaginGroupTag(GroupeTagRepository $groupeTagRepository, TagRepository $tagRepository,Request $request, int $id){
        $groupe=$request->getContent();
        $GroupeTag= $this->serializer->decode($groupe,'json');
        //dd($GroupeTag);
        $groupeObj= $groupeTagRepository->find($id);
        //dd($groupeObj);

        if (isset($GroupeTag['tags'])){
            foreach ($GroupeTag['tags'] as $tag){
                if (isset($tag['libelle'])){
                    $Taglibelle=$tag['libelle'];
                    $requestTag= $tagRepository->findBy(["libelle"=>$Taglibelle]);
                      //dd($requestTag);
                    if (!$requestTag){
                        $newTag= new Tag();
                        $newTag->setLibelle($tag['libelle']);
                        //dd($newTag);
                        $this->manager->persist($newTag);
                        $groupeObj->addTag($newTag);
                       // dd($groupeObj);

                    }else{

                        $groupeObj->addTag($requestTag[0]);
                        //dd($groupeObj);
                    }
                }
                if (isset($tag['id'])){
                    $requestTagId= $tagRepository->find(
                        $tag['id']
                    );
                    //dd($requestTagId);
                    $groupeObj->removeTag($requestTagId);
                    $this->manager->persist($groupeObj);
                }
            }
        }
        //$this->error->error($groupeObj);

        $this->manager->persist($groupeObj);
        $this->manager->flush();
        return $this->json($groupeObj,Response::HTTP_CREATED);
        }


}



