<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Tags;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController  extends AbstractController
{

    /**
     * @Route("/api/article/save", name="api_article_save")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function save(Request $request, ValidatorInterface $validator, SerializerInterface $serializer) :JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $serializer->deserialize($request->getContent(), Article::class, 'json');

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return $this->json((string) $errors,Response::HTTP_BAD_REQUEST);
        }

        if (!empty($article->getId())) {
            $entityManager->merge($article);
        } else {
            $entityManager->persist($article);
        }
        $entityManager->flush();

        return $this->json($serializer->serialize($article, 'json'));
    }

    /**
     * @Route("/api/article/show/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Доктрина сама собирает все связаные теги
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (!$article) {
            return $this->json(["message" => "По этому id ничего не найдено"]);
        }

        return $this->json($article);
    }

    /**
     *
     * @Route("/api/article/all", name="api_article_all")
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $article = null;

        if (!empty($request->getContent())) {
            $requestArr = $request->toArray();
            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->getArticle($requestArr['tagsIds']);
        } else {
            $article = $this->getDoctrine()->getRepository(Article::class)->findAll();
        }


        return $this->json([
            "article" => $article,
            "tags" => $this->getDoctrine()
                ->getRepository(Tags::class)
                ->findAll()
            ]
        );

    }

    /**
     *
     * @Route("/api/article/remove/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function remove(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->getFilters()->disable('softdeleteable');

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (!$article) {
            return $this->json(["message" => "По этому id ничего не найдено"]);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->json(['status' => 'ok']);

    }

}