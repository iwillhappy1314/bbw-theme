/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/index.js":
/*!**********************************************************************************************!*\
  !*** ./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/index.js ***!
  \**********************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = (el, options = {}) => {
  const { ScrollSpy } = __webpack_require__(/*! ./scrollspy */ "./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/scrollspy.js")
  const instance = new ScrollSpy(el, options)

  window.onload = instance.onScroll()
  window.addEventListener('scroll', () => instance.onScroll())

  return instance
}


/***/ }),

/***/ "./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/scrollspy.js":
/*!**************************************************************************************************!*\
  !*** ./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/scrollspy.js ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ScrollSpy: () => (/* binding */ ScrollSpy)
/* harmony export */ });
class ScrollSpy {
  constructor(menu, options = {}) {
    if (!menu) {
      throw new Error('First argument is query selector to your navigation.')
    }

    if (typeof options !== 'object') {
      throw new Error('Second argument must be instance of Object.')
    }

    let defaultOptions = {
      sectionClass: '.scrollspy',
      menuActiveTarget: 'li > a',
      offset: 0,
      hrefAttribute: 'href',
      activeClass: 'active',
      scrollContainer: '',
      smoothScroll: {},
    }

    options.smoothScroll = options.smoothScroll === true && {} || options.smoothScroll

    this.menuList = menu instanceof HTMLElement ? menu : document.querySelector(menu)
    this.options = Object.assign({}, defaultOptions, options)

    if(this.options.scrollContainer) {
      this.scroller = this.options.scrollContainer instanceof HTMLElement ? this.options.scrollContainer : document.querySelector(this.options.scrollContainer)
    } else {
      this.scroller = window
    }

    this.sections = document.querySelectorAll(this.options.sectionClass)

    this.attachEventListeners()
  }

  attachEventListeners() {
    if (this.scroller) {
      this.scroller.addEventListener('scroll', () => this.onScroll())

      // smooth scroll
      if (this.options.smoothScroll) {
        const menuItems = this.menuList.querySelectorAll(this.options.menuActiveTarget)
        menuItems.forEach((item) => item.addEventListener('click', this.onClick.bind(this)))
      }
    }
  }

  onClick(event) {
    const targetSelector = event.target.getAttribute(this.options.hrefAttribute)
    const targetElement = document.querySelector(targetSelector)

    if (targetElement && this.options.smoothScroll) {
      // prevent click event
      event.preventDefault()
      // handle smooth scrolling to 'targetElement'
      this.scrollTo(targetElement)
    }
  }

  onScroll() {
    const section = this.getSectionInView()
    const menuItem = this.getMenuItemBySection(section)

    if (menuItem) {
      this.removeCurrentActive({ ignore: menuItem })
      this.setActive(menuItem)
    }
  }

  scrollTo(targetElement) {
    const smoothScrollBehavior = typeof this.options.smoothScrollBehavior === 'function' && this.options.smoothScrollBehavior

    if (smoothScrollBehavior) {
      smoothScrollBehavior(targetElement, this.options.smoothScroll)
    } else {
      targetElement.scrollIntoView({
        ...this.options.smoothScroll,
        behavior: 'smooth',
      })
    }
  }

  getMenuItemBySection(section) {
    if (!section) return
    const sectionId = section.getAttribute('id')
    return this.menuList.querySelector(`[${this.options.hrefAttribute}="#${sectionId}"]`)
  }

  getSectionInView() {
    for (let i = 0; i < this.sections.length; i++) {
      const startAt = this.sections[i].offsetTop
      const endAt = startAt + this.sections[i].offsetHeight
      let currentPosition = (document.documentElement.scrollTop || document.body.scrollTop) + this.options.offset

      if(this.options.scrollContainer && this.scroller) {
        currentPosition = (this.scroller.scrollTop) + this.options.offset
      }

      const isInView = currentPosition > startAt && currentPosition <= endAt
      if (isInView) return this.sections[i]
    }
  }

  setActive(activeItem) {
    const isActive = activeItem.classList.contains(this.options.activeClass)
    if (!isActive) {
      activeItem.classList.add(this.options.activeClass)
      if (typeof this.options.onActive === 'function') {
        this.options.onActive(activeItem)
      }
    }
  }

  removeCurrentActive({ ignore }) {
    const { hrefAttribute, menuActiveTarget, activeClass } = this.options
    const items = `${menuActiveTarget}.${activeClass}:not([${hrefAttribute}="${ignore.getAttribute(hrefAttribute)}"])`
    const menuItems = this.menuList.querySelectorAll(items)

    menuItems.forEach((item) => item.classList.remove(this.options.activeClass))
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*********************************************!*\
  !*** ./assets/scripts/modules/scrollspy.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var simple_scrollspy__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! simple-scrollspy */ "./node_modules/.pnpm/simple-scrollspy@2.4.0/node_modules/simple-scrollspy/src/index.js");
/* harmony import */ var simple_scrollspy__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(simple_scrollspy__WEBPACK_IMPORTED_MODULE_0__);

simple_scrollspy__WEBPACK_IMPORTED_MODULE_0___default()('.rs-scroll-nav', {
  scrollContainer: '.rs-scroll-container',
  sectionClass: '.rs-scroll-section',
  menuActiveTarget: '.menu-item',
  offset: 100,
  smoothScroll: false
});
})();

/******/ })()
;
//# sourceMappingURL=scrollspy.js.map