<?php
/**
 * @author Agiel K. Saputra <agielkurniawans@gmail.com>
 * @copyright Copyright (c) 2022 Agiel K. Saputra
 */

namespace diecoding\yii2\wablas\tests;

use diecoding\yii2\wablas\Wablas;
use yii\base\BaseObject;

/**
 * Wrapper for Wablas Custom version
 */
class CustomVersion extends BaseObject
{
    /**
     * @var Wablas
     */
    public $wablas;

    /**
     * @return Wablas
     */
    public function sendMessage(array $data): Wablas
    {
        $this->wablas->setRequest($this->wablas->client->post(['custom/send-message'])->setData($data));
        return $this->wablas;
    }
}
