<?php

$user_1st_program_answer = $_POST['user_1st_program_answer'];

$hello_world = "Hello World."

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Playground</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="assets/style.css">
    <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body class="container">
<h1 class="text-center"><strong>Playground</strong></h1>



<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="text-primary ">Vanilla JS</h2>
        <div class="game">
            <h2> Rock Paper Scissors</h2>
                <input type="submit" id="play" class="btn btn-warning" value="Click to Play">
            <p id="demo"></p>
        </div>
    </div>
</div>

<br><br><hr>

<div class="row text-center">
    <h2 class="">jQuery</h2>                                                
    <div class="col-xs-6">
      <h4>#left-well</h4>
      <div class="well" id="left-well">
        <button class="btn btn-default target" id="target1">#target1</button>
        <button class="btn btn-default target" id="target2">#target2</button>
        <button class="btn btn-default target" id="target3">#target3</button>
      </div>
    </div>
    <div class="col-xs-6">
      <h4>#right-well</h4>
      <div class="well" id="right-well">
        <button class="btn btn-default target" id="target4">#target4</button>
        <button class="btn btn-default target" id="target5">#target5</button>
        <button class="btn btn-default target" id="target6">#target6</button>
      </div>
    </div>
</div>

<br><br><hr class="fancy">

<div class="row">
    <div class="col-xs-12 text-center">    
	<h2>Posting Data with PHP</h2>
	<form method="post" action="index.php">
		<p>What is the 1st program you should write?</p>
		<input type="text" id="user_1st_program_answer" class="form-control" name="user_1st_program_answer" >
		<input type="submit" class="form-control btn btn-primary"> 
	</form>

<h2>Your Answer is</h2>
<?php
	if ( !empty($_POST['user_1st_program_answer']) ) {
	         echo $user_1st_program_answer;
	} 
 ?>
<h2>The Correct is</h2>
<?php
    if ( !empty($_POST['user_1st_program_answer']) ) {
             echo $hello_world;
    } 
 ?>	
    </div>
</div>
<br><br><hr class="fancy">
<div class="row">
    <div class="col-xs-12 text-center">   
    <h2>Posting Data with AJAX</h2>

<!-- our form -->  
<form id='userForm'>
    <div><input id="firstname" class="form-control" type='text' name='firstname' placeholder='Firstname' /></div>
    <div><input type='text' class="form-control" name='lastname' placeholder='Lastname' /></div>
    <div><input type='text' class="form-control" name='email' placeholder='Email' /></div>
    <div>
    	<label for="range">Pick a number 1-100</label>
	    <input type="range" min="0" max="100" step="1" id="range_input_id" class="form-control" name="range" oninput="range_output_id.value = range_input_id.value">
	    <output for="range" name"range" id="range_output_id" vaule="" style="margin:0 auto;"></output>
    </div>
    <div><input type='submit' class="btn btn-success" value='Submit' /></div>

</form>
 
<!-- where the response will be displayed -->
<div id='response'></div>


<script>
$(document).ready(function(){
	// alternatively, select individual input elements
    $('#userForm').submit(function(){
     
        // show that something is loading, fills in response div with this text
        $('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST', //= method:GET, POST, etc. 
            url: 'ajax.php', //=action
            data: $(this).serialize() 
        })
        .done(function(data){
             
            // show the response
            $('#response').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
});
</script>
 

    </div>
</div>
<script type="text/javascript" src="assets/javascript.js"></script>
</body>
</html>


