<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>License</h2>

<form id="license-form">
    <input type="text" id="license-key" name="license-key" placeholder="Enter license key">
    <input type="submit" value="Submit">
</form>

<div id="test"></div>

<script>
$(document).ready(function() {
    $('#license-form').submit(function(event) {
        event.preventDefault();
        var key = $('#license-key').val();
        var domain = window.location.hostname; 
        $.ajax({
            url: 'https://cdn.netpeak.dev', 
            type: 'POST',
            dataType: 'json', 
            data: {
                domain: domain,
                key: key
            },
            success: function(response) {
                $('#test').html('Success');
            },
            error: function(xhr, status, error) {
                var errorMessage;
                if (xhr.status === 403) {
                    var response = JSON.parse(xhr.responseText); 
                    errorMessage = response.error;
                } else {
                    errorMessage = 'Error: ' + error;
                }
                $('#test').html(errorMessage);
            }
        });
    });
});
</script>

</body>
</html>
