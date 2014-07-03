/* SkyJS - Javascript Object-Oriented Features Framework */

var Sky = {
  Class: {
  
    /* Param className: name of the class
       Param definition: object containing the definition of the class (extend, properties, members) */
    Define: function(className, definition) {
    	var ObjectContext = Sky.__ParseClassName(className);
    	var ParentContext = Sky.__ParseClassName("Sky.BaseClass");
    	
      if(Sky.__IsFunction(definition.construct)) {
        ObjectContext[Sky.__GetContext(className)] = definition.construct;
      } else {
      	ObjectContext[Sky.__GetContext(className)] = function() {};
      }
      
      if(definition.hasOwnProperty("extend")) {
      	var ParentContext = Sky.__ParseClassName(definition.extend);
        if(Sky.__IsFunction(ParentContext[Sky.__GetContext(definition.extend)])) {
          Sky.__Inherit(ObjectContext[Sky.__GetContext(className)], ParentContext[Sky.__GetContext(definition.extend)]);
        }
      } else if(className != "Sky.BaseClass") {
      	Sky.__Inherit(ObjectContext[Sky.__GetContext(className)], ParentContext[Sky.__GetContext("Sky.BaseClass")]);
      }
      
      if(definition.hasOwnProperty("properties")) {
        Sky.__AddProperties(ObjectContext[Sky.__GetContext(className)], definition.properties);
      }
      
      if(definition.hasOwnProperty("methods")) {
        Sky.__AddMethods(ObjectContext[Sky.__GetContext(className)], definition.methods);
      }
      
      if(definition.hasOwnProperty("type")) {
      	switch(definition.type) {
      		case 'module':
    		    ObjectContext[Sky.__GetContext(className)] = new ObjectContext[Sky.__GetContext(className)]();
      		break;
      	}
      }
    }
    
  },
  
  /* Function handling the inheritance */
  __Inherit: function(subclass, baseclass) {
    function surrogator() {}
    function parentObject() {}
    
    //parent constructor method
    baseclass.prototype["base"] = (function(thebase) {
      return function() {
        //avoid recursive self reinitialization
        if(!(arguments[0].__self instanceof arguments[0].constructor)) {
          var instance = arguments[0];
          instance.parent = new instance.__parentClass();
          //Convert arguments object to array
          arguments = Array.prototype.slice.call(arguments, 1);
          instance.__self = instance;
          
          //method that allows the creation of a new object with variable list of arguments 
          var createBase = (function() {
            function baseObject(args) {
              return instance.__baseclass.apply(this, args);
            }
            baseObject.prototype = instance.__baseclass.prototype;
            return function() {
               return new baseObject(arguments[0]);
            };
          })();
          
          var baseObject = createBase(arguments);
          //pass the main caller instance 
          if(baseObject.hasOwnProperty('__instance')) {
            instance.parent.__instance = baseObject.__instance;
          }
          //avoid recalling the parent constructor, if the current instance is of parent type
          if(!(instance.__baseObject instanceof instance.__baseclass)) {
            instance.__baseclass.apply(baseObject, arguments);
          }
          //set up inhertitance
          instance.__instance = instance;
          instance.__baseclass.apply(instance, arguments);
          instance.__baseObject = baseObject;
        }
      };
    })(baseclass);
    
    //copy parent prototype to kid prototype
    surrogator.prototype = baseclass.prototype;
	var newsurr  = new surrogator();
    subclass.prototype = newsurr;
    subclass.prototype.constructor = subclass;
    //set parent class
    subclass.prototype.__baseclass = baseclass;

    for(var property in subclass.prototype) {
      
      if((subclass.prototype[property] && !typeof(subclass.prototype[property]) != "function")) {
        //clone parent methods to set up polymorphism
        parentObject.prototype[property] = (function(key) {
          return function() {
            //call parent class in the context of the child object
            if(this.instance) {
              baseclass.prototype[key].apply(this.__instance, arguments);
            } else {
              //converting arguments object to array
              var instance = arguments[0];
              arguments = Array.prototype.slice.call(arguments, 1);
              baseclass.prototype[key].apply(instance, arguments);
            }
          };
        })(property);
        
      }
    }
	
    subclass.prototype.__parentClass = parentObject;
    subclass.prototype.__parentClass.prototype = parentObject.prototype;
  },
  
  __IsFunction: function(AFunction) {
    if(AFunction && typeof(AFunction) == "function") {
      return true;
    }
    return false;
  },
  
  __AddMethods: function(clasz, methods) {
    for(var key in methods) {
      var method = methods[key];
      clasz.prototype[key] = method;
    }
  },
  
  __AddProperties: function(clasz, properties) {
    for(var key in properties) {
      var property = properties[key];
      clasz.prototype["__prop" + key] = property;
      clasz.prototype["get" + key] = (function(prop) {
        return function() {
          return this["__prop" + prop];
        };
      })(key);
      clasz.prototype["set" + key] = (function(prop) {
        return function(value) {
          this["__prop" + prop] = value;
        };
      })(key);
    }
  },
  
  __GetFunctionName: function(afunction) {
    var functionName = afunction.toString();
    functionName = functionName.substr('function '.length);
    functionName = functionName.substr(0, functionName.indexOf('('));
    return functionName;
  },
  
  __ParseClassName: function(fullClassName) {
  	var hierarchy = fullClassName.split('.');
    
    var path = Sky.__Context;
    for(var i = 0; i < hierarchy.length - 1; i++) {
    	if(!(path.hasOwnProperty(hierarchy[i]))) {
    		path[hierarchy[i]] = {};
    	}
      path = path[hierarchy[i]];
    }
    
    return path;
  },
  
  __GetContext: function(fullClassName) {
    var hierarchy = fullClassName.split('.');
    return hierarchy[hierarchy.length - 1];
  },
  
  __Context: (window || this)
};

Sky.Class.Define("Sky.BaseClass", {
  constructor: function() {
    this.instance = this;
  }
});

var sky = Sky;
