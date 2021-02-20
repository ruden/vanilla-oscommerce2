<a class="back-to-top">&uarr;</a>

<script>
  (function () {
    const style = document.createElement('style')

    style.innerHTML = '.back-to-top{position:fixed;bottom:80px;right:40px;z-index:999;width:2.5rem;height:2.5rem;text-align:center;line-height:2rem;background:var(--bs-secondary);color:var(--bs-white);cursor:pointer;border-radius:2px;display:none}.back-to-top:hover{background:var(--bs-light);}'
    document.head.appendChild(style)

    const goTopBtn = document.querySelector('.back-to-top')

    function trackScroll() {
      let scrolled = window.pageYOffset
      let coords = document.documentElement.clientHeight

      if (scrolled > coords) {
        goTopBtn.style.display = 'block'
      }

      if (scrolled < coords) {
        goTopBtn.style.display = 'none'
      }
    }

    function backToTop() {
      if (window.pageYOffset > 0) {
        window.scrollTo(0, 0)
      }
    }

    window.addEventListener('scroll', trackScroll)
    goTopBtn.addEventListener('click', backToTop)
  })()
</script>