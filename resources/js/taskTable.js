let update = false;

const form = document.getElementById('create-task-form');
const openButtonModule = document.getElementById('open-module-task-create');
const h2 = document.getElementById('module-task-h2');
const url = form.action;
const btnClose = document.querySelector("#modal-button-close");
const edits = document.querySelectorAll('.item_button_edit');
const btnDeletes = document.querySelectorAll(".item_button_delete");
const submitButton = document.getElementById('submit-button');
const loader = document.getElementById('loader');

//  Click button new task form
openButtonModule.addEventListener('click', () => {
    submitButton.textContent = "Create task";
    h2.textContent = "Create task";
    update = false;
    form.action = url;
});

//  Click button update task form
edits.forEach(edit => {
    edit.addEventListener('click', () => {
        const tr = edit.parentNode.parentNode;
        const id = tr.querySelector('.task__id>input').value,
            title = tr.querySelector('.task__title').textContent.trim(),
            description = tr.querySelector('.task__description').textContent.trim(),
            date = tr.querySelector('.task__date').textContent.trim();

        form.querySelector('input#task-id').value = id
        form.querySelector('input#task-title').value = title
        form.querySelector('textarea#task-description').value = description

        const parts = date.split('.');
        const day = parts[0];
        const month = parts[1];
        const year = parts[2];
        const formattedDate = `${year}-${month}-${day}`;
        form.querySelector('input#task-date').value = formattedDate;
        submitButton.textContent = "Update task";
        h2.textContent = "Update task "+title;
        form.action = edit.getAttribute('action')
    })
})

// Reset form for close modal form
btnClose.addEventListener('click', () => {
    form.reset();
})

// Delete button
btnDeletes.forEach(del => {
    del.addEventListener('click', () => {
        showLoader();
        const tr = del.parentNode.parentNode;
        const id = tr.querySelector('.task__id>input').value;
        console.log('Delete task')
        axios.post(del.getAttribute('action'), {
            id: id
        })
            .then(response => {
                showLoader(false);
                // Успешно удалено, выполните необходимые действия
                console.log('Задача удалена успешно:', response);
                // Например, удалить строку из таблицы
                tr.remove();
            })
            .catch(error => {
                showLoader(false);
                // Обработка ошибок удаления
                console.error('Ошибка при удалении задачи:', error);
                // Возможно, показать сообщение об ошибке
            });
    });
});
// Loader
function showLoader(show = true) {
    if (show) {
        loader.style.display = 'block';
    } else {
        loader.style.display = 'none';
    }
}
