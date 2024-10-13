// Получаем элементы модальных окон
let loginModal = document.getElementById("loginModal");
let registerModal = document.getElementById("registerModal");

// Получаем кнопки, которые открывают модальные окна
let loginBtn = document.getElementById("loginBtn");
let registerBtn = document.getElementById("registerBtn");

// Получаем элементы, которые закрывают модальные окна
let closeLoginModal = document.getElementById("closeLoginModal");
let closeRegisterModal = document.getElementById("closeRegisterModal");


// Открываем модальные окна
loginBtn.onclick = function() {
    loginModal.showModal();
}

registerBtn.onclick = function() {
    registerModal.showModal();
}

// Закрыть модальное окно авторизации
closeLoginModal.onclick = function() {
    loginModal.close();
}

// Закрыть модальное окно регистрации
closeRegisterModal.onclick = function() {
    registerModal.close();
}

// Закрыть модальные окна при щелчке на свободной области
window.addEventListener('click', function(event) {
    if (event.target === loginModal) {
        loginModal.close();
    }
    if (event.target === registerModal) {
        registerModal.close();
    }
});

// Закрыть модальные окна на Esc
window.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        if (loginModal.open) {
            loginModal.close();
        }
        if (registerModal.open) {
            registerModal.close();
        }
    }
});