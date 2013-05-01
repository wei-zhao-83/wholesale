<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    Doctrine\DBAL\Event\Listeners\MysqlSessionInit,
    Doctrine\DBAL\Logging\EchoSqlLogger,
    Doctrine\ORM\Tools\SchemaTool,
    Doctrine\ORM\EntityRepository,
    Doctrine\Common\EventManager;

class Doctrine {

  public $em = null;

  public function __construct()
  {
    // load database configuration from CodeIgniter
    require_once APPPATH.'config/database.php';

    // Set up class loading. You could use different autoloaders, provided by your favorite framework,
    // if you want to.
    require_once APPPATH.'libraries/Doctrine/Common/ClassLoader.php';

    $doctrineClassLoader = new ClassLoader('Doctrine',  APPPATH.'libraries');
    $doctrineClassLoader->register();
    $entitiesClassLoader = new ClassLoader('models', rtrim(APPPATH, "/" ));
    $entitiesClassLoader->register();
    
    foreach (glob(APPPATH . 'modules/*', GLOB_ONLYDIR) as $m) {
        $module = str_replace(APPPATH . 'modules/', '', $m);
        $entitiesClassLoader = new ClassLoader($module, APPPATH . 'modules');
        $entitiesClassLoader->register();
    }
    
    //$proxiesClassLoader = new ClassLoader('Proxies', APPPATH.'models/proxies');
    //$proxiesClassLoader->register();

    // Set up caches
    $config = new Configuration;
    $cache = new ArrayCache;
    $config->setMetadataCacheImpl($cache);
    
    // Set up driver
    $models = array(APPPATH.'models');
    foreach (glob(APPPATH.'modules/*/models', GLOB_ONLYDIR) as $m){
        array_push($models, $m);
    }
    
    $driverImpl = $config->newDefaultAnnotationDriver($models);
    $config->setMetadataDriverImpl($driverImpl);
    $config->setQueryCacheImpl($cache);
    
    // Proxy configuration
    $config->setProxyDir(APPPATH . 'models/proxies');
    $config->setProxyNamespace('Proxies');

    // Set up logger
    //$logger = new EchoSQLLogger;
    //$config->setSQLLogger($logger);
    
    $config->setAutoGenerateProxyClasses( TRUE );

    // Database connection information
    $connectionOptions = array(
        'driver' => 'pdo_mysql',
        'user' =>     $db['default']['username'],
        'password' => $db['default']['password'],
        'host' =>     $db['default']['hostname'],
        'dbname' =>   $db['default']['database']
    );

    // Create EntityManager
    $this->em = EntityManager::create($connectionOptions, $config);
  }
}