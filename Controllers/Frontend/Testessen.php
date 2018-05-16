<?php

declare(strict_types=1);

use Doctrine\DBAL\DBALException;

class Shopware_Controllers_Frontend_Testessen extends \Enlight_Controller_Action
{
    public function indexAction()
    {
        $this->forward('reset');
    }

    public function resetAction()
    {
        $fixturesLoader = $this->get('joda_yellow_box.data_fixtures.fixtures_loader');
        $fixturesLoader->setDeleteAll(true);

        try {
            $fixturesLoader->run();
        } catch (DBALException $ex) {
            die('Fixtures could not be loaded');
        }

        $this->view->loadTemplate('frontend/yellow_box/testessen.tpl');
    }
}
