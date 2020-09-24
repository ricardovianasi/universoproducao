<?php
namespace Admin\Controller;

use Admin\Form\PostForm;
use Application\Entity\AdminUser\Profile;
use Application\Entity\City;
use Application\Entity\Event\Event;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostMeta;
use Application\Entity\Post\PostSite;
use Application\Entity\Site\Language;
use Application\Entity\Site\Site;
use Application\Entity\Tag;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use JasperPHP\JasperPHP;
use Util\Controller\AbstractController;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

abstract class AbstractAdminController extends AbstractController
{
	const SITE_PARAM = 'site';

	/**
	 * Number of entities to return per page of a collection.
	 *
	 * @var int
	 */
	protected $defaultPageSize = 10;

	/**
	 * Current page
	 *
	 * @var string
	 */
	protected $currentPage = 1;

	/**
	 * Name of page size parameter
	 * @var string
	 */
	protected $pageNumberParam = 'page';

	/**
	 *
	 * @var string
	 */
	protected $queryParamns;

	/**
	 * @var ViewModel
	 */
	protected $viewModel;

	/**
	 * @var string
	 */
	protected $currentSite;

	/**
	 * Name of request or query parameter containing identifier
	 *
	 * @var string
	 */
	protected $identifierName = 'id';

	protected $defaultTemplateUpdateName = 'create.phtml';

	protected $authenticationService;

	protected $postForm;

	protected $serializerService;

	protected $reportBasePath = __DIR__ ."/../../../../../data/reports";
	protected $reportOutputPath = __DIR__ ."/../../../../../public/reports";

	public function getViewModel()
	{
		if(null == $this->viewModel) {
			$this->viewModel = new ViewModel();
		}

		return $this->viewModel;
	}

	/**
	 * Retrieve the current id of site from url
	 *
	 * @return int|null
	 */
	protected function getSiteIdFromUri()
	{
		
		if($this->params()->fromRoute(self::SITE_PARAM)) {
			$siteId = $this->params()->fromRoute(self::SITE_PARAM);
		} elseif($this->params()->fromQuery(self::SITE_PARAM)) {
			$siteId = $this->params()->fromQuery(self::SITE_PARAM);
		} else {
			$siteId = null;
		}
		
		return (int) $siteId;
	}

	/**
	 * @return null|Site
	 */
	public function getCurrentSite()
	{
		if(!$this->getSiteIdFromUri()) {
			return null;
		}

		return $this
			->getRepository(Site::class)
			->find($this->getSiteIdFromUri());
	}

	/**
	 * Set the default page size for paginated responses
	 *
	 * @param  int
	 */
	public function setDefaultPageSize($count)
	{
		$this->defaultPageSize = (int) $count;
	}

	/**
	 * Return the default page size
	 *
	 * @return int
	 */
	public function getDefaultPageSize()
	{
		return $this->defaultPageSize;
	}

	public function getPageSize()
	{
		return $this->params()->fromQuery('page_size')
			? $this->params()->fromQuery('page_size')
			: $this->defaultPageSize;
	}

	/**
	 * @return the $page
	 */
	public function getCurrentPage()
	{
		return $this->params()->fromQuery($this->pageNumberParam)
			? $this->params()->fromQuery($this->pageNumberParam)
			: $this->currentPage;
	}

	public function preDispatch(MvcEvent $e)
    {
        $session = new Container();

        $routeMatch = $e->getRouteMatch();
        $controller = strtolower($routeMatch->getParam('__CONTROLLER__', false));
        $action  = $routeMatch->getParam('action', false);

        if(empty($queryParans) && $session->offsetExists('query_filter')) {
            $sess = $session->offsetGet('query_filter');
            if(!empty($sess['controller'])
                && !empty($sess['action'])
                && !empty($sess['query'])) {
                if($sess['controller'] == $controller && $sess['action'] == $action) {
                    foreach ($sess['query'] as $key=>$value) {
                        $routeMatch->setParam($key, $value);
                    }
                }
            }
        }
    }

