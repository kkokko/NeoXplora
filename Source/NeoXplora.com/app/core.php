<?php 
  namespace SkyCore;
  
  define('APP_DIR', str_replace("\\", "/", __DIR__) . "/../");
  require_once "system/Object.php";
  require_once "system/Model.php";
  
  class TCore extends TObject {
    
    private $appNamespace;
	
    public function __construct() {
      require_once APP_DIR . "/app/Config.php";
      require_once APP_DIR . "/app/system/Template.php";
      require_once APP_DIR . "/app/system/SessionManager.php";
      require_once APP_DIR . "/app/system/Registry.php";
      
      
      $registry = TRegistry::getInstance();
      
      $this->registry = $registry;
      
      $this->registry->db = new \mysqli($db_host2, $db_username2, $db_password2, $db_name2) or die("error");
      $this->db->set_charset($db_collation2);
      
      $this->registry->userdb = new \mysqli($db_host, $db_username, $db_password, $db_name) or die("error");
      $this->registry->template = new TTemplate();
      $this->registry->session = new TSessionManager($this->userdb);
      $this->registry->core = $this;
      
      $this->registry->models = array();
      $this->registry->entities = array();
      $this->registry->controllers = array();
      $this->appNamespace = $application_namespace;
      
      $path = implode("/", array_slice(explode("/", $_SERVER['PHP_SELF']), 0, -1));
      $this->template->site_url = "http://" . $_SERVER['HTTP_HOST'] . $path . "/";
      
      $this->initialize_session();
    }
    
    public function execute() {
      $file = basename($_SERVER['PHP_SELF'], '.php');
      $action = isset($_REQUEST['action'])?$_REQUEST['action']:"index";
      $type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
      $controller = null;
      
      if($type == "") {
        $controller = $this->controller($file);
      } else {
        $controller = $this->controller($type, $file);
      }

      if($controller) {
        if(method_exists($controller, $action)) {
          if($controller->accessLevel() != 'guest' && !$this->session->loggedIn()) {
            $this->template->redirect = $this->template->site_url . "login.php?action=" . $file;
            $this->template->render();
          } else if($controller->accessLevel() == 'admin' && !$this->session->isAdmin()) {
            $this->template->redirect = $this->template->site_url;
            $this->template->render();
          } else {
            $controller->$action();
          }
        } else {
          die("Invalid request " . $file . " - " . $type . " - " . $action );
        }
      } else {
        die("Invalid request " . $file . " - " . $type . " - " . $action );
      }
    }
    
    public function model($model, $parent = "") {
      if(isset($this->models[strtolower($parent . "_" . $model)])) {
        return $this->models[strtolower($parent . "_" . $model)];
      } else {
        if($this->load_model($model, $parent))
          return $this->models[strtolower($parent . "_" . $model)];
        else
          return false;
      }
    }
    
    public function entity($entity, $parent = "") {
      if(isset($this->entities[strtolower($parent . "_" . $entity)])) {
        return $this->entities[strtolower($parent . "_" . $entity)];
      } else {
        if($this->load_entity($entity, $parent))
          return $this->entities[strtolower($parent . "_" . $entity)];
        else
          return false;
      }
    }
    
    public function controller($controller, $parent = "") {
      if(isset($this->controllers[strtolower($parent . "_" . $controller)])) {
        return $this->controllers[strtolower($parent . "_" . $controller)];
      } else {
        if($this->load_controller($controller, $parent))
          return $this->controllers[strtolower($parent . "_" . $controller)];
        else
          return false;
      }
    }
        
    public function getNamespace() {
      return '\\' . $this->appNamespace;
    }
    
    private function load_model($model, $parent = "") {
      $file_path = APP_DIR . "model/" . (($parent != "")?$parent . "/":"") . $model . ".php";
      if(file_exists($file_path)) {
        require_once $file_path;
        if(class_exists($this->getNamespace() . '\\Model\\T' . $this->parse_parent($parent) . $model)) {
          $class_name = $this->getNamespace() . '\\Model\\T' . $this->parse_parent($parent) . $model;
          $this->registry->models[strtolower($parent . "_" . $model)] = new $class_name($this->registry);
          return true; 
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    
    private function load_entity($entity, $parent = "") {
      $file_path = APP_DIR . "model/entity/" . (($parent != "")?$parent . "/":"") . $entity . ".php";
      if(file_exists($file_path)) {
        require_once $file_path;
        if(class_exists($this->getNamespace() . '\\Entity\\T' . $this->parse_parent($parent) . $entity)) {
          $class_name = $this->getNamespace() . '\\Entity\\T' . $this->parse_parent($parent) . $entity;
          $this->registry->entities[strtolower($parent . "_" . $entity)] = new $class_name($this->registry);
          return true; 
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    
    private function load_controller($controller, $parent = "") {
      $file_path = APP_DIR . "controller/" . (($parent != "")?$parent . "/":"") . $controller . ".php";
      if(file_exists($file_path)) {
        require_once $file_path;
        if(class_exists($this->getNamespace() . '\\Controller\\T' . $this->parse_parent($parent) . $controller)) {
          $class_name = $this->getNamespace() . '\\Controller\\T' . $this->parse_parent($parent) . $controller;
          $this->registry->controllers[strtolower($parent . "_" . $controller)] = new $class_name($this->registry);
          return true; 
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    
    private function parse_parent($parent) {
      if(preg_match("/\//", $parent)) {
        $parents = preg_split("/\//", $parent);
        $parent = "";
        foreach($parents AS $value) {
          $parent .= $value;
        }
      } 
      return $parent;
    }
    
    private function initialize_session() {
      $this->session->startSession();
      $logged_in = false;
      $username = "";
      $userlevel = 'guest';
      if ($this->session->loggedIn()) {
        $logged_in = true;
        $username = $this->session->getUserName();
        $userlevel = 'user';
        if($this->session->isAdmin())
          $userlevel = 'admin';
      }
      
      $this->template->logged_in = $logged_in;
      $this->template->username = $username;
      $this->template->userlevel = $userlevel;
    }
    
  }

  $app = new TCore();

?>
