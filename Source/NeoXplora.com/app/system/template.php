<?php
  namespace SkyCore;
  
  class TTemplate {
    private $args;
    private $template;
    private $parent;
    private static $template_dir;
    
    public static function init() {
      self::$template_dir = str_replace("\\", "/", __DIR__) . "/../../theme/";
    }
    
    public function __construct($template = null, $template_path = "", $args = array()) {
      if(isset($template)) {
        $this->load($template, $template_path);
      }
      $this->args = $args;
    }
    
    public function __get($name) {
      if(isset($this->args[$name])) {
        return $this->args[$name];
      } else if($this->parent instanceof TTemplate && isset($this->parent->args[$name])) {
        return $this->parent->args[$name];
      } else {
        return "";
      }
    }

    public function __set($name, $value) {
      $this->args[$name] = $value;
      if($value instanceof TTemplate) {
        $value->setParent($this);
      }
    }
    
    public function setParent($parent) {
      $this->parent = $parent;
    }
    
    public function fetch($template = null, $template_path = "") {
      if($template && !$this->{"hide_" . $template}) {
        $tpl = "tpl_" . $template;
        $this->{$tpl} = new TTemplate($template, $template_path);
        return $this->{$tpl}->parse();
      } else {
        return "";
      }
    }
    
    public function load($template = null, $template_path = "") {
      if(!$this->template) {
        if($template_path) $template_path .= "/";
        if (!is_file(self::$template_dir . $template_path . $template . ".tpl")) {
            throw new \Exception("Template not found: $template");
        } elseif (!is_readable(self::$template_dir . $template_path . $template . ".tpl")) {
            throw new \Exception("Could not access template: $template");
        } else {
            $this->template = $template_path . $template;
        }
      }
    }
    
    public function render() {
      if($this->redirect != "") {
        header("Location: " . $this->redirect);
      } else if(!$this->disable_render && $this->template){
        ob_start();
        require self::$template_dir . $this->template . ".tpl";
        $content = ob_get_clean();
        echo $content;
      } else {
        echo "";
      }
    }
    
    public function parse() {
  	  if($this->template) {
  		  ob_start();
  		  require self::$template_dir . $this->template . ".tpl";
  		  $content = ob_get_clean();
  		  return $content;
  	  } else {
  		  return "";
  	  }
    }
    
    public function addScript($path, $external = false) {
      $script_path = $path;
      if(!$external) {
        $script_path =  $this->site_url . $script_path;
      }
      $this->headerinclude .= "<script src=\"" . $script_path . "\"></script>\n";
    }
    
    public function addScripts($scripts) {
      foreach($scripts as $script) {
        $this->addScript($script);
      }
    }
    
    public function addJSModules($modules) {
      $initScript = "";
      foreach($modules as $moduleName => $modulePath) {
        $this->addScript($modulePath);
        $initScript .= $moduleName . ".init();\n";
      }
      $this->headerinclude .= "<script type='text/javascript'>" . $initScript . "</script>\n";
    }
    
    public function addStyle($path, $external = false) {
      $style_path = $path;
      if(!$external) {
        $style_path =  $this->site_url . $style_path;
      }
      $this->headerinclude .= "<link href=\"" . $style_path . "\" rel=\"stylesheet\" type=\"text/css\" />\n";
    }
    
  }
  
  TTemplate::init();
  
?>