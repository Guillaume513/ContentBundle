<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\DocumentContent;
use ContentBundle\Entity\Rubrique;
use ContentBundle\Form\RubriqueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rubrique controller.
 *
 * @Route("/rubrique")
 */
class RubriqueController extends Controller
{
    const REPO_NAME = 'ContentBundle:Rubrique';

    const TYPE_NAME = 'RubriqueType';

    const CREATE_SUCCESS_MESSAGE = 'La rubrique a bien été créé';
    const CREATE_ERROR_MESSAGE = 'La rubrique n\'a pas pu être créé, veuillez vérifier votre formulaire';
    const EDIT_SUCCESS_MESSAGE = 'La rubrique a bien été modifié';
    const EDIT_ERROR_MESSAGE = 'La rubrique n\'a pas pu être modifié, veuillez vérifier votre formulaire';
    const DELETE_SUCCESS_MESSAGE = 'La rubrique a bien été suprimé';
    const DELETE_ERROR_MESSAGE = 'La rubrique n\'a pas été supprimé';


    const LIST_ROUTE = 'admin_rubrique_list';
    const EDIT_ROUTE = 'admin_rubrique_edit';

    /**
     * @Route("/list", name="admin_rubrique_list")
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @return array
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rubriques = $em->getRepository(self::REPO_NAME)->findBy(
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
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ];
    }

    /**
     * @Route("/{id}/edit/{active}", defaults={"active" = 1}, name="admin_rubrique_edit")
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @return array
     */
    public function editAction(Request $request, $id, $active)
    {
        $em = $this->getDoctrine()->getManager();

        $rubrique = $this->getObject($id);
        $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $rubrique->setUser($user);
        $rubrique->setUpdatedAt(new \DateTime());

        $isActive = $rubrique->getIsActive();

        $doc = $em->getRepository('ContentBundle:DocumentContent')->findOneBy(['rubrique' => $rubrique, 'isLogo' => 1]);
        if (empty($doc)) {
            $doc = new DocumentContent();
            $doc->setIsLogo(1);
            $doc->setRubrique($rubrique);
            $doc->setEndPath('rubrique/logo');
            $doc->setCreatedAt(new \DateTime());
            $em->persist($doc);
            $em->flush();
        } else {
            $doc->setEndPath('rubrique/logo');
            $doc->setUpdatedAt(new \DateTime());
        }

        $form = $this->createForm(RubriqueType::class, $rubrique, [
            'action' => $this->generateUrl('admin_rubrique_edit', ['id' => $rubrique->getId(), 'active' => $active]),
            'method' => 'post',
        ]);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if ($form->get('isActive')->getData() == 0) {
                    $this->ActivateContenu($rubrique, 0);
                } else if ($form->get('isActive')->getData() == 1 && $isActive == 0) {
                    $this->ActivateContenu($rubrique, 1);
                }

                if (empty($rubrique->getRefUrl()) && empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $rubrique->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $i = 0;
                foreach ($rubrique->getDocument() as $val) {
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

                $this->get('session')->getFlashBag()->set('success', 'La rubrique a bien été modifié');

            } else {
                $this->get('session')->getFlashBag()->set('error', 'Echec de la modification de la rubrique');
            }

            return $this->redirectToRoute(self::EDIT_ROUTE, ['id' => $rubrique->getId(), 'active' => $active]);
        }

        $rubriques = $em->getRepository(self::REPO_NAME)->findBy(
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
            'object' => $rubrique,
            'form' => $form->createView(),
            'active' => $active,
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ];
    }

    /**
     * @Route("/new", name="admin_rubrique_new")
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @return array
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $rubrique = new Rubrique();
        $rubrique->setUser($this->getUser());
        $rubrique->setCreatedAt(new \DateTime());

        $doc = new DocumentContent();
        $doc->setIsLogo(1);
        $doc->setEndPath('rubrique/logo');
        $doc->setRubrique($rubrique);
        $doc->setCreatedAt(new \DateTime());
        $rubrique->getDocument()->add($doc);

        $form = $this->createForm(RubriqueType::class, $rubrique, [
            'action' => $this->generateUrl('admin_rubrique_new'),
            'method' => 'POST',
        ]);


        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if (empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $rubrique->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $em->persist($object);
                $em->flush();


                $this->get('session')->getFlashBag()->set('success', 'La rubrique a bien été crée');

            } else {
                $this->get('session')->getFlashBag()->set('error', 'Echec de la création de la rubrique');

            }

            return $this->redirect($this->generateUrl(self::EDIT_ROUTE, ['id' => $rubrique->getId()]));
        }

        $rubriques = $em->getRepository(self::REPO_NAME)->findBy(
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
            'form' => $form->createView(),
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ];
    }

    /**
     * Removes a rubrique
     *
     * @Route("/rubrique/{id}/delete", name="admin_rubrique_delete")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $rubrique = $this->getObject($id);

        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('ContentBundle:Page')->findBy(['rubrique' => $rubrique]);
        if (!empty($page)) {
            $this->get('session')->getFlashBag()->set('error_delete', 'Veuillez supprimer les pages associées a la rubrique avant de supprimer cette rubrique');

            return $this->redirect($this->generateUrl('admin_rubrique_list'));
        }

        $docs = $em->getRepository('ContentBundle:DocumentContent')->findBy(['rubrique' => $rubrique], []);
        foreach ($docs as $doc) {
            if ($doc->getIsLogo()) {
                $doc->setEndPath('rubrique/logo');
            } else {
                $doc->setEndPath('rubrique/'.$rubrique->getId());
            }
            $em->remove($doc);
            $em->flush();
        }

        if (is_dir('../web/css/img/img-user/rubrique/'.$rubrique->getId())) {
            rmdir('../web/css/img/img-user/rubrique/'.$rubrique->getId());
        }

        $em->remove($rubrique);
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
    protected function processForm($request, $form, $success = '', $error = '') {
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $object = $form->getData();

                $em = $this->getDoctrine()->getManager();

                $em->persist($object);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', $success);

                return true;
            } else {
                $this->get('session')->getFlashBag()->set('error', $error);

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
        return $this->createForm(RubriqueType::class, $object);
    }


    /**
     * Returns Rubrique object
     *
     * @param $id
     * @return Rubrique|null
     */
    protected function getObject($id)
    {
        $page = $this->getDoctrine()->getRepository(self::REPO_NAME)->findOneBy([
            'id' => $id,
        ]);

        if (!($page instanceof Rubrique)) {
            throw new NotFoundHttpException('The object with ID ' . $id . ' could not be found');
        }

        return $page;
    }

    protected function ActivateContenu($rubrique, $statut) {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('ContentBundle:Page')->findBy(['rubrique' => $rubrique->getId()]);

        foreach ($page as $p) {
            $p->setIsActive($statut);
            $em->persist($p);

            $article = $em->getRepository('ContentBundle:Article')->findBy(['page' => $p->getId()]);
            foreach ($article as $a) {
                $a->setIsActive($statut);
                $em->persist($a);
            }
        }

        return true;
    }
}
