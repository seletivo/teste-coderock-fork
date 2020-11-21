<?php

ini_set('display_errors',0); 
ini_set('display_startup_erros',0); 
error_reporting(E_ALL);

define('_LOCAL_' , true); //mudar para false em producao
define('_BASE_' , (_LOCAL_ == true) ?  '../' : '../app/');


require _BASE_ . 'vendor/autoload.php';
require _BASE_. 'config.php';

global $config;

$configDoctrine =  \Doctrine\ORM\Tools\Setup::createConfiguration($config['local']);
$driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(new \Doctrine\Common\Annotations\AnnotationReader(), ['../']);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
$configDoctrine->setMetadataDriverImpl($driver);
$configDoctrine->setProxyDir('../Model/Proxy');
$configDoctrine->setProxyNamespace('Model\Proxy');
$configDoctrine->addCustomStringFunction('MATCH_AGAINST', 'Classes\DoctrineExtensions\StringFunction\MatchAgainst');

$configDoctrine->setAutoGenerateProxyClasses(true);

if($config['local']) {
   $configDoctrine->setAutoGenerateProxyClasses(true);
   error_reporting(E_ALL);
   ini_set('display_erros',1);
}

$em = Doctrine\ORM\EntityManager::create($config['db'], $configDoctrine);
function getEntityManager(){
    global $em;
    return $em;
}

$app = new \Slim\App($config);

$container = $app->getContainer();

$container['dirUpload'] = (_LOCAL_) ?__DIR__ . '/../img/'  : __DIR__. '/../public_html/img/';
$container['dirFont'] = __DIR__ . '/' ;

$container['config'] = function ($container) use ($config) {
   return $config;
};

$container['key'] = function ($container) {
   return '87fdjfduity*';
};

$container['em'] = function ($container) use ($config, $configDoctrine) {
   return Doctrine\ORM\EntityManager::create($config['db'], $configDoctrine);
};

$container['view'] = function ($container) {
   $view = new \Slim\Views\Twig(_BASE_ . 'View', [
      'cache' => _BASE_ . 'View/Cache'
   ]);

   $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
   $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

   return $view;
};

$app->options('/{routes:.+}', function ($request, $response, $args) {
   return $response;
});

require _BASE_ . 'route.php';

$app->add(new Slim\Middleware\JwtAuthentication([
    "regexp" => "/(.*)/",
    "header" => "X-Token",
    "path" => "/",
    "passthrough" => [          
                      
    ],
    "realm" => "Protected",
    "secure" => false,
    "secret" => $container['key'],
    "callback" => function (\Slim\Http\Request $request, \Slim\Http\Response $response, $arguments) use ($container) {        
        
    }
])
);

unset($config);
$app->run();
