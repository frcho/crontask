<?php

namespace Frcho\Bundle\CrontaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Frcho\Bundle\CrontaskBundle\Entity AS Entity;
use Frcho\Bundle\CrontaskBundle\Form\CronTaskType;
use Frcho\Bundle\CrontaskBundle\Util\Util;

/**
 * Cron Task Controller
 *
 */
class CronTaskController extends Controller {

    const accessDeniedMessage = 'accessDeniedMessage';
    const globalAccessDenied = 'global.access_denied';
    const masterUnlockBackendHomepage = 'master_unlock_backend_homepage';
    const FrchoCrontaskBundleCronTask = 'FrchoCrontaskBundle:CronTask';
    const session = 'session';
    const translator = 'translator';

    public function cronTaskAction(Request $request) {


        $em = $this->getDoctrine()->getManager();

        $cronTasks = $em->getRepository(self::FrchoCrontaskBundleCronTask)->findAll();
        $forms = array();
        $i = 0;
        foreach ($cronTasks as $cronTask) {

            $form = $this->container
                    ->get('form.factory')
                    ->createNamedBuilder(CronTaskType::FORM_PREFIX . $i, CronTaskType::class, $cronTask)
                    ->getForm()
                    ->createView();
            array_push($forms, $form);
            $i++;
        }

        return $this->render('FrchoCrontaskBundle:Default:cronTask.html.twig', array(
                    'forms' => $forms,
        ));
    }

    public function cronTaskUpdateAction(Request $request) {


        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {

            $parameters = $request->request->getIterator()->getArrayCopy();

            $i = 0;
            foreach ($parameters as $form) {
                $prev = $em->getRepository(self::FrchoCrontaskBundleCronTask)->findAll();

                $interval = Util::convertDaysHoursMinutes($form['interval'], $form['range']);
                if (isset($form['statusTask'])) {
                    $statusTask = boolval((int) $form['statusTask']);
                } else {
                    $statusTask = boolval((int) Entity\CronTask::DISABLED);
                }
                $prev[$i]->setInterval($interval);
                $prev[$i]->setStatusTask($statusTask);

                $em->persist($prev[$i]);
                $em->flush();

                $i++;
            }
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }
    }

}
