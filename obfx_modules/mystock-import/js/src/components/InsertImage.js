const { createBlock } = wp.blocks;

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
