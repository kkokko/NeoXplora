var TCRepRecord_Implementation = {
  
  construct: function(id, sentence, rep) {
    this.base(this);
    this.setId(id);
    this.setSentence(sentence);
    this.setRep(rep);
    this.Children = new Sky.TList();
    this.Highlights = new Sky.TList();
  },
  
  properties: {
  	Id: null, //int
    Sentence: null, //string
    Rep: null, //string
    Children: null, //TList of TStringList of Style: (TList of Intervals) 
    Highlights: null //TList of {Style, Interval}
  },
  
  methods: {
  	
  	highlight: function(AnInterval, AStyle, ColIndex) {
      var i = 0;
      
      var HLights;;
      
      if(typeof ColIndex != "undefined") {
      	console.log("yes");
        HLights = this.Children.object(ColIndex);
      } else {
      	console.log(ColIndex);
      	HLights = this.Highlights;
      }
      
      while(i < HLights.count()) {
    		var TheObject = HLights.object(i);
      	
  			if (TheObject.Interval.OverlapsWith(AnInterval)){
  				if(TheObject.Interval.From < AnInterval.From){
            var TheOldUntil = TheObject.Interval.Until;
            // insert value AnInterval, s1
            HLights.insertAt(i+1, {
            	'Interval': AnInterval,
            	'Style': AStyle
            });
            
            i++;
            
            TheObject.Interval.Until = AnInterval.From;
            TheObject.Style = 's0';
  					if(TheOldUntil > AnInterval.Until){
  						//insert new Interval(AnInterval.Until, TheOldUntil);
  						HLights.insertAt(i+1, {
                'Interval': new Sky.TInterval(AnInterval.Until, TheOldUntil),
                'Style': 's0'
              });
              i++;
  					}
  				} else {
            var TheOldUntil = TheObject.Interval.Until;
            // Remove TheObject.Interval
            HLights.remove(i);
            i--;
  					if(TheObject.Interval.From == AnInterval.From) {
              // insert value AnInterval, s1
              HLights.insertAt(i+1, {
                'Interval': AnInterval,
                'Style': AStyle
              });
              
              i++;
  					}
            if(TheObject.Interval.Until > AnInterval.Until){
              // insert new Interval(AnInterval.Until, TheOldUntil);
            	HLights.insertAt(i+1, {
                'Interval': new Sky.TInterval(AnInterval.Until, TheOldUntil),
                'Style': 's0'
              });
              i++;
            }
  				}
				}
				i++;
  		}
  		
  		var TheOldObject = HLights.object(0);
  		i = 1;
  		while (i < HLights.count()){
        
  			var TheObject = HLights.object(i);
  		  if (TheOldObject.Style == TheObject.Style){
  		  	TheOldObject.Interval.Until = TheObject.Interval.Until;
          // Remove TheObject
  		  	
  		  	HLights.remove(i);
  		  } else {
  		    TheOldObject = TheObject;
  		    i++;
  		  }
  		}
  		
  	}
  }

};

Sky.Class.Define("NeoX.TCRepRecord", TCRepRecord_Implementation);