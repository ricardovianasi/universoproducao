<?php
namespace Admin\Controller;

use Application\Entity\Widget\Type;
use Application\Entity\Widget\Widget;

class WidgetController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
		$widgets = $this->search(Widget::class, ['site' => $this->getSiteIdFromUri()]);

		$this->getViewModel()->setVariables([
			'widgets' => $widgets,
			'site' => $this->getSiteIdFromUri()
		]);

		return $this->getViewModel();
	}

	public function widgetsAction()
    {
        return $this->getViewModel()->setVariables([
            'site' => $this->getSiteIdFromUri()
        ]);
    }

	public function createAction($data)
	{
	    return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/widget/create.phtml');

		return $result;
	}


	public function deleteAction($id)
	{
		$widget = $this->getRepository(Widget::class)->find($id);
		$this->getEntityManager()->remove($widget);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Video excluído com sucesso.');

		return $this->redirect()->toRoute('admin/tv', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function persist($data, $id = null)
	{
        $widgetType = $this->params()->fromQuery('type');
        if(!$widgetType) {
            return $this->redirect()->toRoute('admin/widget', ['action'=>'widgets', self::SITE_PARAM => $this->getSiteIdFromUri()]);
        }

		if($id) {
            $widget = $this->getRepository(Widget::class)->find($id);
		} else {
            $widget = new Widget();
            $widget->setAuthor($this->getAuthenticationService()->getIdentity());
            $widget->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
            $widget->setTitle($data['title']);
            $widget->setDescription($data['description']);
            $widget->setType($widgetType);

            $method = "widget_$widgetType";
            if(method_exists($this, $method)) {
                $this->$method($data[$widgetType], $widget);
            }

            $this->getEntityManager()->persist($widget);
            $this->getEntityManager()->flush();

            if($id) {
                $this->messages()->success("Widget atualizado com sucesso!");
            } else {
                $this->messages()->flashSuccess("Widget criado com sucesso!");
                return $this->redirect()->toRoute('admin/widget', [
                    'action' => 'update',
                    'id' => $widget->getId(),
                    'site' => $this->getSiteIdFromUri()
                ], ['query'=>['type'=>$widgetType]]);
            }

		}

        return $this->getViewModel()->setVariables([
            'site' => $this->getSiteIdFromUri(),
            'widget' => $widget,
            'widget_type' => $widgetType,
            'widget_name' => Type::get($widgetType),
            'widget_value' => !empty($widget->getValue()) ? json_decode($widget->getValue()) : []
        ]);
	}

	protected function widget_gallery($data, Widget &$widget)
    {
        $dataJson = json_encode($data);
        $widget->setValue($dataJson);

        return $widget;
    }

}