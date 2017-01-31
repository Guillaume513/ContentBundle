<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\Article;
use ContentBundle\Entity\DocumentContent;
use ContentBundle\Entity\Page;
use ContentBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{id}/edit/{active}", defaults={"active" = 1}, name="admin_article_edit")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Template()
     */
    public function editAction(Request $request, $id, $active)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $this->getObject($id);
        $article->setUpdatedAt(new \DateTime());

        $doc = $em->getRepository('ContentBundle:DocumentContent')->findOneBy(['article' => $article, 'isLogo' => 1]);
        if (empty($doc)) {
            $doc = new DocumentContent();
            $doc->setIsLogo(1);
            $doc->setArticle($article);
            $doc->setEndPath('article/logo');
            $doc->setCreatedAt(new \DateTime());
            $em->persist($doc);
            $em->flush();
        } else {
            $doc->setEndPath('article/logo');
            $doc->setUpdatedAt(new \DateTime());
        }

        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_edit', ['id' => $article->getId(), 'active' => $active]),
            'method' => 'post',
        ]);

        if ($request->isMethod('POST')  ) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if (empty($article->getRefUrl()) && empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $article->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $i = 0;
                foreach ($article->getDocument() as $val) {
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

                $this->get('session')->getFlashBag()->set('success_article', 'L\'article a bien été modifié');

            } else {
                $this->get('session')->getFlashBag()->set('error_article', 'Echec de la modification de l\'article');
            }

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId(), 'active' => $active]);
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

        return $this->render('ContentBundle:Article:edit.html.twig', [
            'object' => $article,
            'form' => $form->createView(),
            'active' => $active,
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("page/{pageId}/article/new/", name="admin_article_new")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Template()
     * @return array
     */
    public function createAction(Request $request, $pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $article = new Article();

        $page = $em->getRepository('ContentBundle:Page')->findOneBy([
            'id' => (int)$pageId,
        ]);

        if (!($page instanceof Page)) {
            throw new NotFoundHttpException('The page with ID ' . $pageId . ' could not be found');
        }

        $article->setPage($page);
        $article->setCreatedAt(new \DateTime());

        $doc = new DocumentContent();
        $doc->setIsLogo(1);
        $doc->setEndPath('article/logo');
        $doc->setArticle($article);
        $doc->setCreatedAt(new \DateTime());
        $article->getDocument()->add($doc);;

        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_new', ['pageId' => $page->getId()]),
            'method' => 'POST',
        ]);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                $object = $form->getData();

                if (empty($form->get('refUrl')->getData())) {
                    $helper = $this->get('content.helper.fonction');
                    $article->setRefUrl($helper->clean_url($form->get('title')->getData()));
                }

                $em->persist($object);
                $em->flush();


                $this->get('session')->getFlashBag()->set('success_article', 'Succès');

            } else {
                $this->get('session')->getFlashBag()->set('error_article', 'Echec');

            }

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);
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
            'article' => $article,
            'form' => $form->createView(),
            'rubriques' => $rubriques,
            'pages' => $pages,
            'articles' => $articles,
        ];
    }

    /**
     * @Route("/article/{id}/delete", name="admin_article_delete")
     * Removes a Article
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $article = $this->getObject($id);

        $em = $this->getDoctrine()->getManager();

        $docs = $em->getRepository('ContentBundle:DocumentContent')->findBy(['article' => $id], []);
        foreach ($docs as $doc) {
            if ($doc->getIsLogo()) {
                $doc->setEndPath('article/logo');
            } else {
                $doc->setEndPath('article/'.$article->getId());
            }
            $em->remove($doc);
            $em->flush();
        }

        if (is_dir('../web/css/img/img-user/article/'.$article->getId())) {
            rmdir('../web/css/img/img-user/article/'.$article->getId());
        }

        $em->remove($article);
        $em->flush();


//        $this->get('session')->getFlashBag()->set('success', 'L\'article a bien été supprimée');

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

                $this->get('session')->getFlashBag()->set('success_article', 'L\'article a bien été modifiée');

                return true;
            } else {
                $this->get('session')->getFlashBag()->set('error_article', 'L\'article n\'a pas été modifiée, veuillez vérifier les champs du formulaire');

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
        return $this->createForm(ArticleType::class, $object);
    }

    /**
     * Returns Article object
     *
     * @param $id
     * @return Article|null
     * @throws NotFoundHttpException
     */
    protected function getObject($id)
    {
        $article = $this->getDoctrine()->getRepository('ContentBundle:Article')->findOneBy([
            'id' => (int)$id,
        ]);

        if (!($article instanceof Article)) {
            throw new NotFoundHttpException('The article with ID ' . $id . ' could not be found');
        }

        return $article;
    }
}