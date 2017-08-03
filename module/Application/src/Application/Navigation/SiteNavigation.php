<?php
namespace Application\Navigation;

use Application\Entity\Site\Menu\Menu;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Navigation\Exception\InvalidArgumentException;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteNavigation extends DefaultNavigationFactory
{
    /** @var Site Id */
    private $siteId;

    /** @var Route Name */
    private $routeName;

    private $language;

    /** @var EntityManager */
    private $em;

    public function __construct($siteId, $routeName, $language='pt')
    {
        if($siteId)
            $this->siteId = $siteId;

        if($routeName)
            $this->routeName = $routeName;

        $this->language = $language;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        $this->em = $container->get('doctrine.entitymanager.orm_default');
        return parent::createService($container, $name, $requestedName); // TODO: Change the autogenerated stub
    }

    protected function getPages(ContainerInterface $container)
	{
	    if(null === $this->pages) {
            $items = $this->findMenuItems();
            $pages = $this->prepareMenuPages($items);

            $configuration['navigation'][$this->getName()] = $pages;

            if (!isset($configuration['navigation'])) {
                throw new InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }

            $application = $container->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }

		return $this->pages;
	}

	/** @return EntityManager */
	protected function getEntityManager()
    {
        return $this->em;
    }

    protected function findMenuItems()
    {
        $qb = $this->getEntityManager()->getRepository(Menu::class)->createQueryBuilder('p');
        $qb->andWhere("p.site = :siteId")
            ->andWhere('p.language = :lang')->setParameter('lang', $this->language)
            ->setParameter('siteId', $this->siteId);

        $result = $qb->getQuery()->getOneOrNullResult();
        return $result ? $result->getItems() : [];
    }

    protected function prepareMenuPages($items, $isChildren=false)
    {
        $navigation = [];
        foreach ($items as $item) {
            $target = $item->getTargetBlank() ? 'target="_blanck"' : '';
            if(!empty($item->getLabel())) {
                $label = $item->getLabel();
            } elseif(!empty($item->getPost())) {
                $label = $item->getPost();
            }
//            $label = $item->getLabel() ? $item->getLabel() : $item->getPost()->getTitle();

            $label = empty($label) ? "Sem título" : "";

            $navigation[$item->getId()] = [
                'label' => $label
            ];

            if($item->isExternalLink()) {
                $navigation[$item->getId()]['uri'] = $item->getExternalUrl();
            } else {
                $navigation[$item->getId()]['route'] = $this->routeName;
                $navigation[$item->getId()]['params'] = [
                    'slug' => $item->getPost()->getRelativeUrl(),
                    'locale' => $this->language
                ];
            }

            if($item->hasChildren()) {
                $navigation[$item->getId()]['pages'] = $this->prepareMenuPages($item->getChildren(), true);
            }
        }

        return $navigation;
    }
}