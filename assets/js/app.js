/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import 'bootstrap';
require('../css/app.scss');


let menuOpen = document.getElementById('newscan-menu');
let menuClose = document.getElementById('close-menu');
let menu = document.getElementById('hidden-menu');

menuOpen.addEventListener('click', openMenu);
menuClose.addEventListener('click', closeMenu);

function openMenu(event) {
    menu.style.display = "block";
}
function closeMenu(event) {
    menu.style.display = "none";
}

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');