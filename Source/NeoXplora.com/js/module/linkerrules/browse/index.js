var MLinkerRuleBrowseIndex_Implementation = {
	extend : "NeoX.TBaseObject",
	type : "module",

	construct : function(settings) {
		this.base(this);
		this.setConfig($.extend(this.getConfig(), settings));
	},

	properties : {
		Config : {
			Sortable : '#rulesSortable',
			SortableItems : '#rulesSortable li',
			DeleteRuleBtn: "#deleteRule",
			moduleScript : 'panel.php',
			moduleType : 'linkerrule'
		}
	},

	methods : {

		init : function() {
			NeoX.Modules.LinkerRuleBrowseIndex.hookEvents();
			NeoX.Modules.LinkerRuleBrowseIndex.initSortableRuleList();
			
		},

		hookEvents : function() {
      this.hookEvent("click", NeoX.Modules.LinkerRuleBrowseIndex.getConfig().DeleteRuleBtn, this.deleteRule);
		},

		initSortableRuleList : function() {
			$(function() {
				$(NeoX.Modules.LinkerRuleBrowseIndex.getConfig().Sortable)
						.sortable({
							placeholder : "ui-state-highlight",
							update : NeoX.Modules.LinkerRuleBrowseIndex.UpdateHandler
						});
				$(NeoX.Modules.LinkerRuleBrowseIndex.getConfig().Sortable)
						.disableSelection();
			});
		},

		UpdateHandler : function(event, ui) {
			var data = [];

			$(NeoX.Modules.LinkerRuleBrowseIndex.getConfig().SortableItems).each(
					function(index) {
						var el = $(this);
						data.push([el.attr('data-id'), el.index() + 1]);
					});

			NeoX.Modules.LinkerRuleBrowseRequests.updateOrder(data);
		},
		
		deleteRule: function() {
			var rule = $(this).parent().parent();
      NeoX.Modules.LinkerRuleBrowseRequests.deleteRule(rule);
		}

	}

};

Sky.Class.Define("NeoX.Modules.LinkerRuleBrowseIndex",
		MLinkerRuleBrowseIndex_Implementation);