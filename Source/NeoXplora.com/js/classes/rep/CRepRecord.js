var TCRepRecord_Implementation = {
  
  construct: function(id, sentence, rep, indentation, type) {
    this.base(this);
    this.setId(id);
    this.setSentence(sentence);
    this.setIndentation(indentation);
    this.setRep(rep);
    this.setType(type);
    this.Children = new Sky.TList();
    this.Highlights = new Sky.TList();
  },
  
  properties: {
  	Id: null, //int
    Sentence: null, //string
    Rep: null, //string
    Indentation: null, //int
    Type: null, //se or pr
    Style: null, //string
    Children: null, //TList of TStringList of Style: (TList of Intervals) 
    Highlights: null //TList of {Style, Interval}
  },
  
  methods: {
  	
  	highlight: function(AnInterval, AStyle, ColIndex) {
      var i = 0;
      
      var HLights;
      
      if(typeof ColIndex != "undefined") {
        HLights = this.Children.object(ColIndex);
      } else {
      	HLights = this.Highlights;
      }
      
      while(i < HLights.count()) {
    		var TheObject = HLights.object(i);
    		
    		if(TheObject.Interval.Equals(AnInterval) && TheObject.Style != 's0') {
    			this.addHighlight(TheObject, AStyle);
    		} else if (TheObject.Interval.OverlapsWith(AnInterval)) {
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
  		
  		this.mergeAdjacent(HLights);
  	},
  	
  	mergeAdjacent: function(highlightsArray) {
  		var TheOldObject = highlightsArray.object(0);
      i = 1;
      while (i < highlightsArray.count()){
        
        var TheObject = highlightsArray.object(i);
        if (TheOldObject.Style == TheObject.Style){
          TheOldObject.Interval.Until = TheObject.Interval.Until;
          highlightsArray.remove(i);
        } else {
          TheOldObject = TheObject;
          i++;
        }
      }
  	},
  	
  	addHighlight: function(AHighlight, AStyle) {
  		if(AStyle == "s0") {
        AHighlight.Style = AStyle;
      } else {
        var styles = AHighlight.Style.split("-");
        if($.inArray(AStyle, styles) == -1) {
          AHighlight.Style = AHighlight.Style + "-" + AStyle;
          styles.push(AStyle);
          
          for(var i = 0; i < styles.length; i++) {
            styles[i] = parseInt(styles[i].replace("s", ""), 10) - 1;
          }
          
          var last = 0;
          var chunkSize = Math.floor(100 / styles.length);
          var css1 = "background: -moz-linear-gradient(left";
          var css2 = "background: -webkit-gradient(linear, left top, right top";
          var css3 = "background: -webkit-linear-gradient(left";
          var css4 = "background: -o-linear-gradient(left";
          var css5 = "background: -ms-linear-gradient(left";
          var css6 = "background: linear-gradient(to right";

          for(var i = 0; i < styles.length; i++) {
            var colorCode = NeoX.Modules.LinkerTrainIndex.getConfig().styles[styles[i]];
            var addition = ", " + colorCode + " " + last + "%, " + colorCode + " " + (last + chunkSize) + "%";
            css1 += addition;
            css2 += ", color-stop(" + last + "%, " + colorCode + "), color-stop(" + (last + chunkSize) + "%, " + colorCode + ")";
            css3 += addition;
            css4 += addition;
            css5 += addition;
            css6 += addition;
            last += chunkSize;
          }
          
          css1 += ");";
          css2 += ");";
          css3 += ");";
          css4 += ");";
          css5 += ");";
          css6 += ");";
          
          $("head").append("<style type='text/css'> ." + AHighlight.Style + " { " + css1 + "  " + css2 + "  " + css3 + "  " + css4 + "  " + css5 + "  " + css6 + " } </style>");
        }
      }
  	}
  	
  }

};

Sky.Class.Define("NeoX.TCRepRecord", TCRepRecord_Implementation);