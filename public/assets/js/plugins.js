/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 4.3.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Common Plugins Js File
*/

//Common plugins
const scripts = [
    {condition: "[toast-list]", src: "https://cdn.jsdelivr.net/npm/toastify-js"},
    {condition: "[data-choices]", src: "/assets/libs/choices.js/public/assets/scripts/choices.min.js"},
    {condition: "[data-provider]", src: "/assets/libs/flatpickr/flatpickr.min.js"}
];

scripts.forEach(({condition, src}) => {
    if (document.querySelector(condition)) {
        const script = document.createElement("script");
        script.type = "text/javascript";
        script.src = src;
        document.body.appendChild(script);
    }
});