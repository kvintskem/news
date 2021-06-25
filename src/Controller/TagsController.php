<?php

namespace App\Controller;

use App\Entity\Tags;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagsController extends AbstractController
{
    /**
     * @Route("/api/tags/save", name="tags")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function save(Request $request, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tags = $serializer->deserialize($request->getContent(), Tags::class, 'json');

        $errors = $validator->validate($tags);
        if (count($errors) > 0) {
            return $this->json((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        if (!empty($tags->getId())) {
            $entityManager->merge($tags);
        } else {
            $entityManager->persist($tags);
        }
        $entityManager->flush();

        return $this->json($serializer->serialize($tags, 'json'));
    }
}
