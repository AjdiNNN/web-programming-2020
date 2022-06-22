var itemNo = 0;
var msDay = 60*60*24*1000;
var msHours = 60*60*1000;
var msMinute = 60*1000;
var currentMinbid = 1;
function checkEndings()
{
    for(let i = 0; i <itemNo; i++)
    {
        $.ajax({
            url: 'rest/bids/'+$('#highestBid'+i).attr("value"),
            type: "GET",
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
          },
          success: function(data) {
            if (!$.trim(data)){   
              $('#highestBid'+i).html("No bids!");
            }
            else{   
                $('#highestBid'+i).html("Highest bid "+data[0].amount+"BAM");
            } 
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
              toastr.error(XMLHttpRequest.responseJSON.message);
              //userService.logout();
          }
        });
        var currentDate = new Date().getTime();
        var endingDate = new Date($("#date"+i).attr('datetime')).getTime();
        var diff = Math.floor((endingDate - currentDate) / msDay);
        if(diff<0)
        {
            $("#date"+i).html("Ended");
            continue;
        }
        else if(diff>0)
        {
            $("#date"+i).html(diff+" days left");
            $("#date"+i).css("background-color", "rgb(15, 99, 30)");
            continue;
        }
        else
        {
            var hours = Math.floor(((endingDate - currentDate) % msDay) / msHours);
            if(hours>0)
            {
                $("#date"+i).html(hours+" hours left");
                $("#date"+i).css("background-color", "rgb(255,127,80)");
                continue;
            }
            else
            {
                var mins = Math.floor(((endingDate - currentDate) % msDay) / msMinute);
                if(mins>0)
                {
                    $("#date"+i).html(mins+" minutes left");
                    continue;
                }
                else
                {
                    var seconds = Math.floor(((endingDate - currentDate) % 60000) / 1000);
                    $("#date"+i).html(seconds+" seconds left");
                }
            }
        }
    }
}
function modalEnding(useritem){
  var currentDate = new Date().getTime();
  var endingDate = new Date( $('#modalDate').attr('datetime')).getTime();
  var diff = Math.floor((endingDate - currentDate) / msDay);
  if(!useritem){
    $('#bid').css("display","flex");
  }
  if(diff<0)
  {
      $('#modalDate').html("Ended");
      $('#modalDate').css("background-color", "rgb(0, 0, 0)");
      $('#bid').css("display","none");
  }
  else if(diff>0)
  {
      $('#modalDate').html(diff+" days left");
      $('#modalDate').css("background-color", "rgb(15, 99, 30)");
  }
  else
  {
      var hours = Math.floor(((endingDate - currentDate) % msDay) / msHours);
      if(hours>0)
      {
          $('#modalDate').html(hours+" hours left");
          $('#modalDate').css("background-color", "rgb(255,127,80)");
      }
      else
      {
          var mins = Math.floor(((endingDate - currentDate) % msDay) / msMinute);
          if(mins>0)
          {
              $('#modalDate').html(mins+" minutes left");
          }
          else
          {
              var seconds = Math.floor(((endingDate - currentDate) % 60000) / 1000);
              $('#modalDate').html(seconds+" seconds left");
          }
      }
  }
  $.ajax({
    url: 'rest/bids/'+$('#modalText').attr("value"),
    type: "GET",
  beforeSend: function(xhr){
    xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
  },
  success: function(data) {
    $('#modalText').html("");
    if(diff>=0)
    {
      if (!$.trim(data)){
        if(!useritem){
          $('#modalText').html("Bid 1BAM or more!");
          currentMinbid = 1;
        }
        else
        {
          $('#modalText').html("No bids!");
        }
      }
      else{   
        if(!useritem){
          currentMinbid = data[0].amount+1;
          $('#modalText').html("Bid "+(data[0].amount+1)+"BAM or more");
        }
        else
        {
          $('#modalText').html("Current bid "+(data[0].amount+1)+"BAM");
        }
      } 
    }
    else
    {
      if (!$.trim(data)){
        $('#modalText').html("No bids!");
      }
      else{   
        $('#modalText').html("Higest bid "+(data[0].amount+1)+"BAM");
      } 
    }
  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
      toastr.error(XMLHttpRequest.responseJSON.message);
      //userService.logout();
  }
});
}
var MainService = {
    init: function(){
      MainService.list();
      setInterval(function () {
        checkEndings();
     }, 1000);
     
      $('#exampleModalCenter').on('hidden.bs.modal', function(e) {
        $('#modalText').css("color","black")
      });
      $('#bidform').validate({ 
        rules:{
          amount: {
              required:true,
              min:  function ()  { return  currentMinbid; }
            },
        },
        errorPlacement: function ( error, element ) {
      },
        highlight: function(element) {
            $(element).removeClass('is-valid').addClass('is-invalid');
            $('#modalText').css("color","red")
            $('#money').css("color","red")
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
            $('#modalText').css("color","green")
            $('#money').css("color","green")
        },
        submitHandler: function(form) {
          
          var bid = Object.fromEntries((new FormData(form)).entries());
          MainService.addBid(bid);
        },
      });
    },

    list: function(){
      $.ajax({
         url: "rest/items",
         type: "GET",
         beforeSend: function(xhr){
           xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
         },
         success: function(data) {
           $("#item-list").html("");
            var html = "";
            for(let i = 0; i < data.length; i++){
                var currentDate = new Date().getTime();
                var endingDate = new Date(data[i].ending).getTime();
                var diff = Math.floor((endingDate - currentDate) / msDay);
                var toShow = "Ended";
                var color = "";
                if(diff<0)
                {
                    toShow = toShow;
                    color = "rgb(0, 0, 0)";
                }
                else if(diff>0)
                {
                    toShow = diff+" days left";
                    color = "rgb(15, 99, 30)";
                }
                else
                {
                    var hours = Math.floor(((endingDate - currentDate) % msDay) / msHours);
                    if(hours>0)
                    {
                        toShow = hours+" hours left";
                        color = "rgb(255,127,80)";
                    }
                    else
                    {
                        var mins = Math.floor(((endingDate - currentDate) % msDay) / msMinute);
                        if(mins>0)
                        {
                            toShow = mins+" minutes left";
                        }
                        else
                        {
                            var seconds = Math.floor(((endingDate - currentDate) % 60000) / 1000);
                            toShow = seconds+" seconds left";
                        }
                    }
                }
                html += `
                <div class="col-md-6 col-lg-4 col-xl-3" data-toggle="modal" data-target="#exampleModalCenter" onclick="MainService.get(`+data[i].id+`)">
                <div id="product-2" class="single-product">
                        <div class="part-1" id="itemImage`+itemNo+`">
                        <style>
                        #itemImage`+itemNo+`::before{
                            background: url("img/items/`+data[i].image+`") no-repeat center;
                            background-size: cover;
                            transition: all 0.3s;
                        }
                        </style>
                          <span class="discount" id="date`+itemNo+`" style="background-color: `+ color +`" datetime="`+data[i].ending+`">`+toShow+`</span>
                        </div>
                        <div class="part-2">
                                <h3 class="product-title">`+data[i].title+`</h3>
                                <h4 class="product-price" id="highestBid`+itemNo+`" value="`+data[i].id+`"><div class="spinner-border" role="status"></div></h4>
                        </div>
                    </div>
                </div>
                `;
                itemNo++;
            }
            
            $("#item-list").html(html);
        },
         error: function(XMLHttpRequest, textStatus, errorThrown) {
           toastr.error(XMLHttpRequest.responseJSON.message);
           //userService.logout();
         }
      });
    },

    get: function(id){
      $.ajax({
        url: 'rest/user',
        type: "GET",
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        success: function(testId) {
          $.ajax({
            url: 'rest/items/'+id,
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data) {
              $('#bid').css("display","none");
                var userItem = false;
                if(data.owner_id == testId)
                {
                  $('#bid').css("display","none");
                  userItem=true;
                }
                  
                $('#dataTitle').html(data.title);
                $('#modalDate').attr("dateTime", data.ending);
                $('#modalText').attr("value", data.id);
                modalEnding(userItem);
                setInterval(function () {
                  modalEnding(userItem);
                }, 1000);    
                $('#dataImage').css("background-image","url('img/items/"+data.image+"')");
                $('#dataDescription').html(data.description);
                $('#exampleModalCenter').modal("show");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              toastr.error(XMLHttpRequest.responseJSON.message);
              $('.note-button').attr('disabled', false);
            }});
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          toastr.error(XMLHttpRequest.responseJSON.message);
          $('.note-button').attr('disabled', false);
      }});

    },

    addBid : function(data){
      var itemid = $('#modalText').attr("value");
      data['item_id'] = itemid;
      $.ajax({
        url: 'rest/bid',
        type: 'POST',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
          toastr.success("Bid succesful");
        }
      });
    }
};
