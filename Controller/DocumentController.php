<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\Article;
use ContentBundle\Entity\DocumentContent;
use ContentBundle\Entity\Page;
use ContentBundle\Entity\Rubrique;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentController extends Controller
{
    /**
     * @Route("/doc/{id}/delete/{active}", defaults={"active" = 1}, options={ "expose" = true}, name="admin_doc_delete")
     * Removes a DocumentContent
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     */
    public function deleteAction($id, $active)
    {
        if (!$this->getUser()->hasRole('ROLE_SUPER_ADMIN')) {
            throw new NotFoundHttpException('Le document n\'a pas été trouvée');
        }

        $em = $this->getDoctrine()->getManager();

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findOneBy([
            'id' => (int)$id,
        ]);

        if (!($doc instanceof DocumentContent)) {
            throw new NotFoundHttpException('The Document with ID ' . $id . ' could not be found');
        }

        if (!empty($doc->getArticle())) {
            $id_object = $doc->getArticle()->getId();
            $doc->setEndPath('article/logo');
            $path = 'admin_article_edit';
        } else if (!empty($doc->getPage())) {
            $id_object = $doc->getPage()->getId();
            $doc->setEndPath('page/logo');
            $path = 'admin_page_edit';
        } else if (!empty($doc->getRubrique())) {
            $id_object = $doc->getRubrique()->getId();
            $doc->setEndPath('rubrique/logo');
            $path = 'admin_rubrique_edit';
        } else if (!empty($doc->getNewsHelloresto())) {
            $doc->setEndPath('newsHelloresto');
            $id_object = $doc->getNewsHelloresto()->getId();
            $path = 'admin_news_helloresto_edit';
        } else if (!empty($doc->getUser())) {
            $id_object = $doc->getUser()->getId();
            $doc->setEndPath('user');
            $path = 'admin_user_edit';
        } else if (!empty($doc->getRecipe())) {
            $id_object = $doc->getRecipe()->getId();
            $doc->setEndPath('recipe');
            $path = 'edit_recipe';
        } else if (!empty($doc->getPublicity())) {
            $id_object = $doc->getPublicity()->getId();
            $doc->setEndPath('publicity');
            $path = 'announcer_publicity_edit';
        }

        $em->remove($doc);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success_delete_image', 'L\'image a bien été supprimé');

        return $this->redirectToRoute($path, ['id' => $id_object, 'active' => $active]);
    }

    /**
     * @Route("/rubrique/{id}/image", options={ "expose" = true}, name="ajax_snippet_image_rubrique_send")
     * @return array
     */
    public function ajaxSnippetImageRubriqueSendAction(Request $request, $id)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $document = new DocumentContent();
        $doc = $request->files->get('file');

        $rubrique = $em->getRepository('ContentBundle:Rubrique')->findOneBy(['id' => (int)$id]);

        if (!($rubrique instanceof Rubrique)) {
            throw new NotFoundHttpException('The Rubrique could not be found');
        }

        $document->setFile($doc);
        $document->setEndPath('rubrique/'.$rubrique->getId());
        $document->setPath($doc->getPathName());
        $document->setName($doc->getClientOriginalName());
        $document->setRubrique($rubrique);
        $document->setCreatedAt(new \DateTime());

        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/page/{id}/image", options={ "expose" = true}, name="ajax_snippet_image_page_send")
     * @return array
     */
    public function ajaxSnippetImagePageSendAction(Request $request, $id)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $document = new DocumentContent();
        $doc = $request->files->get('file');

        $page = $em->getRepository('ContentBundle:Page')->findOneBy(['id' => (int)$id]);

        if (!($page instanceof Page)) {
            throw new NotFoundHttpException('The Page could not be found');
        }

        $document->setFile($doc);
        $document->setEndPath('page/'.$page->getId());
        $document->setPath($doc->getPathName());
        $document->setName($doc->getClientOriginalName());
        $document->setPage($page);
        $document->setCreatedAt(new \DateTime());

        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/article/{id}/image", options={ "expose" = true}, name="ajax_snippet_image_article_send")
     * @return array
     */
    public function ajaxSnippetImageArticleSendAction(Request $request, $id)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $document = new DocumentContent();
        $doc = $request->files->get('file');

        $article = $em->getRepository('ContentBundle:Article')->findOneBy(['id' => (int)$id]);

        if (!($article instanceof Article)) {
            throw new NotFoundHttpException('The Article could not be found');
        }

        $document->setFile($doc);
        $document->setEndPath('article/'.$article->getId());
        $document->setPath($doc->getPathName());
        $document->setName($doc->getClientOriginalName());
        $document->setArticle($article);
        $document->setCreatedAt(new \DateTime());

        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }


    /**
     * @Route("rubrique/{id}/image/edit", options={ "expose" = true}, name="image_rubrique_edit_doc")
     * @return array
     */
    public function imageRubriqueEditDoc(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $rubrique = $em->getRepository('ContentBundle:Rubrique')->findOneBy(['id' => (int)$id]);

        if (!($rubrique instanceof Rubrique)) {
            throw new NotFoundHttpException('The Rubrique could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findBy([
            'rubrique' => $rubrique,
            'isLogo' => 0,
        ]);

        $response = new Response(json_encode($doc));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("page/{id}/image/edit", options={ "expose" = true}, name="image_page_edit_doc")
     * @return array
     */
    public function imagePageEditDoc(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('ContentBundle:Page')->findOneBy(['id' => (int)$id]);

        if (!($page instanceof Page)) {
            throw new NotFoundHttpException('The Page could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findBy([
            'page' => $page,
            'isLogo' => 0,
        ]);

        $response = new Response(json_encode($doc));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("article/{id}/image/edit", options={ "expose" = true}, name="image_article_edit_doc")
     * @return array
     */
    public function imageArticleEditDoc(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('ContentBundle:Article')->findOneBy(['id' => (int)$id]);

        if (!($article instanceof Article)) {
            throw new NotFoundHttpException('The Article could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findBy([
            'article' => $article,
            'isLogo' => 0,
        ]);

        $response = new Response(json_encode($doc));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("rubrique/{id_rubrique}/image/{name}/delete", options={ "expose" = true}, name="rubrique_image_delete")
     * Removes a DocumentContent
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     */
    public function deleteRubDocAction($id_rubrique, $name)
    {
        if (!$this->getUser()->hasRole('ROLE_USER')) {
            throw new NotFoundHttpException('Vous n\'avez pas l\'autorisation  d\'acceder a cette page');
        }

        $em = $this->getDoctrine()->getManager();

        $rubrique = $em->getRepository('ContentBundle:Rubrique')->findOneBy(['id' => (int)$id_rubrique]);

        if (!($rubrique instanceof Rubrique)) {
            throw new NotFoundHttpException('The Rubrique could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findOneBy([
            'rubrique' => $rubrique,
            'name' => $name,
            'isLogo' => 0,
        ]);

        $doc->setEndPath('rubrique/'.$rubrique->getId());

        if (!($doc instanceof DocumentContent)) {
            throw new NotFoundHttpException('The Document with ID could not be found');
        }

        $em->remove($doc);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success_delete_image', 'L\'image a bien été supprimé');

//        return $this->redirectToRoute($path, ['id' => $id_object, 'active' => $active]);
//        return $this->redirectToRoute('admin_rubrique_edit', ['id' => 1, 'active' => $active]);

        return new JsonResponse(array('status' => 'success'));
    }

    /**
     * @Route("page/{id_page}/image/{name}/delete", options={ "expose" = true}, name="page_image_delete")
     * Removes a DocumentContent
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     */
    public function deletePageDocAction($id_page, $name)
    {
        if (!$this->getUser()->hasRole('ROLE_USER')) {
            throw new NotFoundHttpException('Vous n\'avez pas l\'autorisation  d\'acceder a cette page');
        }

        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('ContentBundle:Page')->findOneBy(['id' => (int)$id_page]);

        if (!($page instanceof Page)) {
            throw new NotFoundHttpException('The Page could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findOneBy([
            'page' => $page,
            'name' => $name,
            'isLogo' => 0,
        ]);

        $doc->setEndPath('page/'.$page->getId());

        if (!($doc instanceof DocumentContent)) {
            throw new NotFoundHttpException('The Document with ID could not be found');
        }

        $em->remove($doc);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success_delete_image', 'L\'image a bien été supprimé');

        return new JsonResponse(array('status' => 'success'));
    }

    /**
     * @Route("article/{id_article}/image/{name}/delete", options={ "expose" = true}, name="article_image_delete")
     * Removes a DocumentContent
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws NotFoundHttpException
     */
    public function deleteArticleDocAction($id_article, $name)
    {
        if (!$this->getUser()->hasRole('ROLE_USER')) {
            throw new NotFoundHttpException('Vous n\'avez pas l\'autorisation  d\'acceder a cette page');
        }

        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('ContentBundle:Article')->findOneBy(['id' => (int)$id_article]);

        if (!($article instanceof Article)) {
            throw new NotFoundHttpException('The Article could not be found');
        }

        $doc = $this->getDoctrine()->getRepository('ContentBundle:DocumentContent')->findOneBy([
            'article' => $article,
            'name' => $name,
            'isLogo' => 0,
        ]);

        $doc->setEndPath('article/'.$article->getId());

        if (!($doc instanceof DocumentContent)) {
            throw new NotFoundHttpException('The Document with ID could not be found');
        }

        $em->remove($doc);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success_delete_image', 'L\'image a bien été supprimé');

        return new JsonResponse(array('status' => 'success'));
    }
}
