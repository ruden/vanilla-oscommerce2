<a class="t-back-to-top">&uarr;</a>

<script>
  (function () {
    const style = document.createElement('style')

    style.innerHTML = '.t-back-to-top{position:fixed;bottom:80px;right:40px;z-index:9999;width:2.5rem;height:2.5rem;text-align:center;line-height:2rem;background:var(--bs-secondary);color:var(--bs-white);cursor:pointer;border-radius:2px;display:none}.t-back-to-top:hover{background:var(--bs-light);}'
    document.head.appendChild(style)

    const goTopBtn = document.querySelector('.t-back-to-top')

    function trackScroll() {
      let scrolled = window.pageYOffset;
      let coords = document.documentElement.clientHeight;

      if (scrolled > coords) {
        goTopBtn.style.display = 'block'
      }

      if (scrolled < coords) {
        goTopBtn.style.display = 'none'
      }
    }

    function backToTop() {
      if (window.pageYOffset > 0) {
        window.scrollBy(0, -80)
        setTimeout(backToTop, 0)
      }
    }

    window.addEventListener('scroll', trackScroll)
    goTopBtn.addEventListener('click', backToTop)
  })()
</script>