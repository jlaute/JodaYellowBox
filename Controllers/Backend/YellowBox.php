<?php

declare(strict_types=1);

use JodaYellowBox\Models\Release;

class Shopware_Controllers_Backend_YellowBox extends \Shopware_Controllers_Backend_Application
{
    protected $model = Release::class;
    protected $alias = 'release';
}
