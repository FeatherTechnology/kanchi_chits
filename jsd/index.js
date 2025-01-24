// $.post('api/base_api/getSessionData.php', function (response) {
//     if (response != null) {
//         $(document).load('home.php');
//     }
// })

$(document).ready(function () {
    $('#lbutton').click(function (event) {
        event.preventDefault();
        let user_name = $('#lusername').val();
        let password = $('#lpassword').val();
        $.post('api/base_api/login.php', { user_name, password }, function (response) {
            if (response == 'Success') {
                swalSuccess('Login Successful', 'Welcome to Chit Software');
                setTimeout(() => {
                    window.location.href = 'home.php';
                }, 2000);
            } else {
                swalError('Invalid Credentials', 'Try Login Again');
                $('#loginform input').val('');//clears data inside inputs
            }
        }, 'json')
    })
})  