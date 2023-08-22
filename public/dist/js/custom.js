//$(".alert_messages").fadeOut(4000);


setTimeout(function(){
    $('.alert_messages').fadeOut();
}, 3000);

 //$('.alert_messages').fadeIn().delay(10000).fadeOut();

$('#admin_profile_form').validate({

  rules: {
    name: { required: true, },
    phone_number: { required: true, number: true },
  },
  messages: {
    name: { required: "Name is required", },
    phone_number: { required: "Phone number is required", },
  }
});

//validate admin password page
$('#admin_password_form').validate({

  rules: {
    current_password: { required: true },
    new_password: { required: true, },
    confirm_password: { required: true, },
  },
  messages: {
    current_password: { required: "jquery Current Password is required", },
    new_password: { required: "New  Password is required", },
    confirm_password: { required: "Confirm Password is required", },
  }

});



//password and confirm password matching
$('#confirm_password').on('keyup', function () {
  var password = $('#new_password').val();
  var confirm_password = $(this).val();
  //  alert(password);
  if (confirm_password != '') {
    if (password != confirm_password) {
      $('#check_password_match').html('Confirm password is not matched with password !').css("color", "red");
    } else {
      $('#check_password_match').html('Confirm password is matched').css("color", "green");
    }
  }
  else {
    $('#check_password_match').html('');
  }


});

//sweet alert

$('.show_sweetalert').click(function (event) {
  var form = $(this).closest("form");
  //  var name = $(this).data("name");
  event.preventDefault();
  swal({
    title: `Are you sure you want to delete this record?`,
    text: "If you delete this, it will be gone forever.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        form.submit();
      }
    });
});

//sweet alert for confirm make team win

$('.winBtn').click(function (event) {
  var form = $(this).closest("form");
  event.preventDefault();
  swal({
    title: 'Are you sure to make this team win?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    buttons: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    // confirmButtonText: 'Yes, delete it!',
    // reverseButtons: true
  }).then((result) => {
    if (result) {

      form.submit();
    }
  });
});


//front register form validation
$('#register_form').validate({
  rules: {
    fname: { required: true },
    birthday: { required: true },
    email: { required: true, email: true },
    password: { required: true },
    password_confirmation: { required: true },
    phone: { required: true },
    address: { required: true },
    city: { required: true },
    zipcode: { required: true },
    id_proof: { required: true },
    country: { required: true },
 id_proof_number: { required: true },
  },
  messages: {
    fname: { required: "Name is required", },
    birthday: { required: "Date of birth is required", },
    email: { required: "Email is required", },
    password: { required: "Password is required", },
    password_confirmation: { required: "Password Confirmation is required", },
    phone: { required: "Phone is required", },
    address: { required: "Address is required", },
    city: { required: "City is required", },
    zipcode: { required: "Zipcode is required", },
    country: { required: "Country is required", },
    id_proof: { required: "ID Proof is required", },
  id_proof_number: { required: "ID Proof Number is required", },
  }
});



//front js 
// banner carousel js from footer file
$(document).ready(function () {
  $(".owl-carousel").owlCarousel();
});

$(".owl-heroSlider").owlCarousel({

  loop: true,
  items: 1,
  margin: 0,
  dots: false,
  autoplay: true,
  nav: true,
  dots: false,
});

$(".owl-testimonial").owlCarousel({

  loop: true,
  items: 1,
  margin: 30,
  dots: false,
  autoplay: true,
  nav: true,
  dots: false,
  responsive: {
    300: {
      items: 1,
    },
    600: {
      items: 2,
    },
    992: {
      items: 3,
    },
  },
});

$(".owl-videoslider").owlCarousel({

  loop: true,
  margin: 20,
  dots: false,
  autoplay: true,
  nav: false,
  responsive: {
    300: {
      items: 1,
    },
    600: {
      items: 2,
    },
    992: {
      items: 3,
    },
  },
});

// video player js 
const video = document.getElementById("video");
const circlePlayButton = document.getElementById("circle-play-b");

