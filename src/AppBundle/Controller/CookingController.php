<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\CookingJob;

/**
 * @Route("/cooking")
 */
class CookingController extends Controller
{
    /**
     * @Route("/start")
     */
    public function startAction(Request $request)
    {
        $cookingJobRepository = $this->getDoctrine()->getRepository('AppBundle:CookingJob');

        if ($cookingJobRepository->findOneByIsCooking(true)) {
            return new JsonResponse(
                [
                    'error' =>  [
                        'message'   => 'Already being cooked.',
                    ],
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $cookingTemperature = (int)$request->request->get('cookingTemperature');
        $cookingTime = $request->request->get('cookingTime');
        $description = $request->request->get('description');

        if (!$cookingTemperature || !$cookingTime) {
            return new JsonResponse(
                [
                    'error' =>  [
                        'message'   =>  'Wrong number of arguments',
                    ]
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $cookingTime = $this->convertTimeToMinute($cookingTime);

        $cookingJob = new CookingJob();

        $cookingJob->setIsCooking(true);
        $cookingJob->setCookingTemperature($cookingTemperature);
        $cookingJob->setCookingTime($cookingTime);
        $cookingJob->setCookingStartTime(new \DateTime('now'));
        $cookingJob->setCookingEndTime(new \DateTime(sprintf('+%d min', $cookingTime)));
        $cookingJob->setDescription($description);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($cookingJob);
        $manager->flush();

        // TODO:cookingコマンドをたたいて、バックグラウンドで処理させる
        $command = sprintf(
            'nohup %s/../bin/console %s',
            $this->container->getParameter('kernel.root_dir'),
            'app:cooking > /dev/null 2>&1 &'
        );
        //exec(escapeshellcmd($command));

        return new JsonResponse($this->getCookingStatus($cookingJob));
    }

    /**
     * @Route("/stop")
     */
    public function stopAction()
    {
        $cookingJob = $this->getDoctrine()->getRepository('AppBundle:CookingJob')->findOneByIsCooking(true);

        if (!$cookingJob) {
            return new JsonResponse(
                [
                    'message' => 'Nothing CookingJob.',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $cookingJob->setIsCooking(false);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return new JsonResponse($this->getCookingStatus($cookingJob));
    }

    /**
     * @Route("/status")
     */
    public function statusAction()
    {
        $cookingJob = $this->getDoctrine()->getRepository('AppBundle:CookingJob')->findOneByIsCooking(true);

        if (!$cookingJob) {
            return new JsonResponse(
                [
                    'message' => 'The cooking does not begin yet.',
                ]
            );
        }

        return new JsonResponse($this->getCookingStatus($cookingJob));
    }

    /**
     * @Route("/changeTemperature")
     */
    public function changeTemperatureAction(Request $request)
    {
        $cookingJob = $this->getDoctrine()->getRepository('AppBundle:CookingJob')->findOneByIsCooking(true);

        if (!$cookingJob) {
            return new JsonResponse(
                [
                    'message' => 'Nothing CookingJob.',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $cookingTemperature = (int)$request->request->get('cookingTemperature');

        if (!$cookingTemperature) {
            return new JsonResponse(
                [
                    'error' =>  [
                        'message'   =>  'Wrong number of arguments',
                    ]
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $cookingJob->setCookingTemperature($cookingTemperature);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return new JsonResponse($this->getCookingStatus($cookingJob));
    }

    /*
     * `H:i:s`から`minute`に変換
     *
     * @param string
     * @return int
     */
    protected function convertTimeToMinute($time)
    {
        $time = explode(':', $time);
        return (int)$time[0] * 60 + (int)$time[1];
    }

    /*
     * `minute`から`H:i:s`に変換
     *
     * @param int
     * @return string
     */

    protected function convertMinuteToTime($minute)
    {
        $hour  = floor($minute / 60);
        $minute = $minute % 60;

        return sprintf('%02d:%02d:00', $hour, $minute);
    }

    protected function getCookingStatus(CookingJob $cookingJob)
    {
        return [
            'isCooking'             => $cookingJob->getIsCooking(),
            'CookingTime'           => $this->convertMinuteToTime($cookingJob->getCookingTime()),
            'CookingTemperature'    => $cookingJob->getCookingTemperature(),
            'CurrentTemperature'    => $this->container->get('app.gpio')->getCurrentTemperature(),
            'CookingStartTime'      => $cookingJob->getCookingStartTime()->format('Y-m-d H:i:s'),
            'CookingEndTime'        => $cookingJob->getCookingEndTime()->format('Y-m-d H:i:s'),
            'description'           => $cookingJob->getDescription(),
        ];
    }
}
