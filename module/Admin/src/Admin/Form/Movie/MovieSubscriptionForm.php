<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 27/10/2017
 * Time: 12:52
 */

namespace Admin\Form\Movie;

use Application\Entity\Movie\Movie as MovieEntity ;

class MovieSubscriptionForm extends Form
{
    public function __construct(MovieEntity $movie)
    {
        foreach ($movie->getSubscriptions() as $sub) {
            $this->add([
                'type' => SubscriptionFieldset::class,
                'name' => 'subscription['.$sub->getId().']'
            ]);
        }
    }
}