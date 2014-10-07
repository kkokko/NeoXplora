var MLinkerTrainIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
    this.getConfig().data = new Sky.TStringList();
  },
  
  properties: {
    Config: {
    	Buttons: {
        addRepBtn: '.addRepColumn',
        saveBtn: '.saveBtn',
        skipBtn: '.skipBtn',
        finishBtn: '.finishBtn'
      },
      maxChildren: 0,
      moduleScript: 'train.php',
      moduleType: 'linker',
      dataContainer: '.boxContent',
      selectedStyle: 's0',
      data: null,
      charSelector: '.char',
      startPos: null,
      currentCol: null,
      currentRow: null,
      keyDown: false,
      styles: ['#AEABAB', '#ADB9CA', '#BDD7EE', '#F7CBAC', '#DBDBDB', '#FEE599', '#B4C6E7', '#C5E0B3', '#757070', '#8496B0', '#9CC3E5',
                '#F4B183', '#C9C9C9', '#FFD965', '#8EAADB', '#A8D08D', '#3A3838', '#323F4F', '#2F75B5', '#C55A11', '#7B7B7B', '#BF9000',
                '#2F5496', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#00FFFF', '#FF00FF', '#6633CC', '#663300', '#880000']
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvents();
      NeoX.Modules.LinkerTrainIndex.load();
    },
    
    hookEvents: function() {
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", ".color-pallette td", NeoX.Modules.LinkerTrainIndex.selectStyle);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.addRepBtn, NeoX.Modules.LinkerTrainIndex.addRepColumn);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", ".deleteRepColumn", NeoX.Modules.LinkerTrainIndex.deleteRepColumn);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.saveBtn, NeoX.Modules.LinkerTrainIndex.save);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.skipBtn, NeoX.Modules.LinkerTrainIndex.skip);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().Buttons.finishBtn, NeoX.Modules.LinkerTrainIndex.finish);
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().charSelector, NeoX.Modules.LinkerTrainIndex.charClicked);
      NeoX.Modules.LinkerTrainIndex.hookEvent("change", "#categoryId", NeoX.Modules.LinkerTrainIndex.catChanged);
      $(document).on("keydown", NeoX.Modules.LinkerTrainIndex.onKeyDown);
      $(document).on("keyup", NeoX.Modules.LinkerTrainIndex.onKeyUp);
    },
    
    onKeyDown: function(e) {
      if(e.which == "18") {
        NeoX.Modules.LinkerTrainIndex.getConfig().keyDown = true;
      } else {
      	NeoX.Modules.LinkerTrainIndex.getConfig().keyDown = false;
      }
    },
    
    onKeyUp: function() {
      NeoX.Modules.LinkerTrainIndex.getConfig().keyDown = false;
    },    
    
    load: function() {
    	var styles = '<style type="text/css">';
    	for( var i = 0; i < NeoX.Modules.LinkerTrainIndex.getConfig().styles.length; i++ ) {
    		styles += ".s" + (i + 1) + " { ";
    		styles += "border: 2px solid " + NeoX.Modules.LinkerTrainIndex.getConfig().styles[i] + "; ";
    		styles += "background-color: " + NeoX.Modules.LinkerTrainIndex.getConfig().styles[i] + "; ";
    		styles += " } ";
    	}
    	styles += "</style>";
    	$(document).ready(function() {
        $("head").append(styles);
    	});
      NeoX.Modules.LinkerTrainRequests.load();    	
    },
    
    catChanged: function() {
    	NeoX.Modules.LinkerTrainRequests.catChanged($(this).val());
    },
    
    save: function() {
    	NeoX.Modules.LinkerTrainRequests.save(NeoX.Modules.LinkerTrainIndex.dataToSaveArray());
    },
    
    finish: function() {
    	NeoX.Modules.LinkerTrainRequests.finish(NeoX.Modules.LinkerTrainIndex.dataToSaveArray());
    },
    
    skip: function() {
      NeoX.Modules.LinkerTrainRequests.skip();
    },
    
    dataToSaveArray: function() {
    	var data = [];
      for(var i = 0; i < NeoX.Modules.LinkerTrainIndex.getConfig().data.count(); i++) {
        var CRepRecord = NeoX.Modules.LinkerTrainIndex.getConfig().data.object(i);
        if(CRepRecord.Type == 'pr') continue;
        
        var Highlights = [];
        var Children = [];
        for(var j = 0; j < CRepRecord.Highlights.count(); j++) {
          Highlights.push({
            'From': CRepRecord.Highlights.object(j).Interval.From,
            'Until': CRepRecord.Highlights.object(j).Interval.Until,
            'Style': CRepRecord.Highlights.object(j).Style
          });  
        }
        for(var j = 0; j < CRepRecord.Children.count(); j++) {
          var SubHighlights = [];
          for(var k = 0; k < CRepRecord.Children.object(j).count(); k++) {
            SubHighlights.push({
              'From': CRepRecord.Children.object(j).object(k).Interval.From,
              'Until': CRepRecord.Children.object(j).object(k).Interval.Until,
              'Style': CRepRecord.Children.object(j).object(k).Style
            });
          }
          Children.push(SubHighlights);
        }
        data.push({
          'Id': CRepRecord.Id,
          'Highlights': Highlights,
          'Children': Children
        });
      }
      
      return data;
    },
    
    selectStyle: function() {
    	$(".currentStyle").removeClass("currentStyle");
    	if(NeoX.Modules.LinkerTrainIndex.getConfig().selectedStyle != $(this).attr("class")) {
        NeoX.Modules.LinkerTrainIndex.getConfig().selectedStyle = $(this).attr("class");
        $(this).addClass("currentStyle");
    	} else {
    		NeoX.Modules.LinkerTrainIndex.getConfig().selectedStyle = "s0";
    	}
    },
    
    addRepColumn: function() {
    	var data = NeoX.Modules.LinkerTrainIndex.getConfig().data;
    	
    	for(var i = 0; i < data.count(); i++) {
    		if(data.object(i).Type == 'se') {
      		var Highlights = new Sky.TList();
      		var interval = new Sky.TInterval(0, data.object(i).Rep.length);
          Highlights.add({'Interval': interval, 'Style': 's0'});
          data.object(i).Children.add(Highlights);
    		}
      }
      
      NeoX.Modules.LinkerTrainIndex.Config.maxChildren = NeoX.Modules.LinkerTrainIndex.Config.maxChildren + 1;
      
      NeoX.Modules.LinkerTrainIndex.repaint();
    },
    
    deleteRepColumn: function() {
      var data = NeoX.Modules.LinkerTrainIndex.getConfig().data;
      
      for(var i = 0; i < data.count(); i++) {
      	if(data.object(i).Type == 'se') {
          data.object(i).Children.pop();
      	}
      }
      
      NeoX.Modules.LinkerTrainIndex.Config.maxChildren = NeoX.Modules.LinkerTrainIndex.Config.maxChildren - 1;
      
      NeoX.Modules.LinkerTrainIndex.repaint();
    },
    
    loadData: function(data) {
    	var dataList = NeoX.Modules.LinkerTrainIndex.getConfig().data;
    	for(var i = 0; i < data.length; i++) {
    		var CRepRecord = new NeoX.TCRepRecord(data[i].Id, data[i].Sentence, data[i].Rep, data[i].Indentation, data[i].Type);
    		
    		/*var interval = new Sky.TInterval(0, data[i].Rep.length);
        CRepRecord.Highlights.add({'Interval': interval, 'Style': 's0'});*/
    		
    		if(data[i].hasOwnProperty("Highlights") && data[i].Highlights instanceof Array && data[i].Highlights.length > 0) {
    			for(var j = 0; j < data[i].Highlights.length; j++) {
      			var interval = new Sky.TInterval(parseInt(data[i].Highlights[j].From, 10), parseInt(data[i].Highlights[j].Until, 10));
      			CRepRecord.Highlights.add({'Interval': interval, 'Style': data[i].Highlights[j].Style});
      			if(data[i].Highlights[j].Style.split("-").length > 1) {
              NeoX.Modules.LinkerTrainIndex.createCSS(data[i].Highlights[j].Style);
      			}
    			}
    		} else {
    			var interval = new Sky.TInterval(0, data[i].Rep.length);
          CRepRecord.Highlights.add({'Interval': interval, 'Style': 's0'});
    		}
    		
    		if(data[i].hasOwnProperty("Children") && data[i].Children instanceof Array && data[i].Children.length > 0) {
    			if(data[i].Children.length > NeoX.Modules.LinkerTrainIndex.Config.maxChildren) {
    				
            NeoX.Modules.LinkerTrainIndex.Config.maxChildren = data[i].Children.length;
    			} 
    		  for(var j = 0; j < data[i].Children.length; j++) {
    		    var Highlights = new Sky.TList();
    		    for(var k = 0; k < data[i].Children[j].length; k++) {
    		      var interval = new Sky.TInterval(parseInt(data[i].Children[j][k].From, 10), parseInt(data[i].Children[j][k].Until, 10));
              Highlights.add({'Interval': interval, 'Style': data[i].Children[j][k].Style});
              if(data[i].Children[j][k].Style.split("-").length > 1) {
                NeoX.Modules.LinkerTrainIndex.createCSS(data[i].Children[j][k].Style);
              }
    		    }
    		    CRepRecord.Children.add(Highlights);
    		  }
    		}
    		
    		dataList.add(data[i].Id, CRepRecord);
    	}
    },
    
    repaint: function() {
    	var data = NeoX.Modules.LinkerTrainIndex.getConfig().data;
    	
    	var html = '<div class="trainer-container">' +
        '<table class="trainer linker-list">' + 
        '<tr class="table-header">' +
        '<th width="50%">Sentence</th>' +
        '<th>Rep</th>';
        
      for(var i = 0; i < NeoX.Modules.LinkerTrainIndex.Config.maxChildren; i++) {
      	html += '<th>Rep</th>';
      }
        
      html += '</tr>';
        
      for(var i = 0; i < data.count(); i++) {
        if(data.object(i).Type == "pr") {
        	html += '<tr data-id="' + i +  '" class="row1">';
        } else {
        	html += '<tr data-id="' + i +  '" class="aproto">';
        }
        
        html += '<td>';
        html += '<div class="level-indent-wrapper">';
        for(var p = 0; p < parseInt(data.object(i).Indentation, 10) + 1; p++) {
        	html += '<div class="level-indent level' + (p % 5) + '"></div>'
        }
        html += '</div>';
        html += '<div class="content-indent">' + data.object(i).Sentence + '</div>';
        html += '</td>';
        html += NeoX.Modules.LinkerTrainIndex.repaintRow(1, data.object(i).Rep, data.object(i).Highlights);
        for(var j = 0; j < NeoX.Modules.LinkerTrainIndex.Config.maxChildren; j++) {
        	if(data.object(i).Type == "pr") {
            html += NeoX.Modules.LinkerTrainIndex.repaintRow(j + 2, "this1", new Sky.TList());
        	} else {
            html += NeoX.Modules.LinkerTrainIndex.repaintRow(j + 2, data.object(i).Rep, data.object(i).Children.object(j));
        	}
        }
        html += '</tr>';
      }
        
      html += '</table>' +
        '</div>';
        
      $(NeoX.Modules.LinkerTrainIndex.getConfig().dataContainer).html(html);
    },
    
    repaintRow: function(rowNumber, rep, highlightArray) {
    	html = '<td class="rep" data-id="' + rowNumber + '">';
      for(var j = 0; j < highlightArray.count(); j++) {
        html += '<span class="highlighted ' + highlightArray.item(j).Style + '">';
        html += NeoX.Modules.LinkerTrainIndex.repaintChars(
          rep.substr(highlightArray.item(j).Interval.From, highlightArray.item(j).Interval.Until - highlightArray.item(j).Interval.From), 
          highlightArray.item(j).Interval.From
        );
        html += '</span>';
      }
      html += '</td>';
      return html;
    },
    
    repaintChars: function(str, offset) {
    	if(!offset) offset = 0;
    	var output = '';
    	
    	for(var i =0; i < str.length; i++) {
    		output += "<span class='char' data-id='" + (i + offset) + "'>" + str[i] + "</span>";
    	}
    	
    	return output;
    },
    
    charClicked: function(e) {
      var charPos = parseInt($(this).attr("data-id"), 10);
      var repIndex = parseInt($(this).parents("tr").first().attr("data-id"), 10);
      var colIndex = parseInt($(this).parents("td").first().attr("data-id"), 10);
      var TheObject = NeoX.Modules.LinkerTrainIndex.getConfig().data.object(repIndex);
      
      if(/[a-zA-Z0-9]/.test(TheObject.Rep[charPos])) {
      	startPos = charPos;
      	stopPos = charPos;
      	
        while(startPos > 0 && /[a-zA-Z0-9]/.test(TheObject.Rep[startPos-1])) {
          startPos--;
        }
        
        if(startPos > 0 && (TheObject.Rep[startPos-1] == "." || TheObject.Rep[startPos-1] == ":")) {
          startPos--;
        }
        
        while(stopPos < TheObject.Rep.length && /[a-zA-Z0-9]/.test(TheObject.Rep[stopPos+1])) {
          stopPos++;
        }
        
        if(startPos <= stopPos) {
        	var TheInterval;
        	
          if( (NeoX.Modules.LinkerTrainIndex.getConfig().keyDown == true ) && 
             NeoX.Modules.LinkerTrainIndex.getConfig().startPos != null && 
             NeoX.Modules.LinkerTrainIndex.getConfig().currentCol == colIndex && 
             NeoX.Modules.LinkerTrainIndex.getConfig().currentRow == repIndex) 
         	{
         		if(NeoX.Modules.LinkerTrainIndex.getConfig().startPos.from > startPos) {
              TheInterval = new Sky.TInterval(startPos, NeoX.Modules.LinkerTrainIndex.getConfig().startPos.until);
         		} else {
              TheInterval = new Sky.TInterval(NeoX.Modules.LinkerTrainIndex.getConfig().startPos.from, stopPos + 1);
         		}
        	} else {
        		TheInterval = new Sky.TInterval(startPos, stopPos + 1);
        		NeoX.Modules.LinkerTrainIndex.getConfig().currentCol = colIndex;
            NeoX.Modules.LinkerTrainIndex.getConfig().currentRow = repIndex;
            NeoX.Modules.LinkerTrainIndex.getConfig().startPos = {
              "from": startPos,
              "until": stopPos + 1 
            };
        	}
        	
        	if(colIndex == 1) {
            NeoX.Modules.LinkerTrainIndex.getConfig().data.object(repIndex).highlight(TheInterval, NeoX.Modules.LinkerTrainIndex.getConfig().selectedStyle);
        	} else {
        		NeoX.Modules.LinkerTrainIndex.getConfig().data.object(repIndex).highlight(TheInterval, NeoX.Modules.LinkerTrainIndex.getConfig().selectedStyle, colIndex - 2);
        	}

          NeoX.Modules.LinkerTrainIndex.repaint();
        } else {
        	throw "StartBiggerThenStopException";
        }
      }
      
    },
    
    createCSS: function(AStyle) {
    	var styles = AStyle.split("-");
      
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
      
      $("head").append("<style type='text/css'> ." + AStyle + " { " + css1 + "  " + css2 + "  " + css3 + "  " + css4 + "  " + css5 + "  " + css6 + " } </style>");
      
    }

  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerTrainIndex", MLinkerTrainIndex_Implementation);
