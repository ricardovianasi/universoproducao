<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use MeuUniverso\Form\MovieForm;

class MovieRegistrationController extends AbstractMeuUniversoRegisterController
{
    public function indexAction()
    {
        return [];
    }

    public function novoAction()
    {
        return $this->persist();
    }

    public function editarAction()
    {
        return $this->persist();
    }

    protected function persist()
    {
        $form = new MovieForm($this->getEntityManager());

        return [
            'form' => $form
        ];
    }

    public function deleteAction()
    {
        return [];
    }
}