var TRepRecord_Implementation = {
  
  construct: function() {
    this.base(this);
    this.RepEntities = new NeoX.TStringList();
    this.Words = [];
  },
  
  properties: {
    SentenceNumber: null,
    RepEntities: null, //array of key : RepEntity
    Words: null //array of wordObjects { word, repEntity = null }
  },
  
  methods: {
    
  	addWord: function(word, type) {
  		this.Words.push(new NeoX.TRepRecordWord(this, word, type));
  	},
  	
  	addRepEntity: function(key, repEntity) {
  		this.RepEntities.add(key, repEntity);
  	}
  	
  }

};

Sky.Class.Define("NeoX.TRepRecord", TRepRecord_Implementation);