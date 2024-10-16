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
    document.body.classList.add('no-scroll');

}

registerBtn.onclick = function() {
    registerModal.showModal();
    document.body.classList.add('no-scroll');

}

// Закрыть модальное окно авторизации
closeLoginModal.onclick = function() {
    loginModal.close();
    document.body.classList.remove('no-scroll');

}

// Закрыть модальное окно регистрации
closeRegisterModal.onclick = function() {
    registerModal.close();
    document.body.classList.remove('no-scroll');

}

// Закрыть модальные окна при щелчке на свободной области
window.addEventListener('click', function(event) {
    if (event.target === loginModal) {
        loginModal.close();
    document.body.classList.remove('no-scroll');

    }
    if (event.target === registerModal) {
        registerModal.close();
    document.body.classList.remove('no-scroll');

    }
});

// Закрыть модальные окна на Esc
window.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        if (loginModal.open) {
            loginModal.close();
    document.body.classList.remove('no-scroll');

        }
        if (registerModal.open) {
            registerModal.close();
    document.body.classList.remove('no-scroll');

        }
    }
});




// document.querySelector('.modal-form_reg').addEventListener('submit', function (event) {
//     event.preventDefault();

//     const formData = new FormData(this);

//     fetch(this.action, {
//         method: 'POST',
//         body: formData,
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest',
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.errors) {
//             // Обработка ошибок валидации
//             console.log(data.errors);
//             // Здесь вы можете обновить ваш UI, чтобы показать ошибки
//         } else {
//             // Закрытие модального окна и сообщение успеха
//             alert(data.message);
//             document.getElementById('registerModal').close();
//             location.reload(); // Обновляем страницу после успешного запроса
//         }
//     })
//     .catch(error => {
//         console.error('Ошибка регистрации:', error);
//     });
// });



//===========================================
//Обработчики авторизации

    //обработка нажатия на кнопку регистрации
    document
    .querySelector(".modal-form_reg")
    .addEventListener("submit", function (event) {
        event.preventDefault();

        const name = document.getElementById("nameReg").value;
        const email = document.getElementById("emailReg").value;
        const password = document.getElementById("passwordReg").value;
        const password_confirmation = document.getElementById(
            "password_confirmation"
        ).value;
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const error = document.getElementById("error-reg");

        fetch("/checkData", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.result == "ok") {
                    event.target.submit();
                    error.style.display = "none";
                } else {
                    error.style.display = "block";
                    error.innerText = data.result;
                }
            })
            .catch((error) => {
                error.innerText = error;
                error.style.display = "block";
            });
    });


    //обработка нажатия на кнопку для входа в систему
document
    .querySelector(".modal-form_login")
    .addEventListener("submit", function (event) {
        event.preventDefault();

        const email = document.getElementById("emailLogin").value;
        const password = document.getElementById("passwordLogin").value;
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const error = document.getElementById("error-login");

        fetch("/checkUser", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                email: email,
                password: password,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.exists) {
                    event.target.submit();
                    error.style.display = "none";
                } else {
                    error.style.display = "block";
                    error.innerText = "Неверный Логин или Пароль";
                }
            })
            .catch((error) => {
                console.error("Ошибка:", error);
            });
    });
