<?php
namespace MeuUniverso\Form;

use Admin\Form\Movie\MovieForm as AdminMovieForm;
use Application\Entity\Movie\Options;

class MovieForm extends AdminMovieForm
{
    public function __construct($entityManager)
    {
        parent::__construct($entityManager, Options::STATUS_ENABLED);
        $this->setAttributes([
            'class' => 'form-horizontal'
        ]);
    }
}