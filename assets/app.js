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

import { displayFile } from './JS/displayFile'
import { Parallax } from './JS/parallax'
import { Observer } from './JS/observer'

// handle registration confirm Password
if (document.querySelector('#user_confirmPassword')) {
    const alert = document.querySelector('.error');
    const confirmPassword = document.querySelector('#user_confirmPassword');
    const password = document.querySelector('#user_password');
    const submit = document.querySelector('#user_submit');

    alert.style.visibility = "hidden"
    confirmPassword.addEventListener('keyup', () => {
        if (password.value !== confirmPassword.value) {
            submit.classList.add("disabled")
            alert.style.visibility = 'visible'
            alert.innerHTML = ''
            alert.innerHTML = "Le mot de passe et la confimation sont différents"
        } else {
            submit.classList.remove("disabled")
            alert.style.visibility = 'hidden'
            alert.innerHTML = ""
        }
    })
}
if (document.getElementById('project_image')) {
    displayFile('project_image', true, "image")
}

if (document.getElementById('infos_photo')) {
    displayFile('infos_photo', false, "image");
    displayFile('infos_cv', false, "pdf")
}


//opacity header according scroll
if (document.querySelector('.header')) {

    window.addEventListener("scroll", () => {
        let scroll = window.scrollY;
        if (scroll > 200) {
            document.querySelector('.header').style.background = "#171717F3";
        }
        else {
            document.querySelector('.header').style.background = "#17171700";
        }
    })

}



Parallax.bind()

let observerOptions = {
    rootMargin: '-10% 0px -10% 0px',
}

let elementsObserve = document.querySelectorAll("[data-observer]");

elementsObserve.forEach((element) => {
    new Observer(element, observerOptions)
})


let linkMenu = document.querySelectorAll("header .nav-link");

linkMenu.forEach((link) => {
    link.addEventListener("click", (e) => {
        e.preventDefault()
        let path = window.location.href;

        if(path.search("/mentions-legales") !== -1)
        {
            window.location.href = link.getAttribute('href');
        } else {
         let el =  document.querySelector(link.getAttribute("href").substring(1));
         window.scroll(0 , el.offsetTop - 100)

        }
    })
})


if (document.getElementById("portfolio")) {
    var closes = document.querySelectorAll(".close");


    for (close of closes) {
        close.addEventListener("click", function (e) {

            e.preventDefault()
            var scroll = window.scrollY;
            window.location.href = "/#";
            window.scrollTo({
                top: scroll,
                left: 0,
            })

        })
    }
}

var iconMenu = document.querySelector(".icon-menu");

iconMenu.addEventListener("click", function () {
    document.querySelector("body").classList.toggle("with-menu");
    iconMenu.classList.toggle("active")
})


if (document.getElementsByClassName('with-menu')) {
    var menu_items = document.querySelectorAll('.nav-link');
    for (var n = 0; n < menu_items.length; n++) {
        menu_items[n].addEventListener("click", function () {

            setTimeout(function() {
                document.querySelector("body").classList.remove("with-menu");
                iconMenu.classList.remove("active")

            }, 500)
            
        })
    }
}











