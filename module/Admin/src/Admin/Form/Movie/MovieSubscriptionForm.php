<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 27/10/2017
 * Time: 12:52
 */

namespace Admin\Form\Movie;

use Application\Entity\Movie\Movie as MovieEntity ;
use Zend\Form\Form;

class MovieSubscriptionForm extends Form
{
    public function __construct(MovieEntity $movie)
    {
        parent::__construct('movie-subscription-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'default-form-actions',
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'subscriptions',
            'options' => [
                'count' => $movie->getSubscriptions()->count()?$movie->getSubscriptions()->count():1,
                'target_element' => [
                    'type' => SubscriptionFieldset::class,
                ],
            ]
        ]);
    }

    public function setData($data)
    {
        $subscriptions = [];
        if(!empty($data['subscriptions'])) {
            foreach ($data['subscriptions'] as $sub) {
                $subscriptions[$sub->getId()] = [
                    'id'=> $sub->getId(),
                    'status' => $sub->getStatus(),
                    'event_name' => $sub->getEvent()->getShortName()
                ];
            }
        }
        $data['subscriptions'] = $subscriptions;

        return parent::setData($data);
    }
}