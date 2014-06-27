var TBaseObject_Implementation = {
  construct: function() {
    this.base(this);
  },
  members: {
    hookEvent: function(event, selector, func) {
      $(document).ready(function() {
        $(document).on(event, selector, func);
      });
    },
    getClassList: function(element) {
      return element.attr('class').split(/\s+/);
    }
  }
};

Sky.Class.Define("TBaseObject", TBaseObject_Implementation);