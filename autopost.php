<html>
<head><title>jquery</title></head>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<body>

<form id="target">
<input id="one" type="text" name="one" value="Submit here" style="vertical-align: middle;" />
<p id="spinner" style="vertical-align: middle; display: none">Spinning</p>
<div id="result"></div>

<script type="text/javascript">
$(document).ready(function() {
    $('#target').change(function(event) {
        $('#spinner').toggle();
        var form = $('#target');
        //var txt = form.find('input[name="one"]').val();
        var txt = $('#one').val();
        console.log('sending POST ' + txt);
        $.post('autoecho.php', { val: txt }, 
            function(data) {
                console.log(data);
                $('#result').append(data);
                $('#spinner').toggle();
            }
        ).error( function() {
            $('#target').css('background-color', 'red');
            alert('error');
        });
    });
});

</script>

</body>
</html>
