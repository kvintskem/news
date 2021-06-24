<?php


namespace App\Controller;


use App\Entity\Article;
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
     * @return Response
     */
    public function save(Request $request, ValidatorInterface $validator, SerializerInterface $serializer) :Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $serializer->deserialize($request->getContent(), Article::class, 'json');

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return $this->json((string) $errors);
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
     * @Route("/api/article/{id}")
     */
    public function getById($id)
    {

    }
}