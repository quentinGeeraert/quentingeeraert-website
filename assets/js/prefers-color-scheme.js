/* Setting prefers-color-scheme */
const rules = document.styleSheets[0].rules
setPreferredColorScheme = function (mode) {
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
      localStorage.setItem('prefers-color-scheme', mode)
      break
    }
  }
}

setPreferredColorScheme(localStorage.getItem('prefers-color-scheme'))
