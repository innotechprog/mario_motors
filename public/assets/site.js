/**
 * Mario Motors — light interactivity (scroll reveals, nav, mobile menu).
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onReady(function () {
    document.documentElement.classList.add('js-ready');

    /* Scroll reveals (stagger wrappers first so children get observed) */
    if (!reduceMotion) {
      document.querySelectorAll('[data-reveal-stagger]').forEach(function (wrap) {
        var children = wrap.children;
        for (var i = 0; i < children.length; i++) {
          children[i].setAttribute('data-reveal', '');
          children[i].style.transitionDelay = i * 55 + 'ms';
        }
      });

      var revealEls = document.querySelectorAll('[data-reveal]');
      if (revealEls.length && 'IntersectionObserver' in window) {
        var io = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (!entry.isIntersecting) return;
              entry.target.classList.add('is-visible');
              io.unobserve(entry.target);
            });
          },
          { root: null, rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
        );
        revealEls.forEach(function (el) {
          io.observe(el);
        });
      } else {
        revealEls.forEach(function (el) {
          el.classList.add('is-visible');
        });
      }
    } else {
      document.querySelectorAll('[data-reveal], [data-reveal-stagger] > *').forEach(function (el) {
        el.classList.add('is-visible');
      });
    }

    /* Nav shadow on scroll */
    var nav = document.getElementById('site-nav');
    if (nav && !reduceMotion) {
      var tick = false;
      function updateNav() {
        var y = window.scrollY || document.documentElement.scrollTop;
        nav.classList.toggle('nav-is-scrolled', y > 12);
        tick = false;
      }
      window.addEventListener('scroll', function () {
        if (!tick) {
          window.requestAnimationFrame(updateNav);
          tick = true;
        }
      }, { passive: true });
      updateNav();
    }

    /* Mobile menu: explicit button toggle for better browser support */
    var mobileNav = document.getElementById('nav-mobile-menu');
    var mobileToggle = document.getElementById('nav-mobile-toggle');
    var mobilePanel = document.getElementById('nav-mobile-panel');
    if (mobileNav && mobileToggle && mobilePanel) {
      function setMobileMenuState(isOpen) {
        mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        mobileToggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
        mobileNav.classList.toggle('is-open', isOpen);
        mobilePanel.hidden = !isOpen;
      }

      setMobileMenuState(false);

      mobileToggle.addEventListener('click', function () {
        var isOpen = mobileToggle.getAttribute('aria-expanded') === 'true';
        setMobileMenuState(!isOpen);
      });

      mobileNav.querySelectorAll('a[href]').forEach(function (a) {
        a.addEventListener('click', function () {
          setMobileMenuState(false);
        });
      });

      document.addEventListener('click', function (e) {
        if (!mobileNav.contains(e.target)) {
          setMobileMenuState(false);
        }
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mobileToggle.getAttribute('aria-expanded') === 'true') {
          setMobileMenuState(false);
          mobileToggle.focus();
        }
      });
    }

    /* Smooth in-page anchors */
    if (!reduceMotion) {
      document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        var id = a.getAttribute('href').slice(1);
        if (!id) return;
        a.addEventListener('click', function (e) {
          var dest = document.getElementById(id);
          if (!dest) return;
          e.preventDefault();
          dest.scrollIntoView({ behavior: 'smooth', block: 'start' });
          history.pushState(null, '', '#' + id);
        });
      });
    }
  });
})();
