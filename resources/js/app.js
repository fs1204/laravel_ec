import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ドキュメントのget startedから
// Install from NPM より
// import Swiper JS
import Swiper from 'swiper';
// import Swiper styles
import 'swiper/swiper-bundle.css';


// デフォルトのswiperは機能がほぼないので、追加機能・追加モジュールということで、ナビゲーションやページネーションを追加する。
// core version + navigation, pagination modules:
import SwiperCore, { Navigation, Pagination } from 'swiper/core';
SwiperCore.use(Navigation, Pagination);

// init Swiper:
// const swiper = new Swiper('.swiper', {
//   // configure Swiper to use modules
//   modules: [Navigation, Pagination],
//   ...
// });


// Initialize Swiperより

const swiper = new Swiper('.swiper', {
    // Optional parameters
    // direction: 'vertical',   ここはコメントアウトしておく  一般的には横方向へのスライドが多い  こちらは縦方向
    loop: true,

    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },
  });
