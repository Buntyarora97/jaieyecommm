/* Jain Eye Hospital - frontend interactions */
(function () {
  'use strict';

  /* Sticky header shadow */
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 8);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* Dropdown / mega menu (click + hover, keyboard accessible) */
  var items = document.querySelectorAll('.nav__item--has-sub');
  function closeAll(except) {
    items.forEach(function (item) {
      if (item !== except) {
        item.classList.remove('is-open');
        var l = item.querySelector('.nav__link');
        if (l) l.setAttribute('aria-expanded', 'false');
      }
    });
  }
  items.forEach(function (item) {
    var link = item.querySelector('.nav__link');
    link.addEventListener('click', function (ev) {
      ev.preventDefault();
      var open = item.classList.toggle('is-open');
      link.setAttribute('aria-expanded', open ? 'true' : 'false');
      closeAll(item);
    });
    item.addEventListener('mouseenter', function () {
      closeAll(item);
      item.classList.add('is-open');
      link.setAttribute('aria-expanded', 'true');
    });
    item.addEventListener('mouseleave', function () {
      item.classList.remove('is-open');
      link.setAttribute('aria-expanded', 'false');
    });
  });
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape') { closeAll(null); closeDrawer(); }
  });
  document.addEventListener('click', function (ev) {
    if (!ev.target.closest('.nav__item--has-sub')) closeAll(null);
  });

  /* Mobile drawer */
  var drawer = document.getElementById('drawer');
  var openBtn = document.getElementById('navToggle');
  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  if (openBtn) openBtn.addEventListener('click', openDrawer);
  if (drawer) {
    drawer.querySelectorAll('[data-close-drawer]').forEach(function (el) {
      el.addEventListener('click', closeDrawer);
    });
  }

  /* Reveal on scroll */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* Carousels */
  document.querySelectorAll('[data-carousel]').forEach(function (wrap) {
    var track = wrap.querySelector('.carousel__track');
    var prev = wrap.querySelector('[data-carousel-prev]');
    var next = wrap.querySelector('[data-carousel-next]');
    if (!track) return;
    var step = function () {
      var card = track.querySelector(':scope > *');
      return card ? card.getBoundingClientRect().width + 24 : 300;
    };
    if (prev) prev.addEventListener('click', function () {
      track.scrollBy({ left: -step(), behavior: 'smooth' });
    });
    if (next) next.addEventListener('click', function () {
      track.scrollBy({ left: step(), behavior: 'smooth' });
    });
  });

  /* Phone reel player */
  document.querySelectorAll('[data-phone]').forEach(function (phone) {
    var video = phone.querySelector('video');
    if (!video) return;
    var playBtn = phone.querySelector('[data-play]');
    var muteBtn = phone.querySelector('[data-mute]');
    if (playBtn) playBtn.addEventListener('click', function () {
      if (video.paused) { video.play(); playBtn.textContent = '❚❚'; }
      else { video.pause(); playBtn.textContent = '▶'; }
    });
    if (muteBtn) muteBtn.addEventListener('click', function () {
      video.muted = !video.muted;
      muteBtn.textContent = video.muted ? '🔇' : '🔊';
    });
  });

  /* Doctor directory live search/filter */
  var searchInput = document.querySelector('[data-filter-search]');
  if (searchInput) {
    var targets = document.querySelectorAll('[data-filter-item]');
    var pills = document.querySelectorAll('[data-filter-spec]');
    var current = '';
    var apply = function () {
      var q = searchInput.value.toLowerCase();
      targets.forEach(function (el) {
        var text = el.textContent.toLowerCase();
        var spec = el.getAttribute('data-spec') || '';
        var okQ = !q || text.indexOf(q) !== -1;
        var okS = !current || spec === current;
        el.style.display = (okQ && okS) ? '' : 'none';
      });
    };
    searchInput.addEventListener('input', apply);
    pills.forEach(function (pill) {
      pill.addEventListener('click', function (ev) {
        ev.preventDefault();
        current = pill.getAttribute('data-filter-spec');
        pills.forEach(function (p) { p.classList.remove('is-active'); });
        pill.classList.add('is-active');
        apply();
      });
    });
  }

  /* Gallery filter */
  var galleryPills = document.querySelectorAll('[data-gallery-filter]');
  if (galleryPills.length) {
    galleryPills.forEach(function (pill) {
      pill.addEventListener('click', function (ev) {
        ev.preventDefault();
        var cat = pill.getAttribute('data-gallery-filter');
        galleryPills.forEach(function (p) { p.classList.remove('is-active'); });
        pill.classList.add('is-active');
        document.querySelectorAll('[data-gallery-item]').forEach(function (item) {
          item.style.display = (!cat || item.getAttribute('data-gallery-item') === cat) ? '' : 'none';
        });
      });
    });
  }
})();
