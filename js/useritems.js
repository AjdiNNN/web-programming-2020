var itemNo = 0;
var msDay = 60*60*24*1000;
var msHours = 60*60*1000;
var msMinute = 60*1000;
function checkItems()
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
                $('#highestBid'+i).html("Current highest bid "+data[0].amount+"BAM");
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
setInterval(function () {
  checkItems();
}, 1000);

function modalEnding(){
  var currentDate = new Date().getTime();
          var endingDate = new Date( $('#modalDate').attr('datetime')).getTime();
          var diff = Math.floor((endingDate - currentDate) / msDay);
          if(diff<0)
          {
              $('#modalDate').html("Ended");
              $('#modalDate').css("background-color", "rgb(0, 0, 0)");
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
}
var itemService = {
    init: function(){
        itemService.list();
    },

    list: function(){
      $.ajax({
         url: "rest/useritems",
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
                <div class="col-md-6 col-lg-4 col-xl-3" data-toggle="modal" data-target="#exampleModalCenter" onclick="itemService.get(`+data[i].id+`)">
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
         url: 'rest/items/'+id,
         type: "GET",
         beforeSend: function(xhr){
           xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
         },
         success: function(data) {
          $('#dataTitle').html(data.title);
          $('#modalDate').attr("dateTime", data.ending);
          modalEnding();
          setInterval(function () {
            modalEnding();
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

    add: function(note){
      $.ajax({
        url: 'rest/notes',
        type: 'POST',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        data: JSON.stringify(note),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $("#note-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            MainService.list(); // perf optimization
            $("#addNoteModal").modal("hide");
            toastr.success("Note added!");
        }
      });
    },

    update: function(id, entity){
      $.ajax({
        url: 'rest/notes/'+id,
        type: 'PUT',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        data: JSON.stringify(entity),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $("#note-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            MainService.list(); // perf optimization
            $("#addNoteModal").modal("hide");
            toastr.success("Note updated!");
        }
      });
    },

    delete: function(id){
      $('.note-button').attr('disabled', true);
      $.ajax({
        url: 'rest/notes/'+id,
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        type: 'DELETE',
        success: function(result) {
            $("#note-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            MainService.list();
            toastr.success("Note deleted!");
        }
      });
    },

    choose_color: function(color){
      $('#addNoteForm input[name="color"]').val(color);
    },

    share: function(id){
      $('#shareNoteForm input[name="note_id"]').val(id);
      $('#shareModal').modal('show');
    },

    share_note : function(){
      var note_id = $('#shareNoteForm input[name="note_id"]').val();
      var recipient = $('#shareNoteForm input[name="recipient"]').val();

      $.ajax({
        url: 'rest/notes/'+note_id+'/share',
        type: 'POST',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        data: JSON.stringify({email: recipient}),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $("#shareModal").modal("hide");
            toastr.success("Note shared!");
        }
      });

    }


};