var userService = {
    init: function(){
      $('#registerForm').validate({
        rules: {
            username: {
                minlength: 4,
                required: true,
                maxlength: 32,
            },
            fname: {
                minlength: 4,
                required: true,
                maxlength: 32,
            },
            sname: {
                minlength: 4,
                required: true,
                maxlength: 32,
            },
            email: {
                required: true,
            },
            password: {
                minlength: 6,
                maxlength: 30,
                required: true
            }
        },
        errorElement: "div",
            errorPlacement: function ( error, element ) {
                error.addClass( "invalid-feedback" );
                error.insertAfter( element );
            },
            highlight: function(element) {
                $(element).removeClass('is-valid').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form) {
          var user = Object.fromEntries((new FormData(form)).entries());
          userService.add(user);
        }
      });
    },


    get: function(id){
      $('.todo-button').attr('disabled', true);
      $.get('rest/user/'+id, function(data){
        $("#description").val(data.description);
        $("#id").val(data.id);
        $("#created").val(data.created);
        $("#exampleModal").modal("show");
        $('.todo-button').attr('disabled', false);
      })
    },

    add: function(todo){
      $.ajax({
        url: 'rest/user',
        type: 'POST',
        data: JSON.stringify(todo),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $("#register").modal("hide");
        }
      });
    },

    update: function(){
      $('.save-todo-button').attr('disabled', true);
      var todo = {};

      todo.description = $('#description').val();
      todo.created = $('#created').val();

      $.ajax({
        url: 'rest/todos/'+$('#id').val(),
        type: 'PUT',
        data: JSON.stringify(todo),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            $("#exampleModal").modal("hide");
            $('.save-todo-button').attr('disabled', false);
            $("#todo-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            ToDoService.list(); // perf optimization
        }
      });
    },

    delete: function(id){
      $('.todo-button').attr('disabled', true);
      $.ajax({
        url: 'rest/todos/'+id,
        type: 'DELETE',
        success: function(result) {
            $("#todo-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            ToDoService.list();
        }
      });
    },
}