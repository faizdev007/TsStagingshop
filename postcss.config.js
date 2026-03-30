import purgecss from '@fullhuman/postcss-purgecss';

export default {
    plugins: [
        purgecss({
            content: [
                './resources/**/*.blade.php',
                './resources/**/*.js',
            ],
            safelist: ['active', 'show', 'open'], // important
        }),
    ],
};