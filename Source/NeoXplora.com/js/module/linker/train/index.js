var MLinkerTrainIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
    this.Data = new NeoX.TStringList();
    this.Entities = new NeoX.TStringList();
  },
  
  properties: {
    Config: {
      Buttons: {
        addPerson: '#add-person',
        addObject: '#add-object',
        addGroup: '#add-group'
      },
      moduleScript: 'train.php',
      moduleType: 'linker',
      sentenceContainer: '.boxLeft',
      entityContainer: '.boxRight'
    },
    Data: null,
    Entities: null
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvents();
      $(document).ready( function() {
      	$(NeoX.Modules.EntityControl.getConfig().Controls.self).matchHeight(false);
      });
      NeoX.Modules.LinkerTrainIndex.load();
    },
    
    hookEvents: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addPerson, NeoX.Modules.LinkerTrainIndex.addEntityEvent,  {type: "Person"});
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addObject, NeoX.Modules.LinkerTrainIndex.addEntityEvent, {type: "Object"});
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addGroup, NeoX.Modules.LinkerTrainIndex.addEntityEvent, {type: "Group"});
    },
    
    addEntityEvent: function(e) {
    	NeoX.Modules.EntityControl.addEntity(e.data.type);
    },
    
    load: function() {
      //NeoX.Modules.LinkerTrainRequests.load();    	
    	var json = $.parseJSON('[{"Key":"1","Value":{"Words":[{"word":"Sara","type":"word"},{"word":" ","type":"separator"},{"word":"has","type":"word"},{"word":" ","type":"separator"},{"word":"been","type":"word"},{"word":" ","type":"separator"},{"word":"married","type":"word"},{"word":" ","type":"separator"},{"word":"to","type":"word"},{"word":" ","type":"separator"},{"word":"John","type":"word"},{"word":" ","type":"separator"},{"word":"for","type":"word"},{"word":" ","type":"separator"},{"word":"seven","type":"word"},{"word":" ","type":"separator"},{"word":"years","type":"word"}],"Entities":[{"Key":"p1","Value":{"Id":1,"Name":"p1","EntityType":"etPerson","EntityNumber":"1","PageId":"1","Kids":[{"Key":"name","Value":{"Id":1,"PropertyType":"ptAttribute","Key":"name","ParentEntityId":1,"Values":[{"Key":"Sara","Value":{"Id":1,"TargetEntityId":null,"TargetKeyId":null,"TargetValueId":null,"OperatorType":"otEquals","KeyId":1,"Value":"Sara"}}]}},{"Key":"has been","Value":{"Id":2,"PropertyType":"ptEvent","Key":"have been","ParentEntityId":1,"Values":[{"Key":"married","Value":{"Id":2,"Kids":[{"Key":"to who","Value":{"Id":3,"PropertyType":"ptAttribute","Key":"to who","ParentEntityId":1,"Values":[{"Key":"2","Value":{"Id":3,"TargetEntityId":2,"TargetKeyId":null,"TargetValueId":null,"OperatorType":"otEquals","KeyId":3,"Value":""}}]}},{"Key":"how long","Value":{"Id":4,"PropertyType":"ptAttribute","Key":"how long","ParentEntityId":1,"Values":[{"Key":"seven years","Value":{"Id":4,"TargetEntityId":null,"TargetKeyId":null,"TargetValueId":null,"OperatorType":"otEquals","KeyId":4,"Value":"seven years"}}]}}],"TargetEntityId":null,"TargetKeyId":null,"TargetValueId":null,"OperatorType":"otEquals","KeyId":2,"Value":"married"}}]}}]}},{"Key":"p2","Value":{"Id":2,"Name":"p2","EntityType":"etPerson","EntityNumber":"2","PageId":"1","Kids":[{"Key":"name","Value":{"Id":5,"PropertyType":"ptAttribute","Key":"name","ParentEntityId":2,"Values":[{"Key":"John","Value":{"Id":5,"TargetEntityId":null,"TargetKeyId":null,"TargetValueId":null,"OperatorType":"otEquals","KeyId":5,"Value":"John"}}]}}]}}]}}]'); 
    	NeoX.Modules.LinkerTrainIndex.loadData(json);
    	NeoX.Modules.LinkerTrainIndex.displayData();
    },
    
    displayData: function() {
    	NeoX.Modules.LinkerTrainIndex.displayEntities();
    	NeoX.Modules.LinkerTrainIndex.displaySentences();
    },
    
    displaySentences: function() {
    	var html = '';
    	var data = NeoX.Modules.LinkerTrainIndex.getData();
    	for(var i = 0; i < data.count(); i++) {
      	for(var j =0; j < data.object(i).Words.length; j++) {
      		var word = data.object(i).Words[j];
      		if(typeof word.LinkedEntity == "undefined" || word.LinkedEntity == null) {
      		  html += word.Word;
      		} else {
      			
      			html += "<span class='word highlighted color" + word.LinkedEntity.EntityNumber + "' id='e" + word.LinkedEntity.EntityNumber + "-w" + j + "'>" + word.Word + "</span>";
      		}
        }
        html += "<br/>";
    	}
    	
    	$(document).ready(function() {
        $(NeoX.Modules.LinkerTrainIndex.Config.sentenceContainer).html(html);
    	});
    },
    
    displayEntities: function() {
      var html = '';
      var entities = NeoX.Modules.LinkerTrainIndex.Entities;
      for(var i = 0; i < entities.count(); i++) {
      	var entity = entities.object(i);
      	html += "<div class='entity ";
      	if(entity.EntityType == "Group") {
          html += "groupEntity";
      	} else {
      		html += "singleEntity";
      	}
      	html += "'><div class='portrait color" + entity.Id + "'>";
      	switch(entity.EntityType) {
      		case "etPerson":
      		  html += "Person <b>" + entities.item(i) + "</b>";
      		  break;
          case "etObject":
            html += "Object <b>" + entities.item(i) + "</b>";
            break;
          case "etGroup":
            html += "Group <b>" + entities.item(i) + "</b>";
            break;
      	}
      	html += "</div>";
      	html += "<div class='info'>";
      	 
      	html += "</div>";
      	html += "</div>";
      }
      $(document).ready(function() {
        $(NeoX.Modules.LinkerTrainIndex.Config.entityContainer).html(html);
      });
    },
    
    /*
              <div class='info'>
                <?php foreach($entity['data'] as $info_key => $info_values) { ?>
                  <div class='label'><?php echo $info_key; ?></div>
                    <?php foreach($info_values AS $info_value) { ?>
                      <div class='value'><?php echo $info_value; ?></div>
                    <?php } ?>
                <?php } ?>
              </div>
              
            </div>*/
    
    loadData: function(json) {
    	var data = NeoX.Modules.LinkerTrainIndex.getData();
      for(var i = 0; i < json.length; i++) {
      	var sentence = json[i];
      	var repRecord = new NeoX.TRepRecord();
      	
      	NeoX.Modules.LinkerTrainIndex.loadWords(repRecord, sentence.Value.Words);
      	NeoX.Modules.LinkerTrainIndex.loadEntities(repRecord, sentence.Value.Entities);
      	NeoX.Modules.LinkerTrainIndex.highlightWords(repRecord);
      	
      	data.add(sentence.Key, repRecord);
      }
    },
    
    loadWords: function(repRecord, wordList) {
    	for(var i = 0; i < wordList.length; i++) {
        repRecord.addWord(wordList[i].word, wordList[i].type);
      }
    },
    
    loadEntities: function(repRecord, entityList) {
    	var Entities = NeoX.Modules.LinkerTrainIndex.Entities;
      for(var i = 0; i < entityList.length; i++) {
      	
      	var entity = entityList[i].Value;
      	var repEntity = new NeoX.TRepEntity(repRecord, entity.Id, entity.EntityNumber, entity.EntityType, entity.Name, entity.PageId);
      	NeoX.Modules.LinkerTrainIndex.loadPropertyKey(repEntity, entity.Kids);
      	
      	var key = "";
      	switch(entity.EntityType) {
      		case "etObject": 
            key += "o";
      		  break;
      		case "etPerson":
      		  key += "p";
      		  break;
      		case "etGroup":
      		  key += "g";
      		  break;
      	}
      	key += entity.EntityNumber;
      	
        repRecord.addRepEntity(key, repEntity);
        if(!Entities.itemExists(key)) {
          Entities.add(key, repEntity);
        }
      }
    },
    
    loadPropertyKey: function(repEntity, propertyList) {
    	for(var i = 0; i < propertyList.length; i++) {
    		var property = propertyList[i].Value;
    		var repPropertyKey = new NeoX.TRepPropertyKey(repEntity, property.Id, property.Key, property.ParentEntityId, property.PropertyType);
    		
    		NeoX.Modules.LinkerTrainIndex.loadPropertyValue(repPropertyKey, property.Values);
    		
    		repEntity.addKid(property.Key, repPropertyKey);
    	}
    },
    
    loadPropertyValue: function(repPropertyKey, valueList) {
      for(var i = 0; i < valueList.length; i++) {
        var value = valueList[i].Value;
        var repPropertyValue = new NeoX.TRepPropertyValue(repPropertyKey, value.Id, value.TargetEntityId, value.TargetKeyId, value.TargetValueId, value.OperatorType, value.KeyId, value.Value);
        
        if(value.hasOwnProperty("Kids")) {
          NeoX.Modules.LinkerTrainIndex.loadPropertyKey(repPropertyValue, value.Kids);
        }
        
        var key = value.Value;
        if(key == "") {
          key = repPropertyKey.Id + " " + value.Id + " " + value.TargetEntityId + " " + value.TargetKeyId + " " + value.TargetValueId;
        }
        
        repPropertyKey.addValue(key, repPropertyValue);
      }
    },
    
    highlightWords: function(repRecord) {
    	var wordList = repRecord.Words;
    	var entityList = repRecord.RepEntities;
    	
    	//go through all words in the sentence
    	for(var i = 0; i < wordList.length; i++) {
    		var theWord = wordList[i];

    		if(theWord.Type == "word") {
    			
    			//go through all the entities in MREP
          for(var j = 0; j < entityList.count(); j++) {          	
          	var entity = entityList.object(j);
          	
          	//go through all properties of that entity that have name or ref as key
          	for(var k = 0; k < entity.Kids.count(); k++) {
          		
              if(entity.Kids.item(k) == "name" || entity.Kids.item(k) == "ref") {
              	var property = entity.Kids.object(k);
              	
              	//go through all of the properties values and see if you find a match
                for(var l = 0; l < property.Values.count(); l++) {
                	
                  if(theWord.Word == property.Values.item(l)) {
                  	theWord.LinkedEntity = entity;
                  }
                }
              }
          	}
          }
    		}
    	}
    }
    
  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerTrainIndex", MLinkerTrainIndex_Implementation);
