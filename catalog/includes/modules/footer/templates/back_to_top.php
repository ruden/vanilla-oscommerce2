<style>
  .back_to_top {
    position: fixed;
    bottom: 80px;
    right: 40px;
    z-index: 9999;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    background: #dcdcdc;
    color: #444;
    cursor: pointer;
    border-radius: 2px;
    display: none;
  }

  .back_to_top:hover {
    background: #b1b3b4;
  }

  .back_to_top-show {
    display: block;
  }
</style>

<a class="back_to_top">&uarr;</a>

<script>
  (function() {
    const goTopBtn = document.querySelector('.back_to_top');

    function trackScroll() {
      let scrolled = window.pageYOffset;
      let coords = document.documentElement.clientHeight;

      if (scrolled > coords) {
        goTopBtn.classList.add('back_to_top-show');
      }

      if (scrolled < coords) {
        goTopBtn.classList.remove('back_to_top-show');
      }
    }

    function backToTop() {
      if (window.pageYOffset > 0) {
        window.scrollBy(0, -80);
        setTimeout(backToTop, 0);
      }
    }

    window.addEventListener('scroll', trackScroll);
    goTopBtn.addEventListener('click', backToTop);
  })();
</script>