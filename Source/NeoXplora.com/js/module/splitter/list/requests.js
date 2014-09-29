var MSplitterListRequests_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  construct: function() {
  	this.base(this);
  },
  
  methods: {
    
    init: function() {
      //nothing to init
    },
        
    /*
     * AJAX REQUESTS
     */
    
    load: function(page) {
    	var per_page = $("#per_page").val();
    	var status = $("#sstype").val();
    	var search = $("#search_name").val();
      $.ajax({
        type: "POST",
        url: NeoX.Modules.SplitterListIndex.getConfig().moduleScript,
        dataType: 'json',
        data: {
          'type': NeoX.Modules.SplitterListIndex.getConfig().moduleType,
          'action': 'load',
          'page': page,
          'status': status,
          'search': search,
          'per_page': per_page
        },
        success: NeoX.Modules.SplitterListRequests.loadCallback
      });
    },
    
    /*
     * AJAX SUCCESS CALLBACKS
     */
    
    loadCallback: function(json) {
    	$(NeoX.Modules.SplitterListIndex.getConfig().dataContainer).html(json['data']);
    	$(NeoX.Modules.SplitterListIndex.getConfig().paginationContainer).html(json['pagination']);
    	if(json['pagination'] == "") {
        $(".buttons.smaller").hide();
    	} else {
    		$(".buttons.smaller").show();
    	}
    }
       
    
  }

};

Sky.Class.Define("NeoX.Modules.SplitterListRequests", MSplitterListRequests_Implementation);
