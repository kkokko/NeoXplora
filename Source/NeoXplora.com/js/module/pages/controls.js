Sky.Class.Define("NeoX.Modules.Pages.Controls", {
  extend: "NeoX.TBaseObject",
  
  type: "module",
  
  construct: function(settings) {
  },
  
  properties: {},
  
  methods: {
    
    init: function() {
      NeoX.Modules.Pages.Controls.hookEvents();
    },
    
    hookEvents: function() {
      NeoX.Modules.Pages.Controls.hookEvent("change", "#categoryId", NeoX.Modules.Pages.Controls.categoryChanged);
      NeoX.Modules.Pages.Controls.hookEvent("change", "#status", NeoX.Modules.Pages.Controls.statusChanged);
    },
    
    categoryChanged: function() {
      var categoryId = $(this).val();
      var status = $("#status").val();
      window.location.href = "panel.php?type=pages&categoryId=" + categoryId + "&status=" + status;      
    },
    
    statusChanged: function() {
    	var categoryId = $("#categoryId").val();
      var status = $(this).val();
      window.location.href = "panel.php?type=pages&categoryId=" + categoryId + "&status=" + status;  
    }

  }
  
});
