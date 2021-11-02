<?php

namespace App\Controller;

use App\Domain\Compute\OddsComputer;
use App\Domain\Factory\EmpireConfigurationFactory;
use App\Domain\Factory\MilleniumFalconConfigurationFactory;
use App\Domain\Model\UniverseDbMock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class ComputeController extends AbstractController
{
    /**
     * @Route("/compute", name="compute")
     */
    public function compute(
        Request $request,
        MilleniumFalconConfigurationFactory $milleniumFalconConfigurationFactory,
        EmpireConfigurationFactory $empireConfigurationFactory,
        OddsComputer $oddsComputer,
        DecoderInterface $decoder
    ): JsonResponse
    {
        if (!$request->files->get('file')) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $configuration = file_get_contents($request->files->get('file'));
        $decodedConfiguration = $decoder->decode($configuration, 'json');

        $result = $oddsComputer->compute(
            $milleniumFalconConfigurationFactory->createExample1(),
            $empireConfigurationFactory->create($decodedConfiguration),
            UniverseDbMock::getMock()
        );

        return new JsonResponse($result);
    }
}