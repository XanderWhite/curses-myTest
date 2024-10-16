const profileForm = document.getElementById("profileForm");
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const passwordConfirmationInput = document.getElementById(
    "password_confirmation"
);
const submitButton = document.querySelector(".profile-btn");

function validateForm() {
    const nameValid = nameInput.value.length >= 3;
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
    const passwordValid =
        passwordInput.value.length >= 8 || passwordInput.value.length == 0;
    const passwordsMatch =
        passwordInput.value === passwordConfirmationInput.value;

    submitButton.disabled = !(
        nameValid &&
        emailValid &&
        passwordValid &&
        passwordsMatch
    );
}

nameInput.addEventListener("input", validateForm);
emailInput.addEventListener("input", validateForm);
passwordInput.addEventListener("input", validateForm);
passwordConfirmationInput.addEventListener("input", validateForm);

function previewAvatar(event) {
    const input = event.target;
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('avatarPreview');
        img.src = e.target.result;
        // Включить кнопку "Сохранить изменения", если файл выбран
        document.querySelector('.profile-btn').disabled = false;
    }
    if (file) {
        reader.readAsDataURL(file);
    }
}

function confirmDeleteAvatar() {
    const img = document.getElementById('avatarPreview');
    img.src = 'images/avatar-default.png'; // Установить изображение по умолчанию
    document.querySelector('.profile-btn').disabled = false; // Включить кнопку "Сохранить изменения"
    return true; // Убедиться, что форма отправляется
}