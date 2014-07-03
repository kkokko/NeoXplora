<?php 
  define('APP_DIR', str_replace("\\", "/", __DIR__) . "/../");
  require_once "system/appentity.php";
  require_once "system/model.php";
  
  class TCore extends TAppEntity {
    
    private $models;
    private $controllers;
	
    public function __construct() {
      require_once APP_DIR . "/app/config.php";
      require_once APP_DIR . "/app/system/template.php";
      require_once APP_DIR . "/app/system/sessionmanager.php";
      
      $this->db = new mysqli($db_host2, $db_username2, $db_password2, $db_name2) or die("error");
      $this->db->set_charset($db_collation2);
      $this->userdb = new mysqli($db_host, $db_username, $db_password, $db_name) or die("error");
      $this->template = new \sky\Template();
      $this->session = new SessionManager($this->userdb);
      
      $this->models = array();
      $this->controllers = array();
      
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
          die("Invalid request");
        }
      } else {
        die("Invalid request");
      }
    }
    
    public function model($model, $parent = "") {
      if(isset($this->models[$parent . "_" . $model])) {
        return $this->models[$parent . "_" . $model];
      } else {
        if($this->load_model($model, $parent))
          return $this->models[$parent . "_" . $model];
        else
          return false;
      }
    }
    
    public function controller($controller, $parent = "") {
      if(isset($this->controllers[$parent . "_" . $controller])) {
        return $this->controllers[$parent . "_" . $controller];
      } else {
        if($this->load_controller($controller, $parent))
          return $this->controllers[$parent . "_" . $controller];
        else
          return false;
      }
    }
    
    private function load_model($model, $parent = "") {
      $file_path = APP_DIR . "model/" . (($parent != "")?$parent . "/":"") . strtolower($model) . ".php";
      if(file_exists($file_path)) {
        require_once $file_path;
        if(class_exists("Model" . $parent . $model)) {
          $class_name = "Model" . $parent . $model;
          $this->models[$parent . "_" . $model] = new $class_name($this, $this->db, $this->template);
          return true; 
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    
    private function load_controller($controller, $parent = "") {
      $file_path = APP_DIR . "controller/" . (($parent != "")?$parent . "/":"") . strtolower($controller) . ".php";
      if(file_exists($file_path)) {
        require_once $file_path;
        if(class_exists("Controller" . $parent . $controller)) {
          $class_name = "Controller" . $parent . $controller;
          $this->controllers[$parent . "_" . $controller] = new $class_name($this, $this->db, $this->template);
          return true; 
        } else {
          return false;
        }
      } else {
        return false;
      }
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