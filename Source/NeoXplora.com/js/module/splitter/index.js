var MSplitterIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
      splitBtn: ".splitButton",
      moduleScript: "train.php",
      moduleType: "splitter",
      dataContainer: ".boxContent"
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.SplitterIndex.hookEvents();
      NeoX.Modules.SplitterIndex.load();
    },
    
    hookEvents: function() {
      
    },
    
    load: function() {
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterIndex.getConfig().moduleType,
          'action': 'load'
        },
        success: function(json) {
          $(NeoX.Modules.SplitterIndex.getConfig().dataContainer).html(json['data']);
          //$('.newRepValue').focus();
        }
      });
    }
    
  }
  
  
  
};

Sky.Class.Define("NeoX.Modules.SplitterIndex", MSplitterIndex_Implementation);
