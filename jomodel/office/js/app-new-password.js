var togglePasswordNewPassword = document.querySelector('#togglePasswordNewPassword');
var neWpassword = document.querySelector('#passwordNew');

togglePasswordNewPassword.addEventListener('click', function (e) {
    // toggle the type attribute
    var type = neWpassword.getAttribute('type') === 'password' ? 'text' : 'password';
    neWpassword.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
