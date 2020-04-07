import PhotoList from "./PhotoList";

const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;

const Mystock = () => {
	return (
		<PluginSidebar
			icon="camera"
			name="mystock-sidebar"
			title="MyStockPhotos"
		>
			<div className="mystock-img-container">
				<PhotoList page='1' />
			</div>
		</PluginSidebar>
	);
};
export default Mystock;
