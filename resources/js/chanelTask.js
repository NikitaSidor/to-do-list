import Pusher from 'pusher-js';

const submitButton = document.getElementById('submit-button');
const form = document.getElementById('create-task-form');
const loader = document.getElementById('loader');
let updataId = [
    'id',
    'date',
];

//  form action
submitButton.addEventListener('click', function () {
    showLoader()
    // Prevent default form submission
    event.preventDefault();

    // Serialize form data
    const formData = new FormData(form);

    // Send POST request using Axios
    axios.post(form.action, formData)
        .then(function (response) {
            showLoader(false)
            const data = response.data
            console.log(data.message);
            updataId['id'] = data.id;
            updataId['date'] = data.date_update;
            let tr = findTrKey(data.id);
            if(tr) {
                updateTr(tr, data.task);
            } else {
                insertTrTask(data.task)
            }


            form.reset();
        })
        .catch(function (error) {
            console.error(error);
        });
});

// chanel
var pusher = new Pusher( import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});
var TaskChannel = pusher.subscribe('task');

TaskChannel.bind('store-task', function(data) {
    if(updataId['id'] !== data.id && updataId['date'] !== data.date_update) {
        console.log('Show task');
        insertTrTask(data.task)
    }
});

TaskChannel.bind('update-task', function(data) {
    if(updataId['id'] !== data.id && updataId['date'] !== data.date_update) {
        console.log('Show update task');
        let tr = findTrKey(data.id);
        updateTr(tr, data.task);
    }
});
TaskChannel.bind('done-task', function(data) {
    if(updataId['id'] !== data.id && updataId['date'] !== data.date_update) {
        console.log('Show update done task');
        let tr = findTrKey(data.id);
        tr.querySelector('input').checked = data.done
        tr.classList.toggle('text-done', data.done)
    }
});

TaskChannel.bind('delete-task', function(data) {
    const tr = findTrKey(data.id);
    if(tr) {
        console.log('Show delete task');
        tr.remove();
    }
});

function updateTr(tr, data) {
    tr.classList.toggle('text-done', data.done);
    tr.getElementsByClassName(`${data.title.class}`)[0].textContent = data.title.value;
    tr.getElementsByClassName(`${data.description.class}`)[0].textContent = data.description.value;
    tr.getElementsByClassName(`${data.date.class}`)[0].textContent = data.date.value;
}

function findTrKey(value) {
    let elem = document.querySelector('.task__id input[value="'+value+'"');

    if (!elem) return null

    return elem.parentNode.parentNode
}

function createTdCheckbox(dataCheckbox) {
    let checkbox = document.createElement('td');
    let inputCheckbox = document.createElement('input');

    inputCheckbox.name = dataCheckbox.name;
    inputCheckbox.value = dataCheckbox.key; // изменено с task.checkbox.key на dataCheckbox.key
    inputCheckbox.type = 'checkbox';

    checkbox.classList.add('table__cell', 'table__cell--checkbox', 'table__cell--no-wrap', 'task__id');
    checkbox.append(inputCheckbox);
    return checkbox;
}

function createTdText(data) {
    let td = document.createElement('td');
    td.className = data.class;
    td.classList.add('table__cell');
    td.textContent = data.value;
    return td;
}

function createTdButton(className, title, svgPath, eventHandler) {
    let td = document.createElement('td');
    let button = document.createElement('button');
    button.classList = className;
    button.type = 'button'; // тип изменен на 'button'

    button.setAttribute('x-data', '');
    button.setAttribute('title', title);
    button.addEventListener('click', eventHandler);

    let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '24');
    svg.setAttribute('height', '24');
    svg.setAttribute('viewBox', '0 0 64 64');
    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    svg.setAttribute('fill', 'white');

    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', svgPath);

    svg.appendChild(path);
    button.appendChild(svg);
    td.classList = 'table__cell table__cell--remainder';
    td.append(button);
    return td;
}

function createTrTask(task) {
    let tr = document.createElement('tr');

    const checkbox = createTdCheckbox(task.checkbox);
    const title = createTdText(task.title);
    const description = createTdText(task.description);
    const date = createTdText(task.date);

    const editButton = createTdButton(
        'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150',
        'Редактировать',
        'm43.586 19-28.586 28.586v-1.586a1 1 0 0 0 -.684-.949l-1.465-.488 28.149-28.149zm-32.1 27.217 1.513.5v3.283a1 1 0 0 0 1 1h3.279l.5 1.513-6.445 3.411-3.258-3.262zm-4.386 8.294 2.39 2.389-5.09 2.7zm12.341-3.362-.489-1.465a1 1 0 0 0 -.952-.684h-1.586l28.586-28.586 2.586 2.586zm30.2-28.928-7.856-7.856 2.98-1.192 6.067 6.068zm2.606-4.394-6.067-6.068 1.191-2.98 7.856 7.856zm7.991-7.473-4.238 4.232-6.586-6.586 4.232-4.232a2.714 2.714 0 0 1 3.708 0l2.878 2.878a2.622 2.622 0 0 1 0 3.708z',
        function(event) {
            event.preventDefault();
            this.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-user-deletion' }));
        }
    );

    const deleteButton = createTdButton(
        'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150',
        'Удалить',
        'M 28 6 C 25.791 6 24 7.791 24 10 L 24 12 L 23.599609 12 L 10 14 L 10 17 L 54 17 L 54 14 L 40.400391 12 L 40 12 L 40 10 C 40 7.791 38.209 6 36 6 L 28 6 z M 28 10 L 36 10 L 36 12 L 28 12 L 28 10 z M 12 19 L 14.701172 52.322266 C 14.869172 54.399266 16.605453 56 18.689453 56 L 45.3125 56 C 47.3965 56 49.129828 54.401219 49.298828 52.324219 L 51.923828 20 L 12 19 z M 20 26 C 21.105 26 22 26.895 22 28 L 22 51 L 19 51 L 18 28 C 18 26.895 18.895 26 20 26 z M 32 26 C 33.657 26 35 27.343 35 29 L 35 51 L 29 51 L 29 29 C 29 27.343 30.343 26 32 26 z M 44 26 C 45.105 26 46 26.895 46 28 L 45 51 L 42 51 L 42 28 C 42 26.895 42.895 26 44 26 z',
        function(event) {
            event.preventDefault();
            this.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-user-deletion' }));
        }
    );

    tr.append(checkbox);
    tr.append(title);
    tr.append(description);
    tr.append(date);
    tr.append(editButton);
    tr.append(deleteButton);

    return tr;
}

function insertTrTask(task) {
    const tbody = document.querySelector('.table__body');
    const tr = createTrTask(task)
    tbody.appendChild(tr);
}



// Loader
function showLoader(show = true) {
    if (show) {
        loader.style.display = 'block';
    } else {
        loader.style.display = 'none';
    }
}
