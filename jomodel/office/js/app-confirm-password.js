var togglePasswordConfirmPassword = document.querySelector('#togglePasswordConfirmPassword');
var confirMpassword = document.querySelector('#passwordConfirm');

togglePasswordConfirmPassword.addEventListener('click', function (e) {
    // toggle the type attribute
    var type = confirMpassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirMpassword.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
