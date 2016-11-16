 $(document).ready(function() {
    $("#target1").css("color", "red");
    $("#target1").prop("disabled", true);
    $("#target4").remove();
    $("#target2").appendTo("#right-well");
    $("#target5").clone().appendTo("#left-well");
    $("#target1").parent().css("background-color", "red");
    $("#right-well").children().css("color", "orange");
    $("#left-well").children().css("color", "green");
    $(".target:nth-child(2)").addClass("animated bounce");
    $(".target:even").addClass("animated shake");

  });

 document.addEventListener("DOMContentLoaded", function(event) { 
  

document.getElementById("play").addEventListener("click", function(){
    

 var userChoice = prompt("Do you choose rock, paper or scissors?");
    var computerChoice = Math.random();
    if (computerChoice < 0.34) {
        computerChoice = "rock";
    } else if(computerChoice <= 0.67) {
        computerChoice = "paper";
    } else {
        computerChoice = "scissors";
    } console.log("Computer: " + computerChoice);

    var compare = function(choice1, choice2)
    {
        if (choice1 === choice2)
        {return "The result is a tie!";}
        
            else if(choice1 === "rock")  {
             
                 if(choice2 === "scissors")
                 {return "rock wins";}
                
                else
                {return "paper wins";}
        }
        
        else if(choice1 === "paper")
        {
            if(choice2 === "rock")
            {return "paper wins";}
            else {return "scissors wins";}
        }
        
        
        else if (choice1 === "scissors")
        {
            if(choice2 === "rock")
            {return "rock wins";}
            else {return "scissors wins";}
        }
        
        else 
            {return "choose only 'rock', 'paper', or 'scissors' please!";}

    };

    console.log(compare(userChoice,computerChoice));
    document.getElementById("demo").innerHTML = compare(userChoice,computerChoice);

});


});