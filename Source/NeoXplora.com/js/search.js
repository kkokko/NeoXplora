
	$(function(){
		performPreSearch();
		bindSearchTrigger();
		bindPageNavigation();
	});
	
	function performPreSearch(){
		var preRequestQuery = getParameterByName("q");
		if(preRequestQuery!=null && preRequestQuery!=""){
			$(".search_bar input[type=text]").val(preRequestQuery);
			makeGUITransition();
		}	
	}
	
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	var performSearchTimeOut = null;
	function bindSearchTrigger(){
		stopSearchTimeout();
		$("#submitSearch").click(function(e){
			searchQuery = $("#searchInput").val();
			if(searchQuery!=""){
				currentPageOffset = 0;
				getSearchResults(searchQuery);
			}
		});
		$("#searchInput").on('keyup',function(e){
			searchQuery = $("#searchInput").val();
			clearTimeout(performSearchTimeOut);
			if(e.keyCode==13 || e.keyCode==32){
				currentPageOffset = 0;
				getSearchResults(searchQuery);
			}else if(searchQuery.length>2){ // average word is 5 letters
				currentPageOffset = 0;
				performSearchTimeOut = setTimeout('getSearchResults(searchQuery)',500);
			}
		});
	}
	
	function bindPageNavigation(){
		$('.pagination a').click(function(e){
			var clickedPage = $(e.currentTarget);
			currentPageOffset = parseInt(clickedPage.attr("data-page"))-1;
			getSearchResults($("#searchInput").val());
			$('html,body').animate({ scrollTop: 0 }, 'fast');
			return false;
		});
	}
	var currentPageOffset = 0;
	function getSearchResults(SearchQuery){
		stopSearchTimeout();
		if(SearchQuery!=""){
			toggleSpinner(true);
			$.ajax(
			{
				url:'search.php',
				method:'POST',
				data:{action:'getSearchResults',q:SearchQuery,page:currentPageOffset+1}
			}).done(function(data){
				makeGUITransition();
				$("#searchResults").html(data);
				toggleSpinner(false);
				bindPageNavigation();
			});
			
			var state = {
			  "search": true
			};
			var getParams = "?q="+SearchQuery;
			if(currentPageOffset!=0) getParams+=("&page="+(currentPageOffset+1));
			history.pushState(state, "", getParams);
		}
	}
	
	function toggleSpinner(toggle){
		if(toggle){
			$('.search_bar input[type=text]').addClass('showSpinner');
		}else{
			$('.search_bar input[type=text]').removeClass('showSpinner');
		}
	}
	
	function stopSearchTimeout(){
		if(performSearchTimeOut!=null){
			window.clearTimeout(performSearchTimeOut);
		}
		performSearchTimeOut = null;
	}
	
	var guiTransitionMade = false;
	function makeGUITransition(){
		if(!guiTransitionMade){
			$('#search_box').css('margin','5px');
			var logoIMG = $('#logo_box_wrp div img');
			//logoIMG.css('height','30px');
			logoIMG.css('float','left');
			logoIMG.css('margin','8px');
			logoIMG.addClass('transitedLogo');
			$('.search_bar').css('padding-top','15px');
			$('.search_bar input[type=text]').addClass('transited');
			$('#searchResults').css('background-color','#FFF');
			
			$('#search_box').css('width','96%');
			$('#search_box').prepend(logoIMG);
			
			//$('#footer').css('position','fixed');
			$('#footer').css('background-color',$("body").css('background-color'));
			guiTransitionMade = true;
		}
		
	}
