const form = document.getElementById('form-search')
const formError = form.querySelector('#error')

form.addEventListener('submit', searchPost)

async function searchPost (e) {
    e.preventDefault()
    formError.classList.add('none')
    let formData = new FormData(form)
    const formInput = document.getElementById('form__input')
    // Отправка данных с формы
    if (formInput.value !== '' && formInput.value.length >= 3) {
        let response = await fetch('./php/search.php', {
            method: 'POST',
            body: formData
        })

        // Получение дынных с БД и добавление из в HTML файл
        if (response.ok) {
            let result = await response.json()
            if (result.status === true) {
                const groupPosts = document.querySelector('.posts-group')
                for (let keyArr in result.data) {
                    let value = result.data[keyArr]
                    console.log(value)
                    let postHTML = `
                        <div class="post__item">
                            <div class="post">Пост: ${value['title']}</div>
                            <div class="comments">Комментарии: </div>
                        </div>
                    `
                    groupPosts.insertAdjacentHTML('afterbegin', postHTML)
                    const comments = groupPosts.querySelector('.comments')
                    value['body'].forEach(el => {
                        let commentHTML = `
                            <div class="comment">${el}</div>
                        `
                        comments.insertAdjacentHTML('beforeend', commentHTML)
                    })
                }

                form.reset()
            } else {
                formError.textContent = result.message
                formError.classList.remove('none')
            }
        } else {
            alert('error')
            form.reset()
        }
    } else {
        formError.textContent = '*Ввод от 3х символов!'
        formError.classList.remove('none')
    }
}