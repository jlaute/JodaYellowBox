<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Objects\Redmine;

use JodaYellowBox\Components\API\Objects\AbstractAPIObject;

class Issue extends AbstractAPIObject
{
    public function all(array $params = [])
    {
        return $this->retrieveAll('/issues.json', $params);
    }

    public function show(int $id)
    {
        return $this->get('/issues/' . urlencode($id) . '.json');
    }
}
