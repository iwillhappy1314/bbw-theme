'use strict';
import Dropdown from 'bootstrap/js/dist/dropdown';
import './dropdown.scss';

const dropdownElementList = document.querySelectorAll('.rs-dropdown-toggle');

[...dropdownElementList].map(function(dropdownToggleEl) {
    new Dropdown(dropdownToggleEl);
});