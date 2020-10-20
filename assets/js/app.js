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

/* Setting prefers-color-scheme */
const rules = document.styleSheets[0].rules
global.setPreferredColorScheme = function (mode) {
  for (var i = rules.length - 1; i >= 0; i--) {
    var rule = rules[i].media
    if (rule.mediaText.includes('prefers-color-scheme')) {
      switch (mode) {
        case 'light':
          rule.appendMedium('original-prefers-color-scheme')
          if (rule.mediaText.includes('light'))
            rule.deleteMedium('(prefers-color-scheme: light)')
          if (rule.mediaText.includes('dark'))
            rule.deleteMedium('(prefers-color-scheme: dark)')
          break

        case 'dark':
          rule.appendMedium('(prefers-color-scheme: light)')
          rule.appendMedium('(prefers-color-scheme: dark)')
          if (rule.mediaText.includes('original'))
            rule.deleteMedium('original-prefers-color-scheme')
          break

        default:
          rule.appendMedium('(prefers-color-scheme: dark)')
          if (rule.mediaText.includes('light'))
            rule.deleteMedium('(prefers-color-scheme: light)')
          if (rule.mediaText.includes('original'))
            rule.deleteMedium('original-prefers-color-scheme')
      }
      break
    }
  }
}

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
