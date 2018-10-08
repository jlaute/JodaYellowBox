<?php declare(strict_types=1);
/**
 * © isento eCommerce solutions GmbH
 */

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@isento-ecommerce.de>
 * @copyright 2018 isento eCommerce solutions GmbH (http://www.isento-ecommerce.de)
 */
class BackendWidget implements SubscriberInterface
{
    /** @var string */
    protected $viewDir;

    /** @var string */
    protected $pluginDir;

    public function __construct(string $pluginDir, string $viewDir)
    {
        $this->pluginDir = $pluginDir;
        $this->viewDir = $viewDir;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_JodaTicketsWidget' => 'onGetBackendControllerPath',
            'Enlight_Controller_Action_PostDispatch_Backend_Index' => 'onPostDispatchBackendIndex',
        ];
    }

    public function onGetBackendControllerPath(\Enlight_Event_EventArgs $args)
    {
        return $this->pluginDir . '/Controllers/Backend/JodaTicketsWidget.php';
    }

    public function onPostDispatchBackendIndex(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Index $subject */
        $subject = $args->getSubject();

        $request = $args->getRequest();
        $view = $args->getSubject()->View();
        $view->addTemplateDir($this->viewDir);

        // if the controller action name equals "index" we have to extend the backend article application
        if ($request->getActionName() === 'index') {
            $view->extendsTemplate('backend/index/joda_tickets/app.js');
        }
    }
}
