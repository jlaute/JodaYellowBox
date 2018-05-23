<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

class YellowBox implements SubscriberInterface
{
    const SNAP_COOKIE = 'ybsnap';
    const MINIMIZE_COOKIE = 'ybmin';

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_YellowBox' => 'onWidgetPostDispatch',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onWidgetPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $request = $args->getRequest();
        $view = $controller->View();

        $releaseManager = $controller->get('joda_yellow_box.services.release_manager');
        $currentRelease = $releaseManager->getCurrentRelease();
        $snapCookie = $request->getCookie(self::SNAP_COOKIE);
        $minimizeCookie = $request->getCookie(self::MINIMIZE_COOKIE);

        $view->assign('currentRelease', $currentRelease);
        $view->assign('snapPosition', $snapCookie);
        $view->assign('isMinimized', $minimizeCookie);
    }
}
