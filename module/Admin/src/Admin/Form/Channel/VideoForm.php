<?php
namespace Admin\Form\Channel;

use Admin\Form\EntityManagerTrait;
use Application\Entity\Channel\Category;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class VideoForm extends Form
{
    use EntityManagerTrait;

    public function __construct($em=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        parent::__construct();
        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título'
            ],
            'attributes' => [
                'placeholder' => 'Informe um Título'
            ]
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'description',
            'options' => [
                'label' => 'Descrição'
            ],
            'attributes' => [
                'rows' => 3,
                'placeholder' => 'Descrição'
            ]
        ]);

        $this->add([
            'name' => 'streaming_url',
            'options' => [
                'label' => 'Url do Vídeo'
            ],
            'attributes' => [
                'placeholder' => 'Informe o link do Youtube'
            ]
        ]);

        $this->add([
            'name' => 'date',
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'options' => [
                'label' => 'Data'
            ]
        ]);

        $this->add([
            'name' => 'cover',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'file'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'categories',
            'options' => [
                'label' => 'Selectione as categorias',
                'value_options' => $this->populateCategories(),
                'empty_option' => 'Selectione',
            ],
            'attributes' => [
                'id' => 'categories',
                'type'=>'select',
                'class' => 'form-control select2 select2-multiple',
                'multiple' => 'multiple'
            ]
        ]);

        $this->getInputFilter()->add([
            'name' => 'categories',
            'required' => false
        ]);

    }

    public function populateCategories()
    {
        $options = [];

        $categories = $this->getEntityManager()->getRepository(Category::class)->findAll();
        foreach ($categories as $cat) {
            //$options[$cat->getId()] = $cat->getName();
            $options[] = [
                'value' => $cat->getId(),
                'label' =>  $cat->getName()
            ];
        }

        return $options;
    }

    public function setData($data)
    {
        $categoriesValues = [];
        if(!empty($data['categories'])) {
            foreach ($data['categories'] as $cat) {
                if(is_object($cat)) {
                    $categoriesValues[] = $cat->getId();
                } elseif(is_numeric($cat)) {
                    $categoriesValues[] = $cat;
                }
            }
            unset($data['categories']);
        }
        $this->get('categories')->setValue($categoriesValues);

        return parent::setData($data);
    }
}