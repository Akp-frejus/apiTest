<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * New TESTE
     * @Route("/api/new", name="api_new_utilisateur", methods={"POST"})
     */
    public function api_new_userAction(Request $request)
    {
        $form=0;
        $nom=$request->get('nom');
        if($nom<>null) $form++;
        $prenom=$request->get('prenom');
        if($prenom<>null) $form++;
        $message='Erreur du formulaire';
        if ($form==2){
            $verification=true;
            $em = $this->getDoctrine()->getManager();
            if ($verification==true){
                if ($nom==null){
                    $verification=false;
                    $message='Erreur renseigné votre nom;';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné votre nom",
                    ];
                }
            }

            if ($verification==true){
                if ($prenom==null){
                    $verification=false;
                    $message='Erreur renseigné votre prénom;';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné votre prénom",
                    ];
                }
            }
            if ($verification==true){
                $nomExiste =$em->getRepository(User::class)->findOneBy([
                    'lastname'=>$nom
                ]);
                //dump($prenom,$nom,$nomExiste);die();
                $prenomExiste =$em->getRepository(User::class)->findOneBy([
                    'firstname'=>$prenom
                ]);
                //dump($prenomExiste,$nomExiste);die();
                if ($nomExiste!=null and $prenomExiste!=null){
                    $idNom=$nomExiste->getId();
                    $idPrenom=$prenomExiste->getId();
                    if ($idNom==$idPrenom){
                        $verification=false;
                        $message='L\'utilisateur existe déjat';
                        $data[]=[
                            'statut'=>0,
                            'message'=>"L'utilisateur existe déjat",
                        ];
                    }
                }

            }
            if ($verification===true){

                $dateCreation=new \DateTime('now');
                $user=new User();
                $user->setCreationdate($dateCreation);
                $user->setFirstname($prenom);
                $user->setLastname($nom);
                $user->setUpdatedate($dateCreation);
                $em->persist($user);
                $em->flush();
//                $data[]=[
//                    'statut'=>1,
//                    'message'=>"Enregistrement effectuer avec succes",
//                ];
                $message='Enregistrement effectuer avec succes';
                $logger = $this->get('logger');
                $logger->info($message);
                $value=$userExiste =$em->getRepository(User::class)->findOneBy([
                    'firstname'=>$prenom,
                    'lastname'=>$nom
                ]);
                $data[]=[
                    'statut'=>1,
                    'message'=>"Utilisateur enregistrer",
                    "Id"=>$value->getId(),
                    "Nom"=>$value->getLastname(),
                    "Prenom"=>$value->getFirstname(),
                    "DateCreation"=>date_format($value->getCreationdate(),'Y-m-d H:i:s'),
                    "DateModification"=>date_format($value->getUpdatedate(),'Y-m-d H:i:s'),
                ];

            }
        }else{
            $message='Erreur du formulaire';
            $data[]=[
                'statut'=>0,
                'message'=>"Erreur formulaire ",
                //'form'=>$form
            ];
        }
        $logger = $this->get('logger');
        $logger->info($message);
        return new JsonResponse($data,200);
    }

    /**
     * New Modification
     * @Route("/api/modification", name="api_update_utilisateur", methods={"POST"})
     */
    public function api_update_userAction(Request $request)
    {
        $form=0;
        $nom=$request->get('nom');
        if($nom<>null) $form++;
        $prenom=$request->get('prenom');
        if($prenom<>null) $form++;
        $id=$request->get('PK');
        if($id<>null) $form++;
        $message='Erreur du formulaire';
        if ($form==3){
            $verification=true;
            $em = $this->getDoctrine()->getManager();
            if ($verification==true){
                $userExiste =$em->getRepository(User::class)->find($id);
                if ($userExiste==null){
                    $verification=false;
                    $message='Erreur modification';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur modification ",
                    ];
                }
            }
            if ($verification==true){
                if ($nom==null){
                    $verification=false;
                    $message='Erreur renseigné votre nom';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné votre nom",
                    ];
                }
            }

            if ($verification==true){
                if ($prenom==null){
                    $verification=false;
                    $message='Erreur renseigné votre prénom';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné votre prénom",
                    ];
                }
            }
            if ($verification==true){
                $nomExiste =$em->getRepository(User::class)->findOneBy([
                    'lastname'=>$nom
                ]);
                $prenomExiste =$em->getRepository(User::class)->findOneBy([
                    'firstname'=>$prenom
                ]);
                if ($nomExiste!=null and $prenomExiste!=null){
                    $idNom=$nomExiste->getId();
                    $idPrenom=$prenomExiste->getId();
                    if ($idNom==$idPrenom and $userExiste->getId()!=$idPrenom){
                        $verification=false;
                        $message='L\'utilisateur existe déjat';
                        $data[]=[
                            'statut'=>0,
                            'message'=>"Un l'utilisateur du genre  existe déjat",
                        ];
                    }
                }

            }
            if ($verification===true){

                $Updatedate=new \DateTime('now');
                $user=$userExiste;
                //$user->setCreationdate($dateCreation);
                $user->setUpdatedate($Updatedate);
                $user->setFirstname($prenom);
                $user->setLastname($nom);
                //$em->persist($user);
                $em->flush($user);
                $value=$userExiste =$em->getRepository(User::class)->find($id);
                $data[]=[
                    'statut'=>1,
                    'message'=>"Utilisateur modifier",
                    "Id"=>$value->getId(),
                    "Nom"=>$value->getLastname(),
                    "Prenom"=>$value->getFirstname(),
                    "DateCreation"=>date_format($value->getCreationdate(),'Y-m-d H:i:s'),
                    "DateModification"=>date_format($value->getUpdatedate(),'Y-m-d H:i:s'),
                ];
            }
        }else{
            $message='Erreur du formulaire';
            $data[]=[
                'statut'=>0,
                'message'=>"Erreur formulaire ",
                //'form'=>$form
            ];
        }
        $logger = $this->get('logger');
        $logger->info($message);
        return new JsonResponse($data,200);
    }

    /**
     * New Liste
     * @Route("/api/liste", name="api_liste_utilisateur", methods={"GET"})
     */
    public function api_liste_userAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeUser =$em->getRepository(User::class)->findAll();
        //dump($resultatat);die();
        if ($listeUser!=null){
            foreach ($listeUser as $value){
                    $data[]=[
                        "Id"=>$value->getId(),
                        "Nom"=>$value->getLastname(),
                        "Prenom"=>$value->getFirstname(),
                        "DateCreation"=>date_format($value->getCreationdate(),'Y-m-d H:i:s'),
                        "DateModification"=>date_format($value->getUpdatedate(),'Y-m-d H:i:s'),
                    ];


            }
            $logger = $this->get('logger');
            $logger->info('Liste utilisateur envoyer');
        }else{
            $logger = $this->get('logger');
            $logger->info('ucun utilisateur enregistré');
            $data[]=[
                'statut'=>0,
                'message'=>"Aucun utilisateur enregistré",
            ];
        }

        return new JsonResponse($data,200);
    }

    /**
     * New Supprimer
     * @Route("/api/delete", name="api_delete_utilisateur", methods={"GET"})
     */
    public function api_delete_userAction(Request $request)
    {
        $form=0;
        $id=$request->get('PK');
        if($id<>null) $form++;
        $message='Erreur formulaire de suppression';
        if ($form==1) {
            $verification = true;
            $em = $this->getDoctrine()->getManager();
            if ($verification == true) {
                $userExiste = $em->getRepository(User::class)->find($id);
                if ($userExiste == null) {
                    $verification = false;
                    $message='Erreur de suppression';
                    $data[] = [
                        'statut' => 0,
                        'message' => "Erreur de suppression ",
                    ];
                }
            }
            if ($verification===true){
                $em->remove($userExiste);
                $em->flush();
                $message='Utilisateur supprimer';
                return $this->redirectToRoute('api_liste_utilisateur');
            }
        }else{
            $message='Erreur formulaire de suppression';
            $data[]=[
                'statut'=>0,
                'message'=>"Erreur formulaire ",
            ];
        }
        $logger = $this->get('logger');
        $logger->info($message);
        return new JsonResponse($data,200);
    }
    /**
     * New Recherche
     * @Route("/api/recherche", name="api_recherche_utilisateur", methods={"GET"})
     */
    public function api_recherche_userAction(Request $request)
    {
        $form=0;
        $nom=$request->get('nom');
        if($nom<>null) $form++;
        $prenom=$request->get('prenom');
        if($prenom<>null) $form++;

        if ($form==2){
            $verification=true;
            $em = $this->getDoctrine()->getManager();
            if ($verification==true){
                if ($nom==null){
                    $verification=false;
                    $message='Erreur renseigné le nom rechercher';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné le prenom rechercher",
                    ];
                }
            }

            if ($verification==true){
                if ($prenom==null){
                    $verification=false;
                    $message='Erreur renseigné votre prénom';
                    $data[]=[
                        'statut'=>0,
                        'message'=>"Erreur renseigné votre prénom",
                    ];
                }
            }

            if ($verification==true){
                $nomExiste =$em->getRepository(User::class)->findOneBy([
                    'lastname'=>$nom
                ]);
                $prenomExiste =$em->getRepository(User::class)->findOneBy([
                    'firstname'=>$prenom
                ]);

                if ($nomExiste!=null and $prenomExiste!=null){
                    $idNom=$nomExiste->getId();
                    $idPrenom=$prenomExiste->getId();
                    if ($idNom!=$idPrenom){
                        $verification=false;
                        $message='L\'utilisateur rechercher n\'existe pas';
                        $data[]=[
                            'statut'=>0,
                            'message'=>"L'utilisateur rechercher n'existe pas",
                        ];
                    }
                }else{
                    if ($verification==true){
                        if ($nomExiste==null){
                            $verification=false;
                            $message='Le nom de l\'utilisateur rechercher n\'existe pas';
                            $data[]=[
                                'statut'=>0,
                                'message'=>"Le nom de l'utilisateur rechercher n'existe pas",
                            ];
                        }
                    }

                    if ($verification==true){
                        if ($prenomExiste==null){
                            $verification=false;
                            $message='Le prenom de l\'utilisateur rechercher n\'existe pas';
                            $data[]=[
                                'statut'=>0,
                                'message'=>"Le prenom de l'utilisateur rechercher n'existe pas",
                            ];
                        }
                    }

                }

            }
            //dump($nomExiste,$prenomExiste);die();
            if ($verification===true){

                $value=$nomExiste;
                $data[]=[
                    'statut'=>1,
                    'message'=>"Utilisateur trouver ",
                    "Id"=>$value->getId(),
                    "Nom"=>$value->getLastname(),
                    "Prenom"=>$value->getFirstname(),
                    "DateCreation"=>date_format($value->getCreationdate(),'Y-m-d H:i:s'),
                    "DateModification"=>date_format($value->getUpdatedate(),'Y-m-d H:i:s'),
                ];
                $message='Recherche effectuer';
            }
        }else{
            $message='Erreur du formulaire';
            $data[]=[
                'statut'=>0,
                'message'=>"Erreur formulaire ",
                //'form'=>$form
            ];
        }
        $logger = $this->get('logger');
        $logger->info($message);
        return new JsonResponse($data,200);
    }

}
