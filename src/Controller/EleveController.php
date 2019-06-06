<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Eleve;
use App\Entity\Note;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class EleveController extends AbstractController
{
    public function liste()
    {
        $eleveRepository = $this->getDoctrine()->getRepository(Eleve::class);

        return $this->render('eleve/liste.html.twig', [
            'eleves' => $eleveRepository->findAll(),
        ]);
    }

    public function new(Request $request)
    {
        $eleve = new Eleve();
        $form = $this->getEleveForm($eleve);
        $form->handleRequest($request);
        $success = $this->handleEleveForm($form, $eleve);
        if($success)
        {
            return $this->redirectToRoute('eleve_view', ['id' => $eleve->getId()]);
        }
        return $this->render('eleve/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit($id, Request $request)
    {
        $eleveRepository = $this->getDoctrine()->getRepository(Eleve::class);
        $eleve = $eleveRepository->find($id);
        if(empty($eleve)){
            $this->addFlash('warning', 'Elève introuvable');
            return $this->redirectToRoute('index');
        }
        $form = $this->getEleveForm($eleve);
        $form->handleRequest($request);
        $success = $this->handleEleveForm($form, $eleve);
        if($success)
        {
            return $this->redirectToRoute('eleve_view', ['id' => $eleve->getId()]);
        }
        return $this->render('eleve/edit.html.twig', [
            'eleve'=> $eleve,
            'form' => $form->createView(),
        ]);
    }

    public function view($id, Request $request)
    {
        $eleveRepository = $this->getDoctrine()->getRepository(Eleve::class);
        $eleve = $eleveRepository->find($id);
        if(empty($eleve)){
            $this->addFlash('warning', 'Elève introuvable');
            return $this->redirectToRoute('index');
        }
        $notes = $eleve->getNotes();
        $note = new Note();
        $note->setEleve($eleve);
        $form = $this->createFormBuilder($note)
            ->add('matiere', TextType::class)
            ->add('evaluation', IntegerType::class)
            ->add('enregistrer', SubmitType::class, ['label' => 'Ajouter une note'])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();
            $this->addFlash('success', 'La note a été ajoutée.');
            return $this->redirectToRoute('eleve_view', ['id' => $id]);
        }   
        return $this->render('eleve/view.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
            'notes' => $notes,
        ]);
    }

    public function delete($id, Request $request)
    {
        $eleveRepository = $this->getDoctrine()->getRepository(Eleve::class);
        $eleve = $eleveRepository->find($id);
        if(empty($eleve)){
            $this->addFlash('warning', 'Elève introuvable');
            return $this->redirectToRoute('index');
        }
        $form = $this->createFormBuilder()
            ->add('annuler', SubmitType::class)
            ->add('confirmer', SubmitType::class)
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('confirmer')->isClicked()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($eleve);
                $entityManager->flush();
                $this->addFlash('success', 'Enregistrement supprimé.');
            }
            return $this->redirectToRoute('index');
        }
        return $this->render('eleve/delete.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }
    private function getEleveForm(Eleve $eleve)
    {
        return $this->createFormBuilder($eleve)
        ->add('prenom', TextType::class)
        ->add('nom', TextType::class)
        ->add('dateNaissance', DateType::class, [
            'label' => 'Date de naissance',
            'widget' => 'choice', 
            'format' => 'd/M/y', 
            'years' => range(date('Y')-50, date('Y')),
            ])
        ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer l\'élève'])
        ->getForm();
    }

    private function handleEleveForm($form, Eleve $eleve){
        if ($form->isSubmitted() && $form->isValid()) {
            $eleve = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eleve);
            $entityManager->flush();
            $this->addFlash('success', 'Elève enregistré.');
            return true;
        } else if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('warning', 'Saisie incorrecte, veuillez vérifier les informations');
            return false;
        }
    }

}