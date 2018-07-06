/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const social_list = {
	facebook: {
		label: __('Facebook'),
		default: true
	},
	twitter: {
		label: __('Twitter'),
		default: true
	},
	pinterest: {
		label: __('Pinterest'),
		default: true
	},
	tumblr: {
		label: __('Tumblr'),
		default: false
	},
	linkedin: {
		label: __('Linkedin'),
		default: false
	}
};


export default social_list