function togglePlay() {
  if (video.paused || video.ended) {
    video.play();
  } else {
    video.pause();
  }
}

circlePlayButton.addEventListener("click", togglePlay);
video.addEventListener("playing", function () {
  circlePlayButton.style.opacity = 0;
});
video.addEventListener("pause", function () {
  circlePlayButton.style.opacity = 1;
});



  //Pick the team from fixture page 
            $('.team_name').click(function() {

                let season_id = $(this).attr('season_id');
                let fixture_id = $(this).attr('fixture_id');
                let team_id = $(this).attr('team_id');
                let teamName = $(this).attr('teamName');
                let fixture_date = $(this).attr('fixture_date');
                let fixture_time = $(this).attr('fixture_time');
                let week = $(this).attr('week');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    // url: '/check_user',
                    url: '/fixture_team_pick',
                    data: {
                        season_id: season_id,
                        fixture_id: fixture_id,
                        team_id: team_id,
                        week: week
                    },
                    success: function(resp) {
                        if (resp.message == 'login') {
                            let login_url = "{{ route('login') }}";
                            location.href = login_url;
                            $('#selectTeam #teamSelectedMsg').html(
                                '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Please <a href="' +
                                login_url +
                                '" style="color:red">login</a> first to continue . </span></p>'
                                );
                        }
                        if (resp.message == 'subscribe') {
                            $('#login_msg_div').hide();
                            let payment_url = "{{ route('payment') }}";
                            location.href = payment_url;
                            $('#selectTeam #teamSelectedMsg').html(
                                '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Please <a href="' +
                                payment_url +
                                '" style="color:red">subscribe</a> to pick the teams . It will cost you $100 . </span></p>'
                            );
                        }
                        if (resp.message == 'update') {
                            $('#selectTeam #teamSelectedMsg').html(
                                'You have selected <span style="color:#06083B">' +
                                teamName +
                                '</span> for the week  <span style="color:#06083B"> ' +
                                week + ' </span> on <span style="color:#06083B">' +
                                fixture_date + '</span> at <span style="color:#06083B">' +
                                fixture_time + '</span>');
                        }
                        if (resp.message == 'added') {
                            $('#selectTeam #teamSelectedMsg').html(
                                'You have selected <span style="color:#06083B;">' +
                                teamName +
                                '</span> for the week <span style="color:#06083B"> ' +
                                week + ' </span> on <span style="color:#06083B">' +
                                fixture_date + '</span> at <span style="color:#06083B">' +
                                fixture_time + '</span>');
                        }
                        if (resp.message == 'Time_id_over') {
                            $('#selectTeam #teamSelectedMsg').html(
                                '<p style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"> <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"> </polygon> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line>  </svg> <span> Your time is over to pick the team  as you can pick the team till Thursaday 12:00 am . You will receive loss for this week  </span></p>'
                            );
                        }

                    },
                })
            });



//update user  password form in user dashboard
$("#updatePasswordForm").submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                data: $("#updatePasswordForm").serialize(),
                url: 'update-password',
                success: function(data) {
                    //console.log(data);
                    if (data.status == true) {
                        $("#password_success_modal").modal("show");
                     $("#password_success_modal #pass_success_msg").html("Password updated successfully .");
                     $('#updatePasswordForm')[0].reset();
                    } else {
                        $("#password_success_modal").modal("hide");
                        $("#password_fail_modal").modal("show");
                        $("#password_fail_modal #pass_fail_msg").html(data.message);
                        $('#updatePasswordForm')[0].reset();
                    }

                }
            });
            return false;
        }); 


 // front contact form validation
     $('#contactForm').validate({
        rules: {
            name:   {  required: true},
            email:      {  required: true ,  email: true},
            subject:    {  required: true},
            message:    {  required: true},

        },
        messages: {
            name:   {  required: "Name is required",  },
            email:      {  required: "Email is required",  },
            subject:    {  required: "Subject is required",  },
            message:    {  required: "Message is required",  },

        }
    });

