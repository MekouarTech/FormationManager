document.getElementById('registerForm').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirmpassword').value;

    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
