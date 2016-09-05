<?php
use Application\Navigation;

return [
    'factories' => [
        'ArquivoemcartazNavigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(3, 'arquivoemcartaz/default');
            return $navigation->createService($e);
        }
    ]
];