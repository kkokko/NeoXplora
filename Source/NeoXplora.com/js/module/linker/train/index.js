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
        addRepBtn: '.addRepColumn'
      },
      moduleScript: 'train.php',
      moduleType: 'linker',
      dataContainer: '.boxContent',
      selectedStyle: 's0',
      data: null,
      charSelector: '.char',
      startPos: null,
      currentCol: null,
      currentRow: null,
      styles: ['#AEABAB', '#ADB9CA', '#BDD7EE', '#F7CBAC', '#DBDBDB', '#FEE599', '#B4C6E7', '#C5E0B3', '#757070', '#8496B0', '#9CC3E5',
                '#F4B183', '#C9C9C9', '#FFD965', '#8EAADB', '#A8D08D', '#3A3838', '#323F4F', '#2F75B5', '#C55A11', '#7B7B7B', '#BF9000',
                '#2F5496', '#538135', '#171616', '#222A35', '#1E4E79', '#833C0B', '#525252', '#7F6000', '#1F3864', '#375623']
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
      NeoX.Modules.LinkerTrainIndex.hookEvent("click", NeoX.Modules.LinkerTrainIndex.getConfig().charSelector, NeoX.Modules.LinkerTrainIndex.charClicked);
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
    		var Highlights = new Sky.TList();
    		var interval = new Sky.TInterval(0, data.object(i).Rep.length);
        Highlights.add({'Interval': interval, 'Style': 's0'});
        data.object(i).Children.add(Highlights);
      }
      
      NeoX.Modules.LinkerTrainIndex.repaint();
    },
    
    loadData: function(data) {
    	var dataList = NeoX.Modules.LinkerTrainIndex.getConfig().data;
    	for(var i = 0; i < data.length; i++) {
    		var CRepRecord = new NeoX.TCRepRecord(data[i].Id, data[i].Sentence, data[i].Rep);
    		var interval = new Sky.TInterval(0, data[i].Rep.length);
    		CRepRecord.Highlights.add({'Interval': interval, 'Style': 's0'});
    		dataList.add(data[i].Id, CRepRecord);
    	}
    },
    
    repaint: function() {
    	var data = NeoX.Modules.LinkerTrainIndex.getConfig().data;
    	
    	var html = '<div class="trainer-container">' +
        '<table class="trainer">' + 
        '<tr class="table-header">' +
        '<th>Sentence</th>' +
        '<th>Rep</th>';
        
      for(var i = 0; i < data.object(0).Children.count(); i++) {
      	html += '<th>Rep</th>';
      }
        
      html += '</tr>';
        
      for(var i = 0; i < data.count(); i++) {
        html += '<tr data-id="' + i +  '">';
        html += '<td>' + data.object(i).Sentence +  '</td>';
        
        html += NeoX.Modules.LinkerTrainIndex.repaintRow(1, data.object(i).Rep, data.object(i).Highlights);
        for(var j = 0; j < data.object(i).Children.count(); j++) {
          html += NeoX.Modules.LinkerTrainIndex.repaintRow(j + 2, data.object(i).Rep, data.object(i).Children.object(j));
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
    	if ( e.ctrlKey || e.metaKey ) {
    		e.preventDefault();
    	}
    	
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
        	
          if(( e.ctrlKey || e.metaKey ) && 
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
      
    }

  }
  
};

Sky.Class.Define("NeoX.Modules.LinkerTrainIndex", MLinkerTrainIndex_Implementation);
