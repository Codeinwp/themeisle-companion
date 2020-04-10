const { createBlock } = wp.blocks;
const { dispatch } = wp.data;
const { insertBlocks } = dispatch( 'core/block-editor' ) || dispatch('core/editor');

const InsertImage = (url = '', alt = '') => {
	if(url === ''){
		return false;
	}
	const block = createBlock("core/image", {
		url: url,
		alt: alt
	});
	wp.data.dispatch('core/editor').insertBlocks(block)
};
export default InsertImage;
