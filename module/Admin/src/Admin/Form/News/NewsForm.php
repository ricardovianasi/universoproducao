<?php
namespace Admin\Form\News;

use Admin\Form\PostForm;
use Application\Entity\Site\Site;

class NewsForm extends PostForm
{
	public function __construct($em)
	{
		parent::__construct($em);

        $this->add([
            'type' => 'select',
            'name' => 'sites-enabled',
            'options' => [
                'label' => 'Selecione onde a notícia vai aparecer',
                'value_options' => $this->populateSite(),
                'empty_option' => 'Nenhum',
            ],
            'attributes' => [
                'id' => 'sites-enabled',
                'type'=>'select',
                'class' => 'form-control select2',
            ]
        ]);
        $this->getInputFilter()->add([
            'name' => 'sites-enabled',
            'required' => false
        ]);
	}

    public function populateSite()
    {
        $options = [];

        $sites = $this->getEntityManager()->getRepository(Site::class)->findEnabledSites();
        foreach($sites as $site) {
            $options[$site->getId()] = $site->getName();
        }

        return $options;
    }

}