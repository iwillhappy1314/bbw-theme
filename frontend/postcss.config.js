const fs = require('fs');
const postcss = require('postcss');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');
const tailwindcss = require('tailwindcss');

module.exports = {
	plugins: {
		tailwindcss: {},
		autoprefixer: {},
		cssnano: {
			preset: 'default',
		},
	},
};
