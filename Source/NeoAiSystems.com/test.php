<?php ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div id="table-row">
</div>
<div>
    <input type="hidden" id="story-id" />
</div>    

<script type="text/javascript">
    $(document).ready(function() {
            $.ajax
                ({
                    type: "POST",
                    url: "load_data.php",
                    data: "page=" +1,
                    async: false,
                    success: function(data)
                    {
                        var response = $.parseJSON(data);
                        $("#table-row").html(response.table);
                        $('#story-id').val(response.storyId);
                    }
                });
                
           $(document).on('click', '.td-edit', (function(e0) {
            e0.preventDefault;
            var thisObj = $(this);
            var olddata = thisObj.html();
            var newDom = '<input type="text" value="' + olddata + '" class="new-value" style="border:#bbb 1px solid;padding-left:5px;width:75%;"/ >';
            thisObj.parent('td').html(newDom);
        }));
        $(document).on('focusout', '.new-value', (function(e1) {
            e1.preventDefault();
            var thisObj = $(this);
            var parentA=thisObj.parent('td');
            var type = parentA.attr('class');
            var newData = thisObj.val();
            var santanceId = parentA.parent('tr').attr('id');
            santanceId = parseInt(santanceId.replace('tr', ''));
            parentA.html('\'<img src="uploads/loading.gif">\'');
            if (type === 'sentnc') {

                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santanceId, sentnc: newData},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newData + '</span>';
                        parentA.html(newDom);
                    }
                });

            } else if (type === 'senrep') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santanceId, rep: newData},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newData + '</span>';
                        thisObj.parent('td').html(newDom);
                    }
                });
            }
            else if (type === 'quest') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santanceId, quest: newData},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newData + '</span>';
                        thisObj.parent('td').html(newDom);
                    }
                });
            }
            else if (type === 'ans') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santanceId, ans: newData},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newData + '</span>';
                        thisObj.parent('td').html(newDom);
                    }
                });
            }
            else if (type === 'qrule') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santanceId, qrule: newData},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newData + '</span>';
                        thisObj.parent('td').html(newDom);
                    }
                });
            }

            // updateSentence(santanceId, newData);

        }));
        
        $(document).on('keypress', '.new-value', (function(e) {
            var thisObj1 = $(this);
            var newText = thisObj1.val();
            var parent1=thisObj1.parent('td');
            var type1 = parent1.attr('class');
            var santid = parent1.parent('tr').attr('id');
             santid = parseInt(santid.replace('tr', ''));
               
            if (e.which === 13) {
                parent1.html('\'<img src="uploads/loading.gif">\'');
                
               if (type1 === 'sentnc') {

                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santid, sentnc: newText},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newText + '</span>';
                        console.log(thisObj1);
                        parent1.html(newDom);
                    }
                });

            } else if (type1 === 'senrep') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santid, rep: newText},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newText + '</span>';
                        thisObj1.parent('td').html(newDom);
                    }
                });
            }
            else if (type1 === 'quest') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santid, quest: newText},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newText + '</span>';
                        thisObj1.parent('td').html(newDom);
                    }
                });
            }
            else if (type1 === 'ans') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santid, ans: newText},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newText + '</span>';
                        thisObj1.parent('td').html(newDom);
                    }
                });
            }
            else if (type1 === 'qrule') {
                $.ajax({
                    type: "POST",
                    url: "operations.php",
                    data: {santanceId: santid, qrule: newText},
                    success: function() {
                        var newDom = '<span class="td-edit">' + newText + '</span>';
                        thisObj1.parent('td').html(newDom);
                    }
                });
            }



            }
        }));      
        });
</script>

