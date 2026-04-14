$(document).ready(function(){
    $(function() {
    $(".delete-car").click(function(){
      //Decrease number of selected candidates
    //  let numCandSelected = parseInt(document.getElementById("num_candidates").textContent) - 1;
    //$('#num_candidates').html(numCandSelected);
    //Save the link in a variable called element
    var element = $(this).attr('id');
    //Find the id of the link that was clicked
    var del_id = element;
    var table = "images";
    var field = "id";
    //Built a url to send
    var info = 'id=' + del_id ;
       $.ajax({
         type: "GET",
         url: "processes/delete_car.php",
         data: info,
         success: function(){

         }
     });
       $(this).parents(".car-row").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");    
    });
    
       });
    });
    