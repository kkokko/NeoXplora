var MInterpreterTrainIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
  	this.base(this);
    this.setConfig($.extend(this.getConfig(), settings));
  },
  
  properties: {
    Config: {
    	Buttons: {
    		save: '.btnDone',
        skip: '.btnSkip',
        use: '.btnUse',
        approve: '.btnApprove',
        edit: '.btnEdit',
        resplit: '.btnResplit'
    	},
      Inputs: {
      	sentenceID: '.sentenceID',
      	repGuessValue: '.repguess',
      	newValue: '.newRepValue'
      },
      Containers: {
      	error: '.rep-error',
        table: '.trainer'
      },
      moduleScript: 'train.php',
      moduleType: 'interpreter',
      dataContainer: '.boxContent'
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.InterpreterTrainIndex.hookEvents();
      NeoX.Modules.InterpreterTrainIndex.load();
    },
    
    hookEvents: function() {
    	NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.save, NeoX.Modules.InterpreterTrainIndex.save);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.skip, NeoX.Modules.InterpreterTrainIndex.skip);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.use, NeoX.Modules.InterpreterTrainIndex.use);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.approve, NeoX.Modules.InterpreterTrainIndex.approve);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.edit, NeoX.Modules.InterpreterTrainIndex.edit);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('click', NeoX.Modules.InterpreterTrainIndex.getConfig().Buttons.resplit, NeoX.Modules.InterpreterTrainIndex.resplit);
      NeoX.Modules.InterpreterTrainIndex.hookEvent('keypress', NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue, NeoX.Modules.InterpreterTrainIndex.repKeyPress);
      NeoX.Modules.InterpreterTrainIndex.hookEvent("change", "#categoryId", NeoX.Modules.InterpreterTrainIndex.catChanged);
    },
    
    load: function() {
    	NeoX.Modules.InterpreterTrainRequests.load();
    },
    
    save: function() {
      if($(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue).val() == '') {
        NeoX.Modules.InterpreterTrainRequests.load();
      } else {
      	var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
        var newValue = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue).val();
        var approved = ($(".checkApproved").length > 0) && $(".checkApproved").is(":checked");
        NeoX.Modules.InterpreterTrainRequests.save(sentenceID, newValue, approved);
      }
    },
    
    catChanged: function() {
      NeoX.Modules.InterpreterTrainRequests.catChanged($(this).val());
    },
    
    skip: function() {
    	var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
      NeoX.Modules.InterpreterTrainRequests.skip(sentenceID);
    },
      
    use: function() {
    	if($(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.repGuessValue).html() == '') {
        NeoX.Modules.InterpreterTrainRequests.load();
      } else {
      	var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
      	var newValue = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.repGuessValue).html();
      	var approved = ($(".checkApproved").length > 0) && $(".checkApproved").is(":checked");
        NeoX.Modules.InterpreterTrainRequests.use(sentenceID, newValue, approved);
      }
    },
    
    approve: function() {
    	var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
      var newValue = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue).val();
      NeoX.Modules.InterpreterTrainRequests.approve(sentenceID, newValue);
    },
    
    edit: function() {
      $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue).val($(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.repGuessValue).html());
    },
    
    resplit: function() {
      var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
      NeoX.Modules.InterpreterTrainRequests.resplit(sentenceID);
    },
    
    repKeyPress: function(event) {
      if(event.which == 13) {
        if($(this).val() == '') {
          NeoX.Modules.InterpreterTrainRequests.load();
        } else {
          var sentenceID = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.sentenceID).val();
          var newValue = $(NeoX.Modules.InterpreterTrainIndex.getConfig().Inputs.newValue).val();
          var approved = ($(".checkApproved").length > 0) && $(".checkApproved").is(":checked");
          NeoX.Modules.InterpreterTrainRequests.save(sentenceID, newValue, approved);
        }
      }
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.InterpreterTrainIndex", MInterpreterTrainIndex_Implementation);
