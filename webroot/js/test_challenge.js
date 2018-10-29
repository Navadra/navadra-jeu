function get_new_exercice() 
{
  codeChallenge = $("#element").val() + "_" + $("#notion").val() + "_" + $("#level").val() + "_" + $("#exercise").val();
  console.log("ID CHALLENGE = " + codeChallenge);
  get_challenge_json(codeChallenge);
}


function challengeTimeOut()
{
  console.log("ChallengeTimeOut");
}

$(function(){
 
  situation = 0;
  
  $("#element").on("change", function(){
    $("#notion .fire").hide();
    $("#notion .water").hide();
    $("#notion .wind").hide();
    $("#notion .earth").hide();
    $("#notion ." + $(this).val()).show();
  });

  $("#exercise").on("change", function(){
    get_new_exercice();
  });

});




