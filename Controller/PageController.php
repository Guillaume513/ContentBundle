<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\DocumentContent;
use ContentBundle\Entity\Page;
use ContentBundle\Entity\Rubrique;
use ContentBundle\Form\PageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{

    /**
     * @Route("/page/{id}/edit/{active}", defaults={"active" = 1}, name="admin_page_edit")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Template()
     */
    public function editAction(Request $request, $id, $active)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $this->getObject($id);
        $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $page->setUser($user);
        $page->setUpdatedAt(new \DateTime());

        $isActive = $page->getIsActive();

        $doc = $em->getRepository('ContentBundle:DocumentContent')->findOneBy(['page' => $page, 'isLogo' => 1]);
        if (empty($doc)) {
            $doc = new DocumentContent();
            $doc->setIsLogo(1);
            $doc->setPage($page);
            $doc->setEndPath('page/logo');
            $doc->setCreatedAt(new \DateTime());
            $em->persist($doc);
            $em->flush();
        } else {
            $doc->setEndPath('page/logo');
            $doc->setUpdatedAt(new \DateTime());
        }

        $form = $this->createForm(PageType::class, $page, [
            'action' => $this->generateUrl('admin_page_edit', ['id' => $page->getId(), 'active' => $active]),
            'method' => 'post',
        ]);

        if ($request->isMethod('POST') ) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if ($form->get('isActive')->getData() == 0) {
                    $this->ActivateContenu($page, 0);
                } else if ($form->get('isActive')->getData() == 1 && $isActive == 0) {
                    $this->ActivateContenu($page, 1);
                }

                if (empty($page->getRefUrl()) && empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $page->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $i = 0;
                foreach ($page->getDocument() as $val) {
                    if ($val->getIsLogo() == 1) {
                        $doc->setName($form->get('documentContent')[$i]['name']->getData());
                    }
                    $i++;
                }

                $em->persist($object);
                $em->flush();

                if ($form->get('tabulation')->getData() == 2) {
                    $active = 2;
                } else if ($form->get('tabulation')->getData() == 3) {
                    $active = 3;
                } else if ($form->get('tabulation')->getData() == 4) {
                    $active = 4;
                } else {
                    $active = 1;
                }

                $this->get('session')->getFlashBag()->set('success', 'La Page a bien été modifié');

            } else {
                $this->get('session')->getFlashBag()->set('error', 'Echec de la modification de la page');
            }
        }

        $rubriques = $em->getRepository('ContentBundle:Rubrique')->findBy(
            [],
            ['orderNbr' => 'ASC',
                'title' => 'ASC']
        );
        $pages = $em->getRepository('ContentBundle:Page')->findBy(
            [],
            ['orderNbr' => 'ASC',
                'title' => 'ASC']
        );
        $articles = $em->getRepository('ContentBundle:Article')->findby(
            [],
            ['orderNbr' => 'ASC']
        );

        return $this->render('ContentBundle:Page:edit.html.twig', [
            'object' => $page,
            'form' => $form->createView(),
            'active' => $active,
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("rubrique/{rubId}/page/new", name="admin_page_new")
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @return array
     */
    public function createAction(Request $request, $rubId)
    {
        $em = $this->getDoctrine()->getManager();

        $page = new Page();
        $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $page->setUser($user);
        $page->setCreatedAt(new \DateTime());

        $rubrique = $em->getRepository('ContentBundle:Rubrique')->findOneBy([
            'id' => (int)$rubId,
        ]);

        if (!($rubrique instanceof Rubrique)) {
            throw new NotFoundHttpException('The rubrique could not be found');
        }

        $page->setRubrique($rubrique);

        $doc = new DocumentContent();
        $doc->setIsLogo(1);
        $doc->setEndPath('page/logo');
        $doc->setPage($page);
        $doc->setCreatedAt(new \DateTime());
        $page->getDocument()->add($doc);


        $form = $this->createForm(PageType::class, $page, [
            'action' => $this->generateUrl('admin_page_new', ['rubId' => $rubrique->getId()]),
            'method' => 'POST',
        ]);


        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if (empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $page->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $em->persist($object);
                $em->flush();


                $this->get('session')->getFlashBag()->set('success', 'Succès');

            } else {
                $this->get('session')->getFlashBag()->set('error', 'Echec');

            }

            return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
        }

        $rubriques = $em->getRepository('ContentBundle:Rubrique')->findBy(
            [],
            ['orderNbr' => 'ASC',
                'title' => 'ASC']
        );
        $pages = $em->getRepository('ContentBundle:Page')->findBy(
            [],
            ['orderNbr' => 'ASC',
                'title' => 'ASC']
        );
        $articles = $em->getRepository('ContentBundle:Article')->findby(
            [],
            ['orderNbr' => 'ASC']
        );

        return [
            'page' => $page,
            'form' => $form->createView(),
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ];
    }

    /**
     * @Route("/page/{id}/delete", name="admin_page_delete")
     * Removes a Page
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $page = $this->getObject($id);

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('ContentBundle:Article')->findBy(['page' => $page]);

        if (!empty($article)) {
            $this->get('session')->getFlashBag()->set('error_delete', 'Veuillez supprimer les articles associées a la page avant de supprimer cette page');

            return $this->redirect($this->generateUrl('admin_rubrique_list'));
        }

        $docs = $em->getRepository('ContentBundle:DocumentContent')->findBy(['page' => $id], []);
        foreach ($docs as $doc) {
            if ($doc->getIsLogo()) {
                $doc->setEndPath('page/logo');
            } else {
                $doc->setEndPath('page/'.$page->getId());
            }
            $em->remove($doc);
            $em->flush();
        }

        if (is_dir('../web/css/img/img-user/page/'.$page->getId())) {
            rmdir('../web/css/img/img-user/page/'.$page->getId());
        }

        $em->remove($page);
        $em->flush();


//        $this->get('session')->getFlashBag()->set('success', 'La page a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_rubrique_list'));
    }


    /**
     * Process the form with the provided request
     *
     * @param $request
     * @param $form
     * @return bool
     */
    protected function processForm($request, $form) {
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $object = $form->getData();

                $em = $this->getDoctrine()->getManager();

                /* "Gere" l'objet! ne crée pas vraiment la requête dans la bdd (pas encore) */
                $em->persist($object);

                /* Doctrine regarde tous les objets qu'il gère pour savoir s'il ont besoin d'être persité dans la bdd */
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', 'La page a bien été modifiée');

                return true;
            } else {
                $this->get('session')->getFlashBag()->set('error', 'La page n\'a pas été modifiée, veuillez vérifier les champs du formulaire');

            }

        }

        return false;
    }

    /**
     * Returns form object
     *
     * @param $object
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($object)
    {
        return $this->createForm(PageType::class, $object);
    }

    /**
     * Returns Page object
     *
     * @param $id
     * @return Page|null
     */
    protected function getObject($id)
    {
        $page = $this->getDoctrine()->getRepository('ContentBundle:Page')->findOneBy([
            'id' => (int)$id,
        ]);

        if (!($page instanceof Page)) {
            throw new NotFoundHttpException('The page with ID ' . $id . ' could not be found');
        }

        return $page;
    }

    protected function ActivateContenu($page, $statut) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('ContentBundle:Article')->findBy(['page' => $page->getId()]);

        foreach ($article as $a) {
            $a->setIsActive($statut);
            $em->persist($a);
        }

        return true;
    }

}
