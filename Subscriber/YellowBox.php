<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

class YellowBox implements SubscriberInterface
{
    const SNAP_COOKIE = 'ybsnap';

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $request = $args->getRequest();
        $view = $controller->View();

        $releaseManager = $controller->get('joda_yellow_box.services.release_manager');
        $currentRelease = $releaseManager->getCurrentRelease();
        $snapCookie = $request->getCookie(self::SNAP_COOKIE);

        $view->assign('currentRelease', $currentRelease);
        $view->assign('snapPosition', $snapCookie);
    }
}
