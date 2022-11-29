/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import { displayFile } from './displayFile.js'

// handle registration confirm Password
if(document.querySelector('#user_confirmPassword')) {
    const alert = document.querySelector('.error');
    const confirmPassword = document.querySelector('#user_confirmPassword');
    const password = document.querySelector('#user_password');
    const submit = document.querySelector('#user_submit');

    alert.style.visibility="hidden"
    confirmPassword.addEventListener('keyup', () => {
        if(password.value !== confirmPassword.value) {
            submit.classList.add("disabled")
            alert.style.visibility = 'visible'
            alert.innerHTML =''
            alert.innerHTML="Le mot de passe et la confimation sont différents"
        } else {
            submit.classList.remove("disabled")
            alert.style.visibility = 'hidden'
            alert.innerHTML=""
        }
    })
}
if(document.getElementById('project_image')) {
    displayFile('project_image', true)
}