	public function onDispatch(MvcEvent $e)
	{
		$routeMatch = $e->getRouteMatch();
		if (! $routeMatch) {
			/**
			 * @todo Determine requirements for when route match is missing.
			 *       Potentially allow pulling directly from request metadata?
			 */
			throw new \Zend\Mvc\Exception\DomainException('Missing route matches; unsure how to retrieve action');
		}

		$request = $e->getRequest();

		$controller = strtolower($routeMatch->getParam('__CONTROLLER__', false));

		// Was an "action" requested?
		$action  = $routeMatch->getParam('action', false);
		if ($action) {
            $session = new Container();
			switch ($action) {
				case 'index':
				case 'list':
					$this->getEntityManager()->beginTransaction();
					try {
                        $queryParans = $this->getRequest()->getQuery()->toArray();

                        if(empty($queryParans) && $session->offsetExists('last_url')) {
                            $sess = $session->offsetGet('last_url');
                            if(!empty($sess['controller'])
                                && !empty($sess['action'])
                                && !empty($sess['uri'])
                                && !empty($sess['query'])) {
                                if($sess['controller'] == $controller && $sess['action'] == $action) {
                                    return $this->redirect()->toUrl($sess['uri']);
                                }
                            }
                        }

                        $result = $this->indexAction();
						$this->getEntityManager()->commit();

                        $session->offsetSet('last_url', [
                            'query'         => $queryParans,
                            'controller'    => $controller,
                            'action'        => $action,
                            'uri'           => $this->getRequest()->getUriString()
                        ]);

					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
				case 'update':
					$this->getEntityManager()->beginTransaction();
					try {
						$id = $this->getIdentifier($routeMatch, $request);
						$data = $this->processBodyContent($request);

						$result = $this->updateAction($id, $data);

						$this->getEntityManager()->commit();
					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
				case 'create':
					$this->getEntityManager()->beginTransaction();
					try {
						$data = $this->processBodyContent($request);

						$result = $this->createAction($data);

						$this->getEntityManager()->commit();
					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
				case 'delete':
					$this->getEntityManager()->beginTransaction();
					try {
						$id = $this->getIdentifier($routeMatch, $request);
						$result = $this->deleteAction($id);

						$this->getEntityManager()->commit();
					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
				case 'restore':
					$this->getEntityManager()->beginTransaction();
					try {
						$id = $this->getIdentifier($routeMatch, $request);
						$result = $this->restoreAction($id);

						$this->getEntityManager()->commit();
					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
				default:
					$this->getEntityManager()->beginTransaction();
					try {
						// Handle arbitrary methods, ending in Action
						$method = static::getMethodFromAction($action);
						if (!method_exists($this, $method)) {
							throw new \Exception('404 Page not found', 404);
						}

						$result = $this->$method();
						$this->getEntityManager()->commit();
					} catch (\Exception $ex) {
						$result = $ex;
						$this->getEntityManager()->rollback();
					}
					break;
			}

			if($result instanceof \Exception) {
				throw $result;
			}

			$e->setResult($result);
			return $result;
		}
	}

	/**
	 * Set the route match/query parameter name containing the identifier
	 *
	 * @param  string $name
	 * @return self
	 */
	public function setIdentifierName($name)
	{
		$this->identifierName = (string) $name;
		return $this;
	}

	/**
	 * Retrieve the route match/query parameter name containing the identifier
	 *
	 * @return string
	 */
	public function getIdentifierName()
	{
		return $this->identifierName;
	}

	/** Retrieve the identifier, if any
	*
	* Attempts to see if an identifier was passed in either the URI or the
	* query string, returning it if found. Otherwise, returns a boolean false.
	*
	* @param  \Zend\Mvc\Router\RouteMatch $routeMatch
	* @param  Request $request
	* @return false|mixed
	*/
	protected function getIdentifier($routeMatch, $request)
	{
		$identifier = $this->getIdentifierName();
		$id = $routeMatch->getParam($identifier, false);
		if ($id !== false) {
			return $id;
		}

		$id = $request->getQuery()->get($identifier, false);
		if ($id !== false) {
			return $id;
		}

		return false;
	}

	/**
	 * Process the raw body content
	 *
	 * If the content-type indicates a JSON payload, the payload is immediately
	 * decoded and the data returned. Otherwise, the data is passed to
	 * parse_str(). If that function returns a single-member array with a key
	 * of "0", the method assumes that we have non-urlencoded content and
	 * returns the raw content; otherwise, the array created is returned.
	 *
	 * @param  mixed $request
	 * @return object|string|array
	 */
	protected function processBodyContent($request)
	{
		/*$content = $request->getContent();

		parse_str($content, $parsedParams);

		// If parse_str fails to decode, or we have a single element with key
		// 0, return the raw content.
		if (!is_array($parsedParams)
				|| (1 == count($parsedParams) && isset($parsedParams[0]))
		) {
			return $content;
		}

		return $parsedParams;*/

        $data = array_replace_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        return $data;
	}

	public function prepareDataPost($entity, $data=[], &$post=null)
	{
		$postId = null;
		if($post) {
			if(is_scalar($post)) {
				$postId = $post;
			} elseif(is_object($post) && method_exists($post, 'getId')) {
				$postId = $post->getId();
			}
		}

		if(isset($data['tags'])) {
			$tagsColl = new ArrayCollection();
			if(!empty($data['tags'])) {
				$tags = $data['tags'];
				if(is_string($tags)) {
					$tags = explode(',', $tags);
				}

				foreach($tags as $tagName) {
					$tag = $this->getRepository(Tag::class)->findOneBy(['name'=>$tagName]);
					if(!$tag) {
						$tag = new Tag();
						$tag->setName($tagName);
					}
					$tagsColl->add($tag);
				}
			}

			$data['tags'] = $tagsColl;
		}

        if(isset($data['sites'])) {
            $sites = [];
            foreach ($data['sites'] as $site) {
                array_push($sites, [$site['id'] => $site['highlight']]);
            }
            $data['meta'][PostMeta::SITES] = json_encode($sites, JSON_OBJECT_AS_ARRAY);
        }

		if(isset($data['meta'])) {
			$metaColl = new ArrayCollection();
			if(!empty($data['meta'])) {
				foreach ($data['meta'] as $metaKey=>$metaVal) {
				    if(!empty($metaVal)) {
                        $meta = new PostMeta();
                        $meta->setPost($post);
                        $meta->setKey($metaKey);
                        $meta->setValue($metaVal);

                        $metaColl->add($meta);
                    }
				}
			}
			$data['meta'] = $metaColl;
		}
        if(method_exists($post, 'getMeta')) {
            foreach ($post->getMeta() as $meta) {
                $this->getEntityManager()->remove($meta);
            }
            $post->getMeta()->clear();
        }

		if(isset($data['slug'])) {
			$slug = !empty($data['slug']) ? $data['slug'] : 'sem-titulo';
			$slug = $this->slugify()->create($slug, true, $entity, $this->getSiteIdFromUri(), $postId);
			$data['slug'] = $slug;
		}

		if(isset($data['city'])) {
			$city = $this->getRepository(City::Class)->find($data['city']);
			$data['city'] = $city;
		}

		if(!empty($data['profile'])) {
			$data['profile'] = $this->getRepository(Profile::class)->find($data['profile']);
		}

		if(isset($data['parent'])) {
			if(!empty($data['parent'])) {
				$data['parent'] = $this->getRepository(Post::class)->find($data['parent']);
			} else {
				$data['parent'] = null;
			}
		}

		if(empty($data['language'])) {
		    $data['language'] = 'pt';
        }
        $language = $this->getRepository(Language::class)->find($data['language']);
        $data['language'] = $language;

        if(!empty($data['date']) && !empty($data['hour']) && !empty($data['post_date'])) {
            $postDate = $data['post_date'];
            $newDate = $data['date'].' '.$data['hour'];
            if($postDate != $newDate) {
                $data['post_date'] = $newDate;
            }
        }

		return $data;
	}

	public function search($entity, $search=[], $orderby=[], $igonorePagination=false, $pageSize=null)
	{
		//Get order by annotation entity
		$annotationReader = new AnnotationReader();
		$refClass = new \ReflectionClass($entity);
		$entityOrderBy = $annotationReader->getClassAnnotation($refClass, 'Application\Annotations\OrderBy');
		$entityOrderBy = !empty($entityOrderBy->value) ? $entityOrderBy->value : [];
		$orderby = array_merge($entityOrderBy, $orderby);

		return $this->getRepository($entity)->search($search, $orderby, $igonorePagination, $this->getCurrentPage(), $pageSize);
	}

	/**
	 * @return AuthenticationService
	 */
	public function getAuthenticationService()
	{
		//return $this->getServiceLocator()->get('authentication');
		return $this->authenticationService;
	}

	public function setAuthenticationService(AuthenticationService $authenticationService)
	{
		$this->authenticationService = $authenticationService;
		return $this;
	}

	/**
	 * @return \Application\Entity\AdminUser\User|null
	 */
	public function getCurrentUser()
	{
		$user = $this->getAuthenticationService()->getIdentity();
		if(!$user) {
			return null;
		}
		$this->getEntityManager()->refresh($user);
		return $user;
	}

	/**
	 * @return PostForm
	 */
	public function getPostForm()
	{
		return $this->postForm;
	}

	public function setPostForm($postForm)
	{
		$this->postForm = $postForm;
		return $this;
	}

	public function getViewManager()
	{
		return $this->getServiceLocator()->get('ViewHelperManager');
	}

	public function prepareReport(array $items, $reportName, $format)
    {
        //Generate ID to report
        $report_id = time(false).'_'.mt_rand();

        $jsonFile = $this->createJsonFile(json_encode($items), $report_id);
        if(!file_exists($jsonFile)) {
            throw new \Exception("Arquivo $jsonFile não foi encontrado");
        }

        $input = $this->reportBasePath
            .DIRECTORY_SEPARATOR
            .'reports_files'
            .DIRECTORY_SEPARATOR
            .$reportName.".jasper";

        if(!file_exists($input)) {
            throw new \Exception("Arquivo $input não foi encontrado");
        }

        $output = $this->reportOutputPath
            .DIRECTORY_SEPARATOR
            .$report_id;

        mkdir($output, 0775, true);

        $options = [
            'format' => is_array($format) ? $format : [$format],
            'params' => [],
            'locale' => 'pt_BR',
            'db_connection' => [
                'driver' => 'json',
                'data_file' => $jsonFile,
                'json_query' => 'object'
            ]
        ];

        $jasper = new JasperPHP();
        try {
            $jasper->process( $input, $output, $options);
            $jasper->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        if($this->getRequest()->isXmlHttpRequest()) {
            $reportUrl = rtrim($this->url()->fromRoute('universoproducao'), '/');
            $reportUrl.= '/reports/'
                . $report_id
                . '/' . $reportName.".".$format;

            $report = new JsonModel();
            $report->setTerminal(true);
            $report->report = $reportUrl;
        } else {
            $reportFile =
                $output
                .DIRECTORY_SEPARATOR
                . $report_id
                .DIRECTORY_SEPARATOR
                .$reportName.".".$format;

            $report = $this->dowloadReport($reportFile);
        }

        return $report;
    }

    public function dowloadReport($report_file, $downloadToken=null)
    {
        if(!file_exists($report_file)) {
            throw new \Exception("Arquivo $report_file não encontrado");
        }

        $response = new Stream();
        $response->setStream(fopen($report_file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($report_file));

        $headers = new Headers();
        $headers->addHeaders([
            'Content-Disposition' => 'attachment; filename="' . basename($report_file) .'"',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($report_file),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ]);

        $response->setHeaders($headers);
        return $response;
    }

    public function createJsonFile($content, $reportId) {
        $fileName =
            $this->reportBasePath
            .DIRECTORY_SEPARATOR
            .'data_files'
            .DIRECTORY_SEPARATOR
            .$reportId.'.json';

        $jsonFile = fopen($fileName, 'w');
        if(!$jsonFile) {
            throw new \Exception("Não foi possível criar o arquivo $fileName");
        }

        fwrite($jsonFile, $content);
        fclose($jsonFile);

        return $fileName;
    }

    public function arrayFlatten($array, $prefix = '')
    {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + $this->arrayFlatten($value, $prefix . $key . '.');
            }
            else {
                $result[$prefix."_".$key] = $value;
            }
        }
        return $result;
    }

    /**
     * @return null|Event
     */
    public function getDefaultEvent()
    {
        return $this->getRepository(Event::class)->findOneBy([
            'default' => 1
        ]);
    }
}
