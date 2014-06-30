<?php
ini_set('default_charset', 'UTF-8');
header("content-type: text/html; charset=utf-8");
/**

 * Login

 *

 * @package CMS Pro

 * @author wojoscripts.com

 * @copyright 2011

 * @version $Id: login.php, v2.00 2011-04-20 10:12:05 gewa Exp $

 */

define("_VALID_PHP", true);

require_once ("init.php");

//$con=mysqli_connect("127.0.0.1","root","","db179668_ai2.sql");
?>

<?php
  include (THEMEDIR . "/header.php");
?>

<script type="text/javascript">
  history.navigationMode = 'compatible';
  
  jQuery(document).ready(function($) {

    var i;

    $(document).on('click', '#container .pagination li.active', (function() {

      var page = $(this).attr('p');

      //$(".statsresults").html("");
      
      $("#ajaxLoading").val('false');

      loadData(page, -1);

      //            fnstatsCalculator(page);

    }));

    $(document).on('click', '#backbtn', (function(e) {

      var arr = $('#pg').val().split(",");
      arr.splice(-1, 1);
      //remove last value
      var value = arr.join(",");
      // csv file
      if (arr.length == 0) {
        $('#pg').val(1);
      } else {
        $('#pg').val(value);
      }
      var lastEl = arr[arr.length - 1];
      //alert(lastEl);
      $("#ajaxLoading").val('false');
      loadData(lastEl.toString(), -1);

    }));

    $(document).on('click', '.sentnc span.guess', (function(e) {
      sid = $(this).attr('guessid');

      $.ajax({

        type : "POST",

        url : "operations.php",

        data : {
          sentenceID : sid,
          type : 'getStoryID'
        },

        success : function(html) {

          //var pathname = window.location.pathname;
          //window.open(pathname+'?id='+html, 'window name', 'window settings');

          //alert(html);
          pg = $('#pg').val();
          $('#pg').val(pg + ',' + html);
          $("#ajaxLoading").val('false');
          loadData(html, -1);

        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          alert("Status: " + textStatus);
          alert("Error: " + errorThrown);
        }
      });

    }));


    $(document).on('click', '.senrep span.guess', (function(e) {
      var sid = $(this).parent('td').parent('tr').attr('id');  
      sid = parseInt(sid.replace('tr', ''));   
            
      guess = $(this).html();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          sentenceID : sid,
          action : 'replaceRep',
          guess: guess
        },
        success : function(html) {    
          var storyId = $('#story-id').val();      
          loadData(-1, storyId);
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          alert("Status: " + textStatus);
          alert("Error: " + errorThrown);
        }
      });
    }));
    
    $(document).on('click', '.creprow span.guess', (function(e) {
      var sid = $(this).parent('td').parent('tr').attr('id');  
      sid = parseInt(sid.replace('tr', ''));   
      
      var guess = $(this).html();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          sentenceID : sid,
          action : 'replaceCRep',
          guess: guess
        },
        success : function(html) {
          var storyId = $('#story-id').val();      
          loadData(-1, storyId);
        }
      });
    }));
    
    $(document).on('click', '.sreprow span.guess', (function(e) {
      var sid = $(this).parent('td').parent('tr').attr('id');  
      sid = parseInt(sid.replace('tr', ''));   
            
      guess = $(this).html();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          sentenceID : sid,
          action : 'replaceSRep',
          guess: guess
        },
        success : function(html) {    
          var storyId = $('#story-id').val();      
          loadData(-1, storyId);
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          alert("Status: " + textStatus);
          alert("Error: " + errorThrown);
        }
      });
    }));
    
    $(document).on('click', '#container .pagination li.nextactive', (function() {

      var page = $(this).attr('p');

      //	alert('Page Change');
      pg = $('#pg').val();
      $('#pg').val(pg + ',' + page);

      //$(".statsresults").html("");

      //$('#percent').val($('#container .data li').attr('data-value'));

      fnstatsCalculator(page, $('#container .data li').attr('data-value'));

    }));

    $(document).on('click', '#container .pagination li.prevactive', (function() {

      var page = $(this).attr('p');

      //	alert('Page Change');

      pg = $('#pg').val();
      $('#pg').val(pg + ',' + page);

      //$(".statsresults").html("");

      //$('#percent').val($('#container .data li').attr('data-value'));

      fnstatsCalculator(page, $('#container .data li').attr('data-value'));

    }));

    /*Chat Comments*/

    $(".chat-in form").submit(function(e) {

      //var d = new Date();

      // var time = d.getHours() + ":" + d.getMinutes();

      var inp = $("input[type='text']", this);

      var msg = inp.val();

      var img = 'images/avatar1_50.jpg';

      inp.val("");

      var tpl = $('<div class="chat-conv" style="display:none;">' + '<div class="c-avatar usertitle">Reply</div>' + '<div class="c-bubble">' + '<div class="msg">' + msg + '</div>' + '<span></span>' + '</div>' + '</div>');

      var tpll = $('<div class="chat-conv autorply rplyone" style="display:none;">' + '<div class="c-avatar defoalt">NAS</div>' + '<div class="c-bubble">' + '<div class="msg">Auro Reply Text Here 1</div>' + '<span></span>' + '</div>' + '</div>');

      var tplll = $('<div class="chat-conv autorply rplytwo" style="display:none;">' + '<div class="c-avatar defoalt">NAS</div>' + '<div class="c-bubble">' + '<div class="msg">Auro Reply Text Here 2</div>' + '<span></span>' + '</div>' + '</div>');

      var tpllll = $('<div class="chat-conv autorply rplythree" style="display:none;">' + '<div class="c-avatar defoalt">NAS</div>' + '<div class="c-bubble">' + '<div class="msg">Auro Reply Text Here 3</div>' + '<span></span>' + '</div>' + '</div>');

      var tplllll = $('<div class="chat-conv autorply rplyfour" style="display:none;">' + '<div class="c-avatar defoalt">NAS</div>' + '<div class="c-bubble">' + '<div class="msg">Auro Reply Text Here 4</div>' + '<span></span>' + '</div>' + '</div>');

      $(this).parents(".chat-wi").find(".chat-content").css("transition", "all 1s ease");

      $(this).parents(".chat-wi").find(".chat-content").append(tpl);

      var numItems = $('.autorply').length

      //alert(numItems);

      if (numItems == 0) {

        $(this).parents(".chat-wi").find(".chat-content").append(tpll);

        setTimeout(function() {

          $('.rplyone').show();

        }, 2000);

      } else if (numItems == 1) {

        $(this).parents(".chat-wi").find(".chat-content").append(tplll);

        setTimeout(function() {

          $('.rplytwo').show();

        }, 2000);

      } else if (numItems == 2) {

        $(this).parents(".chat-wi").find(".chat-content").append(tpllll);

        setTimeout(function() {

          $('.rplythree').show();

        }, 2000);

      } else if (numItems == 3) {

        $(this).parents(".chat-wi").find(".chat-content").append(tplllll);

        setTimeout(function() {

          $('.rplyfour').show();

        }, 2000);

      }

      tpl.slideDown();

      $(".nscroller").animate({
        scrollBottom : 0
      }, '500', 'swing');

      e.preventDefault();

    });

    $("#tabs").tabs({

      select : function(evt, ui) {

        fnstatsCalculator($("#story-id").val(), $('#percent').val());

      },

      load : function(event, ui) {

        fnstatsCalculator($("#story-id").val(), $('#percent').val());

      }
    });

    //  $("#tabs").tabs();

    $(document).on('click', '.updateGuess', (function() {
      var sentenceID = $(this).attr('rel');
      $('#matchsentc1' + sentenceID).html('<img src="uploads/loading.gif">');
      $.ajax({
        type : "POST",
        url : "NeoShared/Server/PredictSingle.php",
        dataType: "json",
        data : {
          'sentenceID': sentenceID
        },
        async: false,
        success: function(json) {
          console.log("#tr" + sentenceID);
          var sentencesCell = $("#tr" + sentenceID + " .sentnc");
          var repguessCell = $("#tr" + sentenceID + " .senrep");
          var crepguessCell = $("#tr" + sentenceID + " .creprow");
          var srepguessCell = $("#tr" + sentenceID + " .sreprow");
          
          sentencesCell.find(".guess").remove();
          repguessCell.find(".guess").remove();
          crepguessCell.find(".guess").remove();
          srepguessCell.find(".guess").remove();
          sentencesCell.find("br").remove();
          repguessCell.find("br").remove();
          crepguessCell.find("br").remove();
          srepguessCell.find("br").remove();
          
          sentencesCell.append("<br/>" + json['sentencesData']);
          repguessCell.append("<br/>" + json['repguessesData']);
          crepguessCell.append("<br/>" + json['crepguessData']);
          srepguessCell.append("<br/>" + json['srepguessData']);
        }
      });

    }));

    $('.statsCalculator').click(function() {

      var totalSt = $('#total-data').val();

      var storyid = $('#story-id').val();

      var percent = $('#percent').val();

      //	alert('storid'+storyid+' percent'+percent);

      fnstatsCalculator(storyid, percent);

    });

    function fnstatsCalculator(storyid, percent) {

      //            var percent = $('#percent').val();

      /*$.ajax({

        type : "POST",

        url : "operations.php",

        data : {
          storyId : storyid,
          type : 'stats',
          persnt : percent
        },

        success : function(html) {

          //alert(html);
          $(".statsresults").html(html);

          //					alert('HELLO');

        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          alert("Status: " + textStatus);
          alert("Error: " + errorThrown);
        }
      });*/

    }

    function fndataTab(storyid) {

      //            var percent = $('#percent').val();

      $.ajax({

        type : "POST",

        url : "operations.php",

        data : {
          type : 'datatab'
        },

        success : function(html) {

          $("#tabs-6").html(html);

        }
      });

    }

    var ajaxLoading = false;

    $('.submitQA').live("click",function() {
      var storyId = $('#story-id').val();
      var question = $(this).parent().parent().find(".new-value-q").val();
      var answer = $(this).parent().parent().find(".new-value-a").val();
      var rule = $(this).parent().parent().find(".new-value-r").val();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          storyId: storyId,
          type: 'addQA',
          question: question,
          answer: answer,
          rule: rule
        },
        success : function(result) {
          loadData(parseInt($(".pagination .currentpage_input").val(), 10));
        }
      }); 
    });
    
    $('#searchButton').live("click",function() {
      var query = $('#searchQuery').val();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          type: 'searchStories',
          query: query
        },
        success : function(html) {
          $("#tabs-8 #search-row").html(html);
        }
      }); 
    });
    
    $('.category_selection').live("change", function() {
      var newCat = $(this).val();
      var storyId = $("#story-id").val();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          type: 'changeCat',
          newCat: newCat,
          storyId: storyId
        },
        success : function(html) {
          loadData('-1', storyId);
        }
      });
    });
    
    

    $('#listAllButton').live("click",function() {
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          type: 'searchStories',
          query: ''
        },
        success : function(html) {
          $("#tabs-8 #search-row").html(html);
        }
      }); 
    });

    $(document).on('keypress', '#searchQuery', (function(e) {
      if (e.which === 13) {
        var query = $(this).val();
        $.ajax({
          type : "POST",
          url : "operations.php",
          data : {
            type: 'searchStories',
            query: query
          },
          success : function(html) {
            $("#tabs-8 #search-row").html(html);
          }
        }); 
      }
    }));

    $('tr.searchrow td').live("click",function() {
      var id = $(this).parent().attr("id");
      id = id.replace("searchrow", "");
      loadData(parseInt(id));
    });

    $('#tabs-2 .updateREP').click(function() {
      var storyId = $('#story-id').val();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          storyId: storyId,
          type: 'updateREP'
        },
        success : function(result) {
          loadData(parseInt($(".pagination .currentpage_input").val(), 10));
        }
      });       
    });

    $('#tabs-2 .updateCREP').click(function() {
      var storyId = $('#story-id').val();
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          storyId: storyId,
          type: 'updateCREP'
        },
        success : function(result) {
          loadData(parseInt($(".pagination .currentpage_input").val(), 10));
        }
      }); 
    });

    $('#tabs-2 .updateAll').click(function() {

      var totalSt = $('#total-data').val();

      $('#total-count').val(totalSt);

      i = parseInt($('#total-count').val());

      var storyid = $('#story-id').val();

      var rel = $('#upd' + i).attr('rel');
      
      
       $("#confirm").dialog({

        resizable : false,

        height : 140,

        title : "This functionality has been disabled",

        modal : true,

        buttons : {

          "Ok" : function() {

            $(this).dialog("close");

          }
        }

      });
      <?php 
/*
      $("#confirm").dialog({

        resizable : false,

        height : 140,

        title : "This will take about 60 seconds",

        modal : true,

        buttons : {

          "Yes" : function() {

            var totalSt = $('#total-data').val();

            $('#total-count').val(totalSt);

            $('#loder').show();
            
            var storyid = $('#story-id').val();
            
            var tig = setInterval(function() {

              i = parseInt($('#total-count').val());

              var rel = $('#upd' + i).attr('rel');

              var percent = $('#percent').val();
              
              if ((i !== 'NaN') && (i > 0)) {

                //start the ajax

                $.ajax({

                  type : "POST",

                  url : "get_boxoption.php",

                  async : false,

                  data : {
                    entry_id : i,
                    entry_cat : storyid,
                    sid : rel,
                    persnt : percent
                  },

                  success : function(result_msg) {

                    // alert(result_msg);

                    var guesses = result_msg.split(';');

                    $('#1gr' + i).html(guesses[1]);

                    $('#2gr' + i).html(guesses[2]);

                    i = i - 1;
                    
                    $('#total-count').val(i);

                  }
                });

                //  $('#loder').hide();

                // }

              } else {
                
                clearInterval(tig);

                $('#loder').hide();

                loadData(parseInt($(".pagination .currentpage_input").val(), 10));

              }

            }, 100);

            $(this).dialog("close");

          },

          Cancel : function() {

            $(this).dialog("close");

          }
        }

      });
      */ ?>
    });

    $(document).on('click', '.td-edit', (function(e0) {
      e0.preventDefault();
        var thisObj = $(this);
        var olddata = thisObj.html();
        olddata = escapeHtml(olddata);
        if(olddata == "-") olddata = "";
        var newDom = '<input type="text" value="' + olddata + '"  class="new-value" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';
        thisObj.parent('td').html(newDom);
    }));

    $(document).on('click', '.updateEdit', function(e0) {
      e0.preventDefault();
      var caption = $(this).html();
      caption = caption.trim();
      if(caption == "Edit") {
        $('#editAllMode').val('1');
        $(this).html("Save");
        $('#tabs-2 .td-edit').each(function(i) {
          var thisObj = $(this);
          var olddata = thisObj.html();
          olddata = escapeHtml(olddata);
          if(olddata == "-") olddata = "";
          var newDom = '<input type="text" value="' + olddata + '"  class="new-value" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';
          thisObj.parent('td').html(newDom);
        });
        $('#tabs-2 .td-crep').each(function(i) {
          var thisObj = $(this);
          var olddata = thisObj.siblings('span').html();
          var newDom = '<input type="text"  value="' + olddata + '" class="new-crep" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';
          thisObj.parent('td').html(newDom);
        });
      } else {
        $('#editAllMode').val('0');
        var storyId = $('#story-id').val();
        $(this).html("Edit");
        var sentences = [];
        $('.tab2sentencerow').each(function(i) {
          var sentenceID = $(this).attr('id');
          sentenceID = parseInt(sentenceID.replace("tr", ""), 10);
          var sentence = $(this).find('.sentnc .new-value').val();
          var representation = $(this).find('.senrep .new-value').val();
          var context_rep = $(this).find('.new-crep').val();
          var semantic_rep = $(this).find('.semanticreps .new-value').val();
          sentences.push({
            'sentenceID': sentenceID,
            'sentence': sentence,
            'representation': representation,
            'context_rep': context_rep,
            'semantic_rep': semantic_rep
          });
        });
        $.ajax({
          type : "POST",
          url : "operations.php",
          data : {
            type: 'updateEdit',
            sentences: sentences,
            storyId: storyId
            
          },
          success : function() {
            loadData(parseInt($(".pagination .currentpage_input").val(), 10));
          }
        });
      }
    });



    function escapeHtml(text) {
      return text
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    $(document).on('focusout', '.new-value', (function(e1) {
      if($('#editAllMode').val() == 0) { 
        e1.preventDefault();
  
        //			alert("newvalue");
  
        var thisObj = $(this);
  
        // var type = thisObj.parent('td').attr('class');
  
        var parentA = thisObj.parent('td');
  
        var type = parentA.attr('class');
  
        var storyid = $("#story-id").val();
  
        var newData = thisObj.val();
  
        var santanceId = parentA.parent('tr').attr('id');
  
        santanceId = parseInt(santanceId.replace('tr', ''));
  
        // var parent = thisObj.parent('td');
  
        parentA.html('\'<img src="uploads/loading.gif">\'');
  
        if (parentA.hasClass('sentnc')) {
  
          //	alert('sentnc');
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santanceId,
              sentnc : newData
            },
  
            success : function() {
  
              var newDom1 = '<span class="td-edit">' + newData + '</span>';
  
              parentA.html(newDom1);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
  
        } else if (parentA.hasClass('senrep')) {
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santanceId,
              rep : newData
            },
  
            success : function() {
  
              var newDom = '<span class="td-edit">' + newData + '</span>';
  
              parentA.html(newDom);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
  
        } else if (parentA.hasClass('sreprow')) {
 
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                sentenceId : santanceId,
                srep : newData,
                type: 'editSRep'
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newData + '</span>';
  
                parentA.html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parentA.hasClass('quest')) {
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santanceId,
              quest : newData
            },
  
            success : function() {
  
              var newDom = '<span class="td-edit">' + newData + '</span>';
  
              parentA.html(newDom);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
        } else if (parentA.hasClass('ans')) {
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santanceId,
              ans : newData
            },
  
            success : function() {
  
              var newDom = '<span class="td-edit">' + newData + '</span>';
  
              parentA.html(newDom);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
  
        } else if (parentA.hasClass('qrule')) {
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santanceId,
              qrule : newData
            },
  
            success : function() {
  
              var newDom = '<span class="td-edit">' + newData + '</span>';
  
              parentA.html(newDom);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
  
        } else if (parentA.hasClass('semanticreps')) {
          $.ajax({
            type : "POST",
            url : "operations.php",
            data : {
              santanceId : santanceId,
              semanticrep : newData
            },
            success : function() {
              var newDom = '<span class="td-edit">' + newData + '</span>';
              parentA.html(newDom);
              $("#ajaxLoading").val('false');
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
            }
          });
        }
      }
    }));

    $('.update-crep-row').live('mouseover mouseout', function(event) {
      if (event.type == 'mouseover') {
        $(this).css('background-image', 'url("assets/right-arrow.png")');
      } else {
        $(this).css('background-image', 'none');
      }
    });

    $('.update-crep-row').live('click', function(event) {
      var sId = $(this).attr("id");
      sId = parseInt(sId.replace('thegr', ''), 10);
      $.ajax({
        type : "POST",
        url : "operations.php",
        data : {
          sentenceId: sId,
          type: 'copyRepToCrep'
        },
        success : function() {
          loadData(parseInt($(".pagination .currentpage_input").val(), 10));
        }
      });
    });

    ////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.td-crep', (function(e0) {
      var thisObj = $(this);

      var olddata = thisObj.siblings('span').html();

      var newDom = '<input type="text"  value="' + olddata + '" class="new-crep" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';

      thisObj.parent('td').html(newDom);

    }));

    /////////////////////////////////////////////////////////////////////////////////

    $(document).on('focusout', '.new-crep', (function(e1) {
      if($('#editAllMode').val() == 0) {
  
        var thisObj = $(this);
  
        var newRep = thisObj.val();
  
        var santanceId = thisObj.parent('td').parent('tr').attr('id');
  
        santanceId = parseInt(santanceId.replace('tr', ''));
  
        thisObj.parent('td').html('\'<img src="uploads/loading.gif">\'');
  
        var parent = thisObj.parent('td');
        
        var storyid = $("#story-id").val();
  
        $.ajax({
  
          type : "POST",
  
          url : "operations.php",
  
          data : {
            santanceId : santanceId,
            crep : newRep
          },
  
          success : function() {
  
            var newDom = '<span>' + newRep + '</span><span class="td-crep">' + newRep + '</span>';
  
            parent.html(newDom);
  
            $("#ajaxLoading").val('false');
  
            loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
          }
          
        });
  
        //updateSentence(santanceId, newData);
      }
    }));

    ///////////////////////////////////////////////////////////////////////////////////

    $(document).on('keypress', '.currentpage_input', (function(e) {
      if (e.which === 13) {
        var total = parseInt($('span.total').attr('a'));
        var page = parseInt($(this).val());
        if(page >= 1 && page <= total) {
          loadData(page);
        }
      }
    }));

    $(document).on('keypress', '.new-crep', (function(e) {
      if($('#editAllMode').val() == 0) {
        var thisObj1 = $(this);
  
        var newRep1 = thisObj1.val();
  
        var santid = thisObj1.parent('td').parent('tr').attr('id');
  
        var parent = thisObj1.parent('td');
        
        var storyid = $("#story-id").val();
  
        if (e.which === 13) {
  
          santid = parseInt(santid.replace('tr', ''));
  
          thisObj1.parent('td').html('\'<img src="uploads/loading.gif">\'');
  
          $.ajax({
  
            type : "POST",
  
            url : "operations.php",
  
            data : {
              santanceId : santid,
              crep : newRep1
            },
  
            success : function() {
  
              var newDom2 = '<span>' + newRep1 + '</span><span class="td-crep">' + newRep1 + '</span>';
  
              parent.html(newDom2);
  
              $("#ajaxLoading").val('false');
  
              loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
            }
          });
  
        }
      }
    }));

    $(document).on('keypress', '.new-value', (function(e) {
      if($('#editAllMode').val() == 0) {
        var thisObj1 = $(this);
  
        var newText = thisObj1.val();
  
        var parent1 = thisObj1.parent('td');
  
        var type1 = parent1.attr('class');
  
        var santid = parent1.parent('tr').attr('id');
  
        santid = parseInt(santid.replace('tr', ''));
  
        var storyid = $("#story-id").val();
  
        if (e.which === 13) {
  
          parent1.html('\'<img src="uploads/loading.gif">\'');
  
          if (parent1.hasClass('sentnc')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                santanceId : santid,
                sentnc : newText
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                parent1.html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parent1.hasClass('sreprow')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                sentenceId : santid,
                srep : newText,
                type: 'editSRep'
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                parent1.html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parent1.hasClass('senrep')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                santanceId : santid,
                rep : newText
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                thisObj1.parent('td').html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parent1.hasClass('quest')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                santanceId : santid,
                quest : newText
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                thisObj1.parent('td').html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parent1.hasClass('ans')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                santanceId : santid,
                ans : newText
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                thisObj1.parent('td').html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          } else if (parent1.hasClass('qrule')) {
  
            $.ajax({
  
              type : "POST",
  
              url : "operations.php",
  
              data : {
                santanceId : santid,
                qrule : newText
              },
  
              success : function() {
  
                var newDom = '<span class="td-edit">' + newText + '</span>';
  
                thisObj1.parent('td').html(newDom);
  
                $("#ajaxLoading").val('false');
  
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
  
              }
            });
  
          }

      }
      }
    }));

    $(document).on('click', '.add', (function(e) {

      window.location.replace("newstory.php");

    }));

    $(document).on('click', '.finishStory', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to finish this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'finishStory'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );

    }));
    
    
    $(document).on('click', '.reopenStory', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to reopen this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'reopenStory'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );

    }));
    
    $(document).on('click', '.setStory', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to set this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStory'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );

    }));
    
    
    $(document).on('click', '.setStoryOverwrite', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to set the story overwrite at import in DESKTOP ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryOverwrite'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );
    }));
    
    $(document).on('click', '.setStoryNotOverwrite', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to set the story to not overwrite at import in DESKTOP ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryNotOverwrite'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );
    }));
    
    $(document).on('click', '.setStoryAssigned', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to assign the story ?",
        modal : true,
        buttons : {
          "Yes" : function() {
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryAssigned'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      });
    }));
    
    $(document).on('click', '.setStoryUnAssigned', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to unassign the story ?",
        modal : true,
        buttons : {
          "Yes" : function() {
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryUnAssigned'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      });
    }));
    
    $(document).on('click', '.setStoryChecked', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to check this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryChecked'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );
    }));
    
    $(document).on('click', '.setStoryUnChecked', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to uncheck this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'setStoryUnChecked'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );
    }));
    
    
    
    
    $(document).on('click', '.readyStory', (function(e) {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
        resizable : false,
        height : 140,
        title : "Are you sure you want to ready this story ?",
        modal : true,
        buttons : {
          "Yes" : function() {    
            $.ajax({
              type : "POST",
              url : "operations.php",
              data : {
                storyId: storyid,
                action: 'readyStory'
              },
              success : function(result) {
                loadData(parseInt($(".pagination .currentpage_input").val(), 10));
              }
            }); 
            $(this).dialog("close");
          },
          Cancel : function() { 
            $(this).dialog("close");
          }
        } 
      } );

    }));
    
    $(document).on('click', '.editstory', (function(e) {

      var sid = $('#story-id').val();

      window.location.replace("newstory.php?id=" + sid);

    }));

    $(document).on('click', '.deletestory', (function(e) {

      var sid = $('#story-id').val();

      $("#confirm").dialog({

        resizable : false,

        height : 140,

        title : "This process will delete story id " + sid,

        modal : true,

        buttons : {

          "Yes" : function() {

            $.ajax({

              type : "POST",

              url : "operations.php",

              data : {
                id : sid,
                action : 'Delete',
                agree : 'true'
              },

              success : function(html) {

                //				alert(html);

                $("#ajaxLoading").val('false');

                loadData(1, -1);

              }
            });

            $(this).dialog("close");

          },

          Cancel : function() {

            $(this).dialog("close");

          }
        }

      });

    }));

    var opt = 0;

    /** my be not wokring **/

    /*

    $(document).on('click', '.tr-hover', (function(e) {

    var id = this.id;

    var santid = $(this).attr('sid');

    var data = 'santanceId=' + santid + '&aid=' + id;

    alert(data);

    $.ajax({

    type: "GET",

    url: "operations.php",

    data: data,

    success: function(html) {

    //                        loadData(sid);

    alert(html);

    }

    });

    $(this).find('.select-repguess').css('visibility', 'visible');

    }));

    */

    /*****/

    $(document).on('mouseenter', '.tr-hover', (function(e) {

      $(this).find('.select-repguess').css('visibility', 'visible');

    }));

    $(document).on('mouseleave', '.tr-hover', (function(e) {

      $(this).find('.select-repguess').css('visibility', 'hidden');

      $(this).find('.select-repguess.activ').css('visibility', 'visible');

    }));

    $(document).on('mouseenter', '.updateGuess', (function(e) {

      $(this).find('.refreshicone').css('visibility', 'visible');

    }));

    $(document).on('mouseleave', '.updateGuess', (function(e) {

      $(this).find('.refreshicone').css('visibility', 'hidden');

    }));

    $(document).on('click', '.select-repguess', (function() {

      var thisObj = $(this);

      var parentA = thisObj.parents('td');

      var santanceId = parentA.parent('tr').attr('id');

      santanceId = parseInt(santanceId.replace('tr', ''));

      var snumber = thisObj.attr('alt');

      var sid = $('#story-id').val();

      //alert('sid'+santanceId+' number'+snumber+' soryid'+sid);

      parentA.html('<img src="uploads/loading.gif">');

      //alert(santanceId +' '+ snumber+' '+ sid); //2  4  1
      $.ajax({

        type : "POST",

        url : "operations.php",

        data : {
          santanceId : santanceId,
          snumber : snumber,
          sid : sid
        },

        success : function(html) {
          //alert(html);
          $("#ajaxLoading").val('false');

          loadData(-1, sid);

        }
      });

    }));

    $('#stats-all').click(function() {

      //     load data only when tab clicked.

      var sid = $('#story-id').val();

      fndataTab(sid);

    });

    /*        $('#data-tab').click(function(){

     //     load data only when tab clicked.

     var sid = $('#story-id').val();

     //    fndataTab(sid);

     });

     */

    $('#refresh-chart').click(function() {

      //     load data only when tab clicked.

      var sid = $('#story-id').val();

      //               $('iframe').attr('src', $('iframe').attr('src'));

      var iframe = document.getElementById('currentElement');

      iframe.src = 'chart/chart.php?id=' + sid + '&width=' + (($(window).width() * 75) / 100);

    });
    
    // tabs 7 START - stroy functions    
    
    $('#tabs-7 .splitSentence').click(function() {
      var storyid = $('#story-id').val();
      $("#confirm").dialog({
      resizable : false,
      height : 140,
      title : "Are you sure you want to split the sentences ?",
      modal : true,
      buttons : {
        "Yes" : function() {
          var prList = [];
          $('.pr').each(function(index){
            var prID = $(this).attr('id');
            prID = parseInt(prID.replace('pr', ''), 10);
            var prValue = $(this).find("span").html();
            var prLevel = parseInt($(this).attr('level'), 10);
            var childSentences = [];
            if(prLevel > 1) {
              $('.se' + prID).each(function(i) {
                var sID = $(this).attr('id');
                sID = parseInt(sID.replace('s', ''), 10);
                var sValue = $(this).find("span").html();
                childSentences.push({
                  "sID": sID,
                  "sValue": sValue
                });
              });
            }
            prList.push({
              "prID": prID,
              "prValue": prValue,
              "prLevel": prLevel,
              "childSentences": childSentences
            });
          });
          $.ajax({
            type : "POST",
            url : "operations.php",
            data : {
              storyId: storyid,
              action: 'splitSentence',
              sentences: prList
            },
            success : function(result) {
              var sid = $('#story-id').val();
              loadData(-1, sid);  
            }
          }); 
          $(this).dialog("close");
        },
        Cancel : function() { 
          $(this).dialog("close");
        }
      } } );
    }); 
    
     /*$(document).on('click', '.proto_sentnc .td-crep-st, .short_sentnc .td-crep-st ', (function(e0) {
      e0.preventDefault();
        var thisObj = $(this);
        var olddata = thisObj.html();       
        olddata = escapeHtml(olddata);
        var newDom = '<span class="td-crep-st" id="' + thisObj.attr('id') + '" style="display:none;">' + olddata + '</span><input type="text" value="' + olddata + '"  class="new-value-st" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';        
        thisObj.parent('td').html(newDom);
    }));*/
   
   $(document).on('click', '.pr2 .td-crep-st, .se .td-crep-st ', (function(e0) {
      e0.preventDefault();
        var thisObj = $(this);
        var olddata = thisObj.html();       
        olddata = escapeHtml(olddata);
        var newDom = '<span class="td-crep-st" id="' + thisObj.attr('id') + '" style="display:none;">' + olddata + '</span><input type="text" value="' + olddata + '"  class="new-value-st" style="border:#bbb 1px solid;padding-left:5px;width:100%;"/ >';        
        thisObj.parent().html(newDom);
    }));
    
   
   $(document).on('click', '.pr .td-crep-st', (function(e0) {
      e0.preventDefault();
        var thisObj = $(this);
        var olddata = thisObj.html();       
        olddata = escapeHtml(olddata);
        var newDom = '<span class="td-crep-st" id="" style="display:none;">' + olddata + '</span><input type="text" value="' + olddata + '"  class="new-value-st" style="border:#bbb 1px solid;padding-left:5px;width:100%;"/ >';        
        thisObj.parent().html(newDom);
    }));
    
    $(document).on('focusout', '.new-value-st', (function(e1) {
      e1.preventDefault();
      var thisObj = $(this);
      var storyid = $("#story-id").val();
      var newData = thisObj.val();
      /*var santanceId = thisObj.parent('td').parent('tr').attr('id');
      santanceId = parseInt(santanceId.replace('tr', ''));*/
      var id = thisObj.parent().find('.td-crep-st').attr('id');
      //<span class="td-crep" sid="783" id="edit783">He has left his big top on the box</span>
      var newDom1 = '<span class="td-crep-st" id="'+id+'" >' + newData + '</span>';
      var parent = thisObj.parent('');
      

      if(parent.hasClass('pr1')) {
        var pr1Val = parent.find(".td-crep-st").html();
        var pr2Val = parent.next().find(".td-crep-st").html();
        var seVal = parent.next().next().find(".td-crep-st").html();
        var thirdVal = parent.next().next().next();
        if(pr1Val == pr2Val && pr1Val == seVal && thirdVal.hasClass("pr1")) {
          parent.next().find(".td-crep-st").html(newData);
          parent.next().next().find(".td-crep-st").html(newData);
        }
      }
      
      parent.html(newDom1);
    }));
    
    $(document).on('keypress', '.new-value-st', (function(e1) {
      if(e1.which == 13) {
        e1.preventDefault();
        var thisObj = $(this);
        var storyid = $("#story-id").val();
        var newData = thisObj.val();
        /*var santanceId = thisObj.parent('td').parent('tr').attr('id');
        santanceId = parseInt(santanceId.replace('tr', ''));*/
        var id = thisObj.parent().find('.td-crep-st').attr('id');
        //<span class="td-crep" sid="783" id="edit783">He has left his big top on the box</span>
        var newDom1 = '<span class="td-crep-st" id="'+id+'" >' + newData + '</span>';
        var parent = thisObj.parent('');
        
  
        if(parent.hasClass('pr1')) {
          var pr1Val = parent.find(".td-crep-st").html();
          var pr2Val = parent.next().find(".td-crep-st").html();
          var seVal = parent.next().next().find(".td-crep-st").html();
          var thirdVal = parent.next().next().next();
          if(pr1Val == pr2Val && pr1Val == seVal && thirdVal.hasClass("pr1")) {
            parent.next().find(".td-crep-st").html(newData);
            parent.next().next().find(".td-crep-st").html(newData);
          }
        }
        
      parent.html(newDom1);
      }
    }));
    
    //tabs 7 END - story functions 
    
    
    loadData(<?php if(!isset($_GET['id'])) { echo 1;} else{ echo '-1';} ?>, <?php if(isset($_GET['id'])) {echo $_GET['id'];} else{ echo '-1';} ?> );
  });

  function loading_show() {

    $('#loading').html("<img src='uploads/loading.gif'/>").fadeIn('fast');

  }

  function loading_hide() {

    $('#loading').fadeOut('fast');

  }

  //	var ajaxLoading = false;

  function loadData(page, storyid) {
    var ajaxLoading = $("#ajaxLoading").val();
    loading_show();
    if (ajaxLoading == 'false') {
      $.ajax({
        type : "POST",
        url : "load_data.php",
        encoding:"utf-8",
        dataType:"json", 
        data : { 
          page: page,
          storyid: storyid
        },
        async : false,
        success : function(data) {

          $("#table-row").html('');

          //$("#container").ajaxComplete(function(event, request, settings) {
            
            //event.preventDefault();
            loading_hide();
            var response = data;
            
            $(".chat-content").html('');
            //$(".firstmsg").hide();
            $("#container").html(response.msg);
            var stitle = $(".stitle").text();
            // $(".sctitle").text(stitle)
            var htmld = '<div class="chat-conv"><div class="cavatar defoalt">NAS</div><div class="cbubble"><div class="msg">Do you have any questions about this story?' + "<div class='firstmsg' style='display:none;'>What's your name?</div></div>" + '</div>' + '</div>'

            $(".chat-content").html(htmld);
            $("#table-row").html(response.table);
            $("#creptable-row").html(response.creptable);
            $("#faqtable-row").html(response.faqtable);
            $('#total-data').val(response.total);
            $('#story-id').val(response.storyId);
            $('#control-links').html(response.control_links);
            if(response.isFinished == '1'){
              // allow split 
               $('#tabs-7 .splitSentence').html('');
            }else{
               // allow split 
               $('#tabs-7 .splitSentence').html('SPLIT');
            }
            if(response.canEdit == 'false'){
            	 $('#tabs-2 .updateREP').html('');
            	 $('#tabs-2 .updateCREP').html('');
            	 $('#tabs-2 .updateAll').html('');            	             	 
            }else{
            	$('#tabs-2 .updateREP').html('Update REPS');
            	 $('#tabs-2 .updateCREP').html('Update CREPS');
            	 $('#tabs-2 .updateAll').html('Predict');
            }
            $('.statsresults').html(response.stats);
            
            //story TAB             
            $("#sentences-row").html(response.tablePrep);  
          //});

        }
      });

    }

  }

