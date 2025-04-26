'use strict';
import Popover from 'bootstrap/js/dist/popover';
import './popover.scss';

const popoverElementList = document.querySelectorAll('.rs-popover');

[...popoverElementList].map(function(popoverEl) {
    new Popover(popoverEl);
});
