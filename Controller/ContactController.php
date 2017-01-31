<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\Contact;
use ContentBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ContactController extends Controller
{
    /**
     * @Route("/contact-site/", name="admin_contact_edit")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $this->getDoctrine()->getRepository('ContentBundle:Contact')->findOneBy([
            'id' => (int)1,
        ]);

        if (!($contact instanceof Contact)) {
            throw new NotFoundHttpException('La page contact n\'existe pas');
        }

        $form = $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('admin_contact_edit', ['id' => $contact->getId()]),
            'method' => 'post',
        ]);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $object = $form->getData();

                $em->persist($object);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success_contact', 'La fiche contact a bien été modifiée');

            } else {
                $this->get('session')->getFlashBag()->set('error_contact', 'Echec de la modification de la fiche contact');
            }

            return $this->redirectToRoute('admin_contact_edit', ['id' => $contact->getId()]);
        }

        return $this->render('ContentBundle:Contact:contact.html.twig', [
            'object' => $contact,
            'form' => $form->createView(),
        ]);
    }
}
