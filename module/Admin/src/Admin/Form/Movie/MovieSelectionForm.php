<?php
namespace Admin\Form\Movie;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Movie\Category;
use Application\Entity\Registration\Status;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class MovieSelectionForm extends Form
{
    protected $entityManager;

    public function __construct($entityManager)
    {

        $this->entityManager = $entityManager;

        parent::__construct('movie-selection-search');
        $this->setAttributes([
            'method' => 'POST',
            'id' => 'movie-selection-search'
        ]);

    }
}
