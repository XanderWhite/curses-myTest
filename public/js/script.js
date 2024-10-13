document.querySelector('.modal-form_reg').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            // Обработка ошибок валидации
            console.log(data.errors);
            // Здесь вы можете обновить ваш UI, чтобы показать ошибки
        } else {
            // Закрытие модального окна и сообщение успеха
            alert(data.message);
            document.getElementById('registerModal').close();
            location.reload(); // Обновляем страницу после успешного запроса
        }
    })
    .catch(error => {
        console.error('Ошибка регистрации:', error);
    });
});