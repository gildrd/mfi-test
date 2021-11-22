<?php

namespace App\Controller;

use App\Entity\Peak;
use App\Repository\PeakRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    private EntityManagerInterface $em;

    private PeakRepository $peakRepository;

    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, PeakRepository $peakRepository,
                                SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->peakRepository = $peakRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/peak/add", name="api.peak.add", methods={"POST"}, requirements={"id"="\d+"})
     *
     * @OA\RequestBody(
     *     request="data",
     *     description="Corps de la requête",
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="lat", type="string", example="42.519", description="Latitude (en décimal)"),
     *         @OA\Property(property="lon", type="string", example="2.457", description="Longitude (en décimal)"),
     *         @OA\Property(property="altitude", type="string", example="2784", description="Altitude (en mètres)"),
     *         @OA\Property(property="name", type="string", example="Pic du Canigou", description="Nom")
     *     )
     * )
     *
     * @OA\Tag(name="peak")
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        $peak = new Peak();
        $peak
            ->setLat($datas['lat'])
            ->setLon($datas['lon'])
            ->setAltitude($datas['altitude'])
            ->setName(($datas['name']));

        $this->em->persist($peak);
        $this->em->flush();

        return $this->json(
            json_decode($this->serializer->serialize($peak, 'json', [
                'groups' => ['api']
            ]), true)
        );
    }

    /**
     * @Route("/api/peak/", name="api.peak.getAll", methods={"GET"})
     *
     * @OA\Tag(name="peak")
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $peaks = $this->peakRepository->findAll();

        return $this->json(
            json_decode($this->serializer->serialize($peaks, 'json', [
                'groups' => ['api']
            ]), true)
        );
    }

    /**
     * @Route("/api/peak/{id}", name="api.peak.get", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Tag(name="peak")
     *
     * @param Peak $peak
     * @return JsonResponse
     */
    public function getById(Peak $peak): JsonResponse
    {
        return $this->json(
            json_decode($this->serializer->serialize($peak, 'json', [
                'groups' => ['api']
            ]), true)
        );
    }

    /**
     * @Route("/api/peak/update/{id}", name="api.peak.update", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\RequestBody(
     *     request="data",
     *     description="Corps de la requête",
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="lat", type="string", example="42.519", description="Latitude (en décimal)"),
     *         @OA\Property(property="lon", type="string", example="2.457", description="Longitude (en décimal)"),
     *         @OA\Property(property="altitude", type="string", example="2784", description="Altitude (en mètres)"),
     *         @OA\Property(property="name", type="string", example="Pic du Canigou", description="Nom")
     *     )
     * )
     *
     * @OA\Tag(name="peak")
     *
     * @param Peak $peak
     * @return JsonResponse
     */
    public function update(Request $request, Peak $peak): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        $peak
            ->setLat($datas['lat'])
            ->setLon($datas['lon'])
            ->setAltitude($datas['altitude'])
            ->setName(($datas['name']));

        $this->em->persist($peak);
        $this->em->flush();

        return $this->json(
            json_decode($this->serializer->serialize($peak, 'json', [
                'groups' => ['api']
            ]), true)
        );
    }

    /**
     * @Route("/api/peak/delete/{id}", name="api.peak.delete", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @OA\Tag(name="peak")
     *
     * @param Peak $peak
     * @return JsonResponse
     */
    public function delete(Peak $peak): JsonResponse
    {
        $this->em->remove($peak);
        $this->em->flush();

        return new JsonResponse('Object '.$peak->getId().' Successfully deleted');
    }

    /**
     * @Route("/api/peak/area/", name="api.peak.area", methods={"GET"})
     *
     * @OA\Parameter(
     *     name="nw",
     *     in="query",
     *     description="North / West coordinates",
     *     example="45.005 1.420"
     * )
     * @OA\Parameter(
     *     name="ne",
     *     in="query",
     *     description="North / East coordinates",
     *     example="45.005 3.420"
     * )
     * @OA\Parameter(
     *     name="se",
     *     in="query",
     *     description="South / East coordinates",
     *     example="40.005 3.420"
     * )
     * @OA\Parameter(
     *     name="sw",
     *     in="query",
     *     description="South / West coordinates",
     *     example="40.005 1.420"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInBoundingBox(Request $request): JsonResponse
    {
//        dd($request->query->all());
        $datas = $request->query->all();

        $peaks = $this->peakRepository->findInBoundingBox($datas);

        return $this->json(
            json_decode($this->serializer->serialize($peaks, 'json', [
                'groups' => ['api']
            ]), true)
        );
    }
}