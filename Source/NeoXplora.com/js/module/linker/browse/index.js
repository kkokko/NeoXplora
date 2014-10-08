var MLinkerBrowseIndex_Implementation = {
  extend: "NeoX.TBaseObject",
  type: "module",
  
  construct: function(settings) {
    this.setConfig($.extend(this.getConfig(), settings));
    this.getConfig().data = new Sky.TStringList();
  },
  
  properties: {
    Config: {
    	Buttons: {
        loadPageBtn: '.goToPage',
        firstPage: '.firstBtn',
        previousPage: '.previousBtn',
        nextPage: '.nextBtn',
        lastPage: '.lastBtn',
        retrainBtn: '.retrainBtn'
      },
      moduleScript: 'browse.php',
      moduleType: 'linker',
      dataContainer: '.boxContent',
      paginationContainer: '.boxPagination',
      data: null,
      styles: ['#AEABAB', '#ADB9CA', '#BDD7EE', '#F7CBAC', '#DBDBDB', '#FEE599', '#B4C6E7', '#C5E0B3', '#757070', '#8496B0', '#9CC3E5',
                '#F4B183', '#C9C9C9', '#FFD965', '#8EAADB', '#A8D08D', '#3A3838', '#323F4F', '#2F75B5', '#C55A11', '#7B7B7B', '#BF9000',
                '#2F5496', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#00FFFF', '#FF00FF', '#6633CC', '#663300', '#880000']
    }
  },
  
  methods: {
    
    init: function() {
      NeoX.Modules.LinkerBrowseIndex.hookEvents();
      NeoX.Modules.LinkerBrowseIndex.load();
      var styles = '<style type="text/css">';
      for( var i = 0; i < NeoX.Modules.LinkerBrowseIndex.getConfig().styles.length; i++ ) {
        styles += ".s" + (i + 1) + " { ";
        styles += "border: 2px solid " + NeoX.Modules.LinkerBrowseIndex.getConfig().styles[i] + "; ";
        styles += "background-color: " + NeoX.Modules.LinkerBrowseIndex.getConfig().styles[i] + "; ";
        styles += " } ";
      }
      styles += "</style>";
      $(document).ready(function() {
        $("head").append(styles);
      });
    },
    
    hookEvents: function() {
      NeoX.Modules.LinkerBrowseIndex.hookEvent("click", NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.loadPageBtn, NeoX.Modules.LinkerBrowseIndex.goToPage);
      NeoX.Modules.LinkerBrowseIndex.hookEvent('click', NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.firstPage, NeoX.Modules.LinkerBrowseIndex.goToFirst);
      NeoX.Modules.LinkerBrowseIndex.hookEvent('click', NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.previousPage, NeoX.Modules.LinkerBrowseIndex.goToPrevious);
      NeoX.Modules.LinkerBrowseIndex.hookEvent('click', NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.nextPage, NeoX.Modules.LinkerBrowseIndex.goToNext);
      NeoX.Modules.LinkerBrowseIndex.hookEvent('click', NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.lastPage, NeoX.Modules.LinkerBrowseIndex.goToLast);
      NeoX.Modules.LinkerBrowseIndex.hookEvent('click', NeoX.Modules.LinkerBrowseIndex.getConfig().Buttons.retrainBtn, NeoX.Modules.LinkerBrowseIndex.retrain);
    },
    
    load: function() {
      NeoX.Modules.LinkerBrowseRequests.load(1);    	
    },
    
    goToPage: function() {
      var page = parseInt($(this).html(), 10);
      NeoX.Modules.LinkerBrowseRequests.load(page);
      $('html, body').animate({
          scrollTop: $('#content').offset().top
      }, 50);
    },
    
    goToFirst: function() {
      NeoX.Modules.LinkerBrowseRequests.load(1);
    },
    
    goToPrevious: function() {
      var previousPage = parseInt($(".currentPage").html(), 10) - 1;
      if(previousPage > 0) {
        NeoX.Modules.LinkerBrowseRequests.load(previousPage);
      }
    },
    
    goToNext: function() {
      var nextPage = parseInt($(".currentPage").html(), 10) + 1;
      var lastPage = parseInt($(".goToPage").last().html());
      if(nextPage <= lastPage) {
        NeoX.Modules.LinkerBrowseRequests.load(nextPage);
      }
    },
    
    goToLast: function() {
      var lastPage = parseInt($(".goToPage").last().html(), 10);
      NeoX.Modules.LinkerBrowseRequests.load(lastPage);
    },
    
    retrain: function() {
    	var pageId = parseInt($(".pageId").val(), 10);
      NeoX.Modules.LinkerBrowseRequests.retrain(pageId);
    },
    
    loadData: function(data) {
    	var dataList = NeoX.Modules.LinkerBrowseIndex.getConfig().data;
    	for(var i = 0; i < data.length; i++) {
    		var CRepRecord = new NeoX.TCRepRecord(data[i].Id, data[i].Sentence, data[i].Rep, data[i].Indentation, data[i].Type);
    		if(CRepRecord.Type == 'pr') {
    		  CRepRecord.Style = data[i].Style;
    		}
    		/*var interval = new Sky.TInterval(0, data[i].Rep.length);
        CRepRecord.Highlights.add({'Interval': interval, 'Style': 's0'});*/
    		
    		if(data[i].hasOwnProperty("Highlights") && data[i].Highlights instanceof Array && data[i].Highlights.length > 0) {
    			for(var j = 0; j < data[i].Highlights.length; j++) {
      			var interval = new Sky.TInterval(parseInt(data[i].Highlights[j].From, 10), parseInt(data[i].Highlights[j].Until, 10));
      			CRepRecord.Highlights.add({'Interval': interval, 'Style': data[i].Highlights[j].Style});
      			if(data[i].Highlights[j].Style.split("-").length > 1) {
              NeoX.Modules.LinkerBrowseIndex.createCSS(data[i].Highlights[j].Style);
      			}
    			}
    		} else {
    			var interval = new Sky.TInterval(0, data[i].Rep.length);
          CRepRecord.Highlights.add({'Interval': interval, 'Style': 's0'});
    		}
    		
    		if(data[i].hasOwnProperty("Children") && data[i].Children instanceof Array && data[i].Children.length > 0) {
    			if(data[i].Children.length > NeoX.Modules.LinkerBrowseIndex.Config.maxChildren) {
    				
            NeoX.Modules.LinkerBrowseIndex.Config.maxChildren = data[i].Children.length;
    			} 
    		  for(var j = 0; j < data[i].Children.length; j++) {
    		    var Highlights = new Sky.TList();
    		    for(var k = 0; k < data[i].Children[j].length; k++) {
    		      var interval = new Sky.TInterval(parseInt(data[i].Children[j][k].From, 10), parseInt(data[i].Children[j][k].Until, 10));
              Highlights.add({'Interval': interval, 'Style': data[i].Children[j][k].Style});
              if(data[i].Children[j][k].Style.split("-").length > 1) {
                NeoX.Modules.LinkerBrowseIndex.createCSS(data[i].Children[j][k].Style);
              }
    		    }
    		    CRepRecord.Children.add(Highlights);
    		  }
    		}
    		
    		dataList.add(data[i].Id, CRepRecord);
    	}
    },
    
    repaint: function() {
    	var data = NeoX.Modules.LinkerBrowseIndex.getConfig().data;
    	
    	var html = '<div class="trainer-container">' +
        '<table class="trainer linker-list">' + 
        '<tr class="table-header">' +
        '<th width="50%">Sentence</th>' +
        '<th>Rep</th>';
        
      for(var i = 0; i < NeoX.Modules.LinkerBrowseIndex.Config.maxChildren; i++) {
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
        if(data.object(i).Type == 'se') {
          html += NeoX.Modules.LinkerBrowseIndex.repaintRow(1, data.object(i).Rep, data.object(i).Highlights);
        } else {
        	html += NeoX.Modules.LinkerBrowseIndex.repaintProtoRow(data.object(i).Style); 
        }
        for(var j = 0; j < NeoX.Modules.LinkerBrowseIndex.Config.maxChildren; j++) {
        	if(data.object(i).Type == "pr") {
            html += NeoX.Modules.LinkerBrowseIndex.repaintRow(j + 2, "", new Sky.TList());
        	} else {
            html += NeoX.Modules.LinkerBrowseIndex.repaintRow(j + 2, data.object(i).Rep, data.object(i).Children.object(j));
        	}
        }
        html += '</tr>';
      }
        
      html += '</table>' +
        '</div>';
        
      $(NeoX.Modules.LinkerBrowseIndex.getConfig().dataContainer).html(html);
    },
    
    repaintRow: function(rowNumber, rep, highlightArray) {
    	html = '<td class="rep" data-id="' + rowNumber + '">';
      for(var j = 0; j < highlightArray.count(); j++) {
        html += '<span class="highlighted ' + highlightArray.item(j).Style + '">';
        html += NeoX.Modules.LinkerBrowseIndex.repaintChars(
          rep.substr(highlightArray.item(j).Interval.From, highlightArray.item(j).Interval.Until - highlightArray.item(j).Interval.From), 
          highlightArray.item(j).Interval.From
        );
        html += '</span>';
      }
      html += '</td>';
      return html;
    },
    
    repaintProtoRow: function(style) {
      html = '<td class="rep" data-id="1">';
      html += '<span class="char protoRep highlighted ' + style + '">this1</span>';
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
        var colorCode = NeoX.Modules.LinkerBrowseIndex.getConfig().styles[styles[i]];
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

Sky.Class.Define("NeoX.Modules.LinkerBrowseIndex", MLinkerBrowseIndex_Implementation);