</script>

<style type="text/css">
  input.sentencetxt, input.representationtxt {

    border: 1px solid #EEEEEE;
    display: inline-table;
    padding: 2px;
    width: 95%;
  }
  
  #rep-data td {
    font-size: 13px;
    padding:5px;
    vertical-align:top;
  }
  
  .searchrow:hover td {
    color: #999;
    cursor: pointer;
  }

  .category_selection {
    position: absolute;
    right: 15px;
  }
  
  .update-crep-row {
    margin-top: 3px;
    background-image: none;
    background-repeat: no-repeat;
    height: 18px;
    width: 18px;
    cursor: pointer;
  }
   
  .readyStory, .setStoryUnChecked, .readyStory, .setStoryNotOverwrite, .reopenStory, .setStoryUnAssigned {
    color: red;
  } 
   
</style>

<?php

//require_once(THEMEDIR."/login.tpl.php");
?>
<?php 
  if(!$user->logged_in) {
    ?>
    <section id="crumbs" class="row">
      <div class="container">  
        <div class="clearfix">
          <div class="col grid_24">
            <nav><span class="bread-home"><a href="http://neoaisystems.com/index.php">Home</a></span>Demo</nav>
          </div>
        </div>
        </div>
      </section>
      
      <div class="container">  
        <div id="page" class="row grid_24">
          <div class="content-box">
            <article class="post">
              <header class="post-header"><h1><span>Demo</span></h1></header>
              <div class="post-body">
                <span style="font-size: medium;">
                  <br>
                  Coming July 2014 . .
                  <br>
                </span>
              </div>
              </article>
          </div>
        </div>
      </div>
    <?php
  } else {
?>

<div id="sql"></div>
<input type="hidden" id="editAllMode" value="0" />
<div class="header-wrapper">

  <div class="header" style="position:relative; margin-top:60px;">

    <div class="logoo">
      STORY <span class="td-edit1"></span>
      <span class="addedit" id="control-links" style="float: right;margin-right: 255px;font-size: medium;">
        <a class="add" href="#">Add</a> / <a href="#" class="editstory">Edit</a> / <a  class="deletestory" href="#">Delete</a>
      </span>
    </div>

    <div class="header_right">
      <!--<a  href="#"><img src="uploads/header_right_img.png"/></a>-->
    </div>

    <div class="store" >

      <div id="loading"></div>

      <div id="container" >  

        <div class="data">
                  
        </div>

        <div class="pagination"></div>

      </div>

    </div>

  </div>

  <div class="chat" id="tabs">

    <ul>
      
      <?php /*
      <li>
        <a href="#tabs-1">CHAT</a>
      </li> */ ?>

      <?php

      if (($user -> logged_in)) {

        echo '<li><a href="#tabs-2">REP</a></li>';

      }
      ?>

      <?php

      /*if (($user -> logged_in)) {

        echo '<li><a href="#tabs-3">CREP</a></li>';

      }*/
      ?>

      <?php

      if (($user -> logged_in)) {

        echo '<li><a href="#tabs-4">Q & A</a></li>';

      }
      ?>

      <?php
      /*
      if (($user -> logged_in)) {

        echo '<li><a href="#tabs-5" id="refresh-chart">CHART</a></li>';

      }

      if (($user -> logged_in)) {

        echo '<li><a href="#tabs-6" id="stats-all">STATS</a></li>';

      } */
      ?>

      <?php
      if (($user -> logged_in)) {
        echo '<li><a href="#tabs-7">SPLIT</a></li>';
      }
      if (($user -> logged_in)) {
        echo '<li><a href="#tabs-8">SEARCH</a></li>';
      }
      if (($user -> logged_in)) {
        echo '<li><a href="#tabs-9">STATS</a></li>';
      }
      ?>
    </ul>

    <?php /*
    <div id ="tabs-1" class="nas">

      <div class="chat-wi">

        <div class="chat-space nano nscroller" style="border-bottom: 40px solid rgb(230, 230, 230);">

          <div class="chat-content content" style="height:150px; overflow-y: scroll;  padding: 10px 10px;">

            <div class="chat-conv">

              <div class="c-avatar defoalt">
                NAS
              </div>

              <div class="c-bubble">

                <div class="msg">
                  Do you have any questions about this story?'
                </div>

              </div>

            </div>

          </div>

        </div>

        <div class="text chat-in">

          <form action="http://condorthemes.com/cleanzone/dfgdfg" method="post" name="sd">

            <input type="text" placeholder="Send a message..." name="msg" class="textarerea" style="background-color:#FFF;"/>

            <input type="submit" value="Reply" class="primary" />

          </form>

        </div>

      </div>

    </div>

    <?php*/ if (($user->logged_in)) { ?>

    <div id ="tabs-2" class="nas">

      <input type="hidden" value="" id="total-data"/>

      <input type="hidden" value="" id="total-count"/>

      <input type="hidden" value="" id="story-id" value="<?php if(isset($_GET['id'])) {echo $_GET['id']; }?>"/>

      <input type="hidden" value="false" id="ajaxLoading"/>
      <input type="hidden" value="1" id="pg"/>

      <div class="chat-wi">

        <div class="chat-space nano nscroller">

          <div id="table-row"></div>

        </div>

      </div>
      
      
      
      <div id="backdiv" style="display:block; right:580px; top:58px; position:absolute; font-weight:bold;">
        <a href="#" id="backbtn">BACK</a>
      </div>
      
      <div id="loder" style="display:none; right:650px; top:58px; position:absolute; "><img src="uploads/loading.gif">
      </div>

      <div style="color:#3399FF;cursor: pointer; right:490px; top:58px; position:absolute; ">
        <a href="check_all.php" target="_blank">AutoCheck</a>
      </div>

      <div style="color:#3399FF;cursor: pointer; right:325px; top:58px; position:absolute; " class="">

        <input id="percent" type="hidden" value="25%" />
        <?php /*<select name="select" id="percent">

          <option value="25%">25%</option>

          <option value="50%">50%</option>

          <option value="100%" selected="selected">100%</option>

        </select> */ ?>

      </div>
	
      <div style="color:#3399FF;cursor: pointer; right:450px; top:58px; position:absolute; " class="updateEdit">
        Edit
      </div>
	
      <div style="color:#3399FF;cursor: pointer; right:350px; top:58px; position:absolute; " class="updateREP">
        Update REPS
      </div>
      
      <div style="color:#3399FF;cursor: pointer; right:240px; top:58px; position:absolute; " class="updateCREP">
        Update CREPS
      </div>

      <div style="color: #aaa; cursor: pointer; right:180px; top:58px; position:absolute; " class="updateAll">
        Predict
      </div>
	
	
      <?php /* <div style="color:#3399FF;cursor: pointer; right:205px; top:58px; position:absolute;" class="statsCalculator"  >
        Stats
      </div> */ ?>

      <div style="color:#3399FF;cursor: pointer; right:25px; top:58px; position:absolute;" class="statsresults" ></div>

    </div>

    <?php } ?>

    <?php if (($user->logged_in)) { /* ?>

    <div id ="tabs-3" class="nas">

      <div class="chat-wi">

        <div class="chat-space nano nscroller">

          <div id="creptable-row"></div>

        </div>

      </div>

      <div id="loder" style="display:none;"><img src="uploads/loading.gif">
      </div>

      <h3 style="color:#3399FF;font-weight: bold;cursor: pointer;" class="updateAll">Update All</h3>

    </div>

    <?php*/ } ?>

    <?php if (($user->logged_in)) { ?>

    <div id ="tabs-4" class="nas">

      <div class="chat-wi">

        <div class="chat-space nano nscroller">

          <div id="faqtable-row"></div>

        </div>

      </div>

    </div>

    <?php /*<div id ="tabs-6" class="nas">

      <img id="tab-loader" src="uploads/loading.gif" style="display:none;">

    </div>*/ ?>

    <?php } ?>

    <?php if (($user->logged_in)) { ?>

    <?php /*<div id ="tabs-5" class="nas">

      <div class="chat-wi">

        <div class="chat-space nano nscroller">

          <div id="faqtable-row">

            <!--div id='abcchart' style="width:850px; height:400px">   </div -->

            <iframe id="currentElement" class="myframe" name="myframe" src="chart/chart.php?id=1" style="width:100%; height:440px;"></iframe>

            <!--link rel="stylesheet" href="assets/jqwidgets/styles/jqx.base.css" type="text/css" / -->

            <!--    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>  -->

            <!--                            <script type="text/javascript" src="assets/scripts/jquery-1.10.2.min.js"></script>

            -->
            <script>
              //$j=

              //                                $qry = jQuery.noConflict();

            </script>

            <!--

            <script type="text/javascript" src="assets/jqwidgets/jqxcore.js"></script>

            <script type="text/javascript" src="assets/jqwidgets/jqxdata.js"></script>

            <script type="text/javascript" src="assets/jqwidgets/jqxchart.js"></script>

            -->

            <script type="text/javascript">
              //                                $qry(document).ready(function($j) {

              //                            //jQuery(document).ready(function($j) {

              //                                    // prepare the data

              //                                    var source =

              //                                            {

              //                                                datatype: "csv",

              //                                                datafields: [

              //                                                    {name: 'percentage'},

              //                                                    {name: 'guess1'},

              //                                                    {name: 'guess2'},

              //                                                    {name: 'guess3'}

              //                                                ],

              //                                                url: 'testdata.txt'

              //                                            };

              //

              //                                    var dataAdapter = new $j.jqx.dataAdapter(source, {async: false, autoBind: true, loadError: function(xhr, status, error) {

              //                                            alert('Error loading "' + source.url + '" : ' + error);

              //                                        }});

              //

              //

              //                            // prepare jqxChart settings

              //                                    var settings = {

              //                                        title: "Title",

              //                                        description: "Description",

              //                                        enableAnimations: true,

              //                                        showLegend: true,

              //                                        padding: {left: 10, top: 5, right: 10, bottom: 5},

              //                                        titlePadding: {left: 90, top: 0, right: 0, bottom: 10},

              //                                        source: dataAdapter,

              //                                        categoryAxis:

              //                                                {

              //                                                    dataField: 'percentage',

              //                                                    formatFunction: function(value) {

              //                                                        return value;

              //                                                    },

              //                                                    toolTipFormatFunction: function(value) {

              //                                                        return value;

              //                                                    },

              //                                                    type: 'number',

              //                                                    showTickMarks: true,

              //                                                    tickMarksInterval: 1,

              //                                                    tickMarksColor: '#888888',

              //                                                    unitInterval: 1,

              //                                                    showGridLines: true,

              //                                                    gridLinesInterval: 3,

              //                                                    gridLinesColor: '#888888',

              //                                                    valuesOnTicks: false

              //                                                },

              //                                        colorScheme: 'scheme04',

              //                                        seriesGroups:

              //                                                [

              //                                                    {

              //                                                        type: 'line',

              //                                                        valueAxis:

              //                                                                {

              //                                                                    unitInterval: 10,

              //                                                                    minValue: 0,

              //                                                                    maxValue: 100,

              //                                                                    displayValueAxis: true,

              //                                                                    description: 'Percentage Value',

              //                                                                    axisSize: 'auto',

              //                                                                    tickMarksColor: '#888888'

              //                                                                },

              //                                                        series: [

              //                                                            {dataField: 'guess1', displayText: 'guesses1'},

              //                                                            {dataField: 'guess2', displayText: 'guesses2'},

              //                                                            {dataField: 'guess3', displayText: 'guesses3'}

              //                                                        ]

              //                                                    }

              //                                                ]

              //                                    };

              //

              //                                    // setup the chart

              //

              //                                    $j('#abcchart').jqxChart(settings);

              //

              //                                });

            </script>

          </div>

        </div>

      </div>

    </div>
     */ ?>
    <?php } ?>
    
    <?php if (($user->logged_in)) { ?>
    <div id ="tabs-7" class="nas">      
     <input type="hidden" id="total-data" />  
     <input type="hidden" value="false" id="ajaxLoading"/> 
     <div id="loading"></div>          
      <div class="chat-wi">
        <div class="chat-space nano nscroller">
          <div id="sentences-row"></div>
        </div>
      </div>
    
      <div style="color:#3399FF;cursor: pointer; right:150px; top:58px; position:absolute; " class="splitSentence">
        SPLIT
      </div>
      
    </div>
    <?php } ?>

    <?php if (($user->logged_in)) { ?>
    <div id ="tabs-8" class="nas"> 
     <div class="search-loading"></div>
     <div class="search-form">
        <input type="text" name="searchInput" id="searchQuery" placeholder="Search..." style="border: #bbb 1px solid; padding-left: 5px; width: 30%;" />
        <span id="searchButton" style="margin-left: 10px; color:#3399FF;cursor: pointer;">Search</span>
        <span id="listAllButton" style="float:right; margin-left: 10px; color:#3399FF;cursor: pointer;">List All</span>
     </div>
     <div class="chat-wi">
      <div class="chat-space nano nscroller">
        <div id="search-row"></div>
      </div>
     </div>
    </div>
    <?php } ?>

    <?php if (($user->logged_in)) { ?>
    <div id ="tabs-9" class="nas"> 
     <div class="chat-wi">
      <div class="chat-space nano nscroller">
        <div id="stats-row"></div>
        <?php
          require_once "config_storydb.php";
          $db = new mysqli($configuration['host'], $configuration['user'], $configuration['pass'], $configuration['db']); 
          $db->set_charset("utf8");

          $query = $db->query("
            SELECT 
              COUNT(p1.`pageID`) AS totalPages, 
              COUNT(p2.`pageID`) AS totalPagesSplitTrained, 
              COUNT(p3.`pageID`) AS totalPagesRepTrained, 
              COUNT(p4.`pageID`) AS totalPagesCRepTrained
            FROM `page` p1
            LEFT JOIN page p2 ON p1.`pageID` = p2.`pageID` AND p2.`pageStatus` IN ('psTrainedSplit', 'psReviewedSplit')
            LEFT JOIN page p3 ON p1.`pageID` = p3.`pageID` AND p3.`pageStatus` IN ('psTrainedRep', 'psReviewedRep')
            LEFT JOIN page p4 ON p1.`pageID` = p4.`pageID` AND p4.`pageStatus` IN ('psTrainedCRep', 'psReviewedCRep')
          ");
          $pageData = $query->fetch_array();

          $query = $db->query("
            SELECT 
              COUNT(s1.`sentenceID`) AS totalSentences, 
              COUNT(s2.`sentenceID`) AS totalSentencesSplitTrained, 
              COUNT(s3.`sentenceID`) AS totalSentencesRepTrained, 
              COUNT(s4.`sentenceID`) AS totalSentencesCRepTrained
            FROM `sentence` s1
            LEFT JOIN sentence s2 ON s1.`sentenceID` = s2.`sentenceID` AND s2.`sentenceStatus` IN ('ssTrainedSplit', 'ssReviewedSplit')
            LEFT JOIN sentence s3 ON s1.`sentenceID` = s3.`sentenceID` AND s3.`sentenceStatus` IN ('ssTrainedRep', 'ssReviewedRep')
            LEFT JOIN sentence s4 ON s1.`sentenceID` = s4.`sentenceID` AND s4.`sentenceStatus` IN ('ssTrainedCRep', 'ssReviewedCRep')
          ");
          $sentenceData = $query->fetch_array(); 
          
          echo "<br/><strong>Total Pages:</strong> " . $pageData['totalPages'];
          echo "<br/><strong>Total Pages Split Trained:</strong> " . $pageData['totalPagesSplitTrained'];
          echo "<br/><strong>Total Pages Rep Trained:</strong> " . $pageData['totalPagesRepTrained'];
          echo "<br/><strong>Total Pages CRep Trained:</strong> " . $pageData['totalPagesCRepTrained'];
          echo "<br/>";
          echo "<br/><strong>Total Sentences:</strong> " . $sentenceData['totalSentences'];
          echo "<br/><strong>Total Sentences Split Trained:</strong> " . $sentenceData['totalSentencesSplitTrained'];
          echo "<br/><strong>Total Sentences Rep Trained:</strong> " . $sentenceData['totalSentencesRepTrained'];
          echo "<br/><strong>Total Sentences CRep Trained:</strong> " . $sentenceData['totalSentencesCRepTrained'];
          
        ?>
      </div>
     </div>
    </div>
    <?php } ?>

  </div>

  <div id="confirm">

  </div>

  <?php
  }
  include (THEMEDIR . "/footer.php");
 ?>

