var MIRepRuleBrowseIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
		Sortable:'#rulesSortable',
		SortableItems:'#rulesSortable li',
		moduleScript:'panel.php',
		moduleType: 'ireprules'
    }
  },
  
  methods: {
    
    init: function() {
		
      NeoX.Modules.IRepRuleBrowseIndex.hookEvents();
      NeoX.Modules.IRepRuleBrowseIndex.initSortableRuleList();
    },
    
    hookEvents: function() {
    	
    },
    
    initSortableRuleList: function() {
		$(function(){
			$( NeoX.Modules.IRepRuleBrowseIndex.getConfig().Sortable ).sortable({
				placeholder: "ui-state-highlight",
				update: NeoX.Modules.IRepRuleBrowseIndex.UpdateHandler
			});
			$( NeoX.Modules.IRepRuleBrowseIndex.getConfig().Sortable ).disableSelection();
		});
    },
	
	UpdateHandler: function (event,ui){
		var data = [];
		
		$(NeoX.Modules.IRepRuleBrowseIndex.getConfig().SortableItems).each(function(index){
			var el = $(this);
			data.push([el.attr('data-id'),el.index()+1]);
		});
		
		NeoX.Modules.IRepRuleBrowseRequests.updateOrder(data);
	}
        
  }
  
};

Sky.Class.Define("NeoX.Modules.IRepRuleBrowseIndex", MIRepRuleBrowseIndex_Implementation);