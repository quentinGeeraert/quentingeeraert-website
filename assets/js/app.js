/* Import css */
import '../scss/app.scss'

/* Script for sidebar menu */
var arrayActionSidebar = ['sidebar-open', 'sidebar-close']
arrayActionSidebar.forEach(function (id) {
  document.getElementById(id).addEventListener('click', function () {
    arrayActionSidebar.forEach(function (id) {
      document.getElementById(id).classList.toggle('hidden')
    })
    document.getElementById('sidebar').classList.toggle('hidden')
    document.body.classList.toggle('overflow-hidden')
  })
})

/* Create anchor links */
global.createAnchorLinksByTagName = function (elt) {
  var h = document.getElementsByTagName(elt)
  var ul = document.querySelector('#on-this-page ul')
  ul.querySelectorAll('a').forEach(function (a) {
    var anchor = document.querySelector(a.getAttribute('href'))
    if (anchor) anchor.removeAttribute('id')
  })

  if (h.length === 0) {
    ul.parentElement.classList.add('hidden')
  } else {
    ul.parentElement.classList.remove('hidden')
    ul.classList.remove('animate-pulse')
    ul.innerHTML = null
  }

  Array.from(h).forEach(function (element, index) {
    element.id = 'anchor_' + index
    var li = document.createElement('li')
    li.className = 'mb-4 lg:mb-2'
    var a = document.createElement('a')
    a.href = '#' + element.id
    a.className =
      'block transition-fast hover:translate-r-2px hover:text-gray-900 dark:hover:text-gray-400 font-medium text-gray-600'
    a.innerText =
      element.innerText.charAt(0).toUpperCase() +
      element.innerText.toLowerCase().slice(1)
    li.append(a)
    ul.append(li)
  })
}

/* Animations intersection observer */
if (typeof IntersectionObserver !== 'undefined') {
  const ratio = 0.1
  const options = {
    root: null,
    rootMargin: '0px',
    threshold: ratio
  }
  const handleIntersect = function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.intersectionRatio > ratio) {
        entry.target.classList.remove('reveal')
        observer.unobserve(entry.target)
      }
    })
  }
  /* eslint-env browser */
  const observer = new IntersectionObserver(handleIntersect, options)
  document.querySelectorAll('.reveal').forEach(function (r) {
    observer.observe(r)
  })
} else {
  document.querySelectorAll('.reveal').forEach(function (r) {
    r.classList.remove('reveal')
  })
}

/* Homepage header animation */
const headerTextElement = document.getElementById('header-text')
if (headerTextElement) {
  window.addEventListener('scroll', function () {
    var marginTop = Math.min(Math.max(parseFloat(window.scrollY / 100), 0), 3)
    headerTextElement.style.marginTop = marginTop + '%'
  })
}

/* Toggle themeMode dark/light */
const arrayActionModeSlider = ['mode_slider', 'mode_slider_mobile']
const elementLabel = document.getElementById('mode_slider_mobile_label')

function synchroThemeModeSlider () {
  arrayActionModeSlider.forEach(function (id) {
    document.getElementById(id).checked =
      localStorage.getItem('prefers-color-scheme') === 'light'
  })
}

function toggleThemeMode () {
  var mode = localStorage.getItem('prefers-color-scheme')
  /* global setPreferredColorScheme */
  if (mode === 'light') setPreferredColorScheme('dark')
  else setPreferredColorScheme('light')
  synchroThemeModeSlider()
}

if (elementLabel) {
  arrayActionModeSlider.forEach(function (id) {
    document.getElementById(id).addEventListener('change', toggleThemeMode)
    synchroThemeModeSlider()
  })
  elementLabel.addEventListener('click', toggleThemeMode)
}
