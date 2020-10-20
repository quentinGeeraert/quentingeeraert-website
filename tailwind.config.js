const { colors } = require('tailwindcss/defaultTheme')

module.exports = {
    purge: {
        content: [
            './templates/**/*.twig',
            './assets/**/*.js',
        ]
    },
    theme: {
        extend: {
            screens: {
                'dark': { 'raw': '(prefers-color-scheme: dark)' },
                // => @media (prefers-color-scheme: dark) { ... }
            },
            colors: {
                blue: {
                    ...colors.blue,
                    'dark': 'rgb(21, 32, 43)',
                    'regal-dark': 'rgb(25, 39, 52)',
                },
            }
        }
    },
    variants: {},
    plugins: [],
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    important: true,
}