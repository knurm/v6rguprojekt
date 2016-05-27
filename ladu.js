'use strict';

document.querySelector('#kuva-nupp button').addEventListener('click',
    function () {
        document.getElementById('lisa-vorm').style.display = 'block';
        document.getElementById('kuva-nupp').style.display = 'none';
    });

document.querySelector('#peida-nupp button').addEventListener('click',
    function () {
        document.getElementById('lisa-vorm').style.display = 'none';
        document.getElementById('kuva-nupp').style.display = 'block';
    });

document.getElementById('lisa-vorm').addEventListener('submit',
    function (event) {
        var nimetus = document.getElementById('nimetus').value;
        var kogus = Number(document.getElementById('kogus').value);

        if (!nimetus || kogus <= 0) {
            alert('Vigased väärtused!');
            event.preventDefault();
            return;
        }
    });