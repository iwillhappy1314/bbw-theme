const serviceOnline = (function() {
  const scrollSidebar = document.getElementById('content-third');
  let timer = null;

  //悬浮QQ匀速移动
  const startMove = function(argument) {
    clearInterval(timer);
    timer = setInterval(function() {
      let speed = (argument - scrollSidebar.offsetTop) / 4;
      speed = speed > 0 ? Math.ceil(speed) : Math.floor(speed);
      if (argument === scrollSidebar.offsetTop) {
        clearInterval(timer);
      } else {
        scrollSidebar.style.top = scrollSidebar.offsetTop + speed + 'px';
      }
    }, 20);
  };

  //鼠标移动
  const scrollMove = function() {
    window.onscroll = window.onload = function() {
      const scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
      startMove(
          parseInt((document.documentElement.clientHeight - scrollSidebar.offsetHeight) / 2 + scrollTop),
      );
    };
  };

  //返回方法
  return {
    init: function() {
      scrollMove();
    },
  };
})();

export default serviceOnline;