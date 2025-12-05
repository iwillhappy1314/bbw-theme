import Splide from '@splidejs/splide';
import '@splidejs/splide/css';
import {CountUp} from 'countup.js';

if ($('#rs-news-slider').length > 0) {
    new Splide('#rs-news-slider', {
        type    : 'loop',
        autoplay: true,
        arrows  : false,
        perPage : 1,
    }).mount();
}

window.onload = function() {
    new CountUp('rscp-1', 2013,{
        separator: '',
        enableScrollSpy: true,
        scrollSpyDelay: 0,
    }).start();

    new CountUp('rscp-2', 32, {
        enableScrollSpy: true,
        scrollSpyDelay: 100,
    }).start();

    new CountUp('rscp-3', 100, {
        enableScrollSpy: true
    }).start();

    new CountUp('rscp-4', 5, {
        enableScrollSpy: true
    }).start();
